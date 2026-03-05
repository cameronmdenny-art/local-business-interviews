from flask import Flask, render_template, request, jsonify, send_file, url_for, redirect, flash
from flask_login import LoginManager, login_user, logout_user, login_required, current_user
from authlib.integrations.flask_client import OAuth
from dotenv import load_dotenv
from models import db, Scrape, User, SearchHistory
from scraper import GooglePlacesScraper
from config import GOOGLE_MAPS_API_KEY, BUSINESS_TYPES, OUTPUT_DIR, MAX_RESULTS_PER_SCRAPE
from datetime import datetime
import os
import threading
import logging
import csv
import sqlite3

load_dotenv()

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///scraper.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['SECRET_KEY'] = os.getenv('SECRET_KEY', 'change-this-secret-key')

GOOGLE_OAUTH_CLIENT_ID = os.getenv('GOOGLE_OAUTH_CLIENT_ID')
GOOGLE_OAUTH_CLIENT_SECRET = os.getenv('GOOGLE_OAUTH_CLIENT_SECRET')

db.init_app(app)

login_manager = LoginManager()
login_manager.init_app(app)
login_manager.login_view = 'login'

oauth = OAuth(app)
if GOOGLE_OAUTH_CLIENT_ID and GOOGLE_OAUTH_CLIENT_SECRET:
    oauth.register(
        name='google',
        server_metadata_url='https://accounts.google.com/.well-known/openid-configuration',
        client_id=GOOGLE_OAUTH_CLIENT_ID,
        client_secret=GOOGLE_OAUTH_CLIENT_SECRET,
        client_kwargs={'scope': 'openid email profile'},
    )

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))


@login_manager.unauthorized_handler
def unauthorized_handler():
    if request.path.startswith('/api/'):
        return jsonify({'error': 'Authentication required'}), 401
    return redirect(url_for('login'))


def _sqlite_db_path():
    return os.path.join(app.instance_path, 'scraper.db')


def _ensure_schema_columns():
    db_path = _sqlite_db_path()
    if not os.path.exists(db_path):
        return

    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    cursor.execute("PRAGMA table_info(scrapes)")
    scrape_columns = {row[1] for row in cursor.fetchall()}
    if 'user_id' not in scrape_columns:
        cursor.execute("ALTER TABLE scrapes ADD COLUMN user_id INTEGER")

    cursor.execute("PRAGMA table_info(users)")
    user_columns = {row[1] for row in cursor.fetchall()}
    if user_columns:
        if 'google_sub' not in user_columns:
            cursor.execute("ALTER TABLE users ADD COLUMN google_sub VARCHAR(255)")
        if 'avatar_url' not in user_columns:
            cursor.execute("ALTER TABLE users ADD COLUMN avatar_url VARCHAR(500)")
        if 'last_login_at' not in user_columns:
            cursor.execute("ALTER TABLE users ADD COLUMN last_login_at DATETIME")
        if 'full_name' not in user_columns:
            cursor.execute("ALTER TABLE users ADD COLUMN full_name VARCHAR(255)")

    # Check if search_history table exists and has necessary columns
    cursor.execute("PRAGMA table_info(search_history)")
    search_history_columns = {row[1] for row in cursor.fetchall()}
    if search_history_columns:
        if 'last_searched' not in search_history_columns:
            cursor.execute("ALTER TABLE search_history ADD COLUMN last_searched DATETIME")
        if 'is_default' not in search_history_columns:
            cursor.execute("ALTER TABLE search_history ADD COLUMN is_default BOOLEAN DEFAULT 0")

    conn.commit()
    conn.close()


def _coerce_float(value, default):
    try:
        return float(value)
    except (TypeError, ValueError):
        return default


def _coerce_int(value, default=None):
    if value in (None, "", "null"):
        return default
    try:
        return int(value)
    except (TypeError, ValueError):
        return default


def _parse_bool(value, default=False):
    if isinstance(value, bool):
        return value
    if isinstance(value, str):
        return value.lower() in {"1", "true", "yes", "on"}
    return default


def _build_filters(payload: dict):
    min_reviews = _coerce_int(payload.get("min_reviews"), 0)
    max_reviews = _coerce_int(payload.get("max_reviews"), None)
    min_rating = _coerce_float(payload.get("min_rating"), 2.0)
    max_rating = _coerce_float(payload.get("max_rating"), 5.0)
    min_photos = _coerce_int(payload.get("min_photos"), 0)
    max_photos = _coerce_int(payload.get("max_photos"), None)

    if max_reviews is not None and min_reviews is not None and min_reviews > max_reviews:
        min_reviews, max_reviews = max_reviews, min_reviews
    if max_rating is not None and min_rating is not None and min_rating > max_rating:
        min_rating, max_rating = max_rating, min_rating
    if max_photos is not None and min_photos is not None and min_photos > max_photos:
        min_photos, max_photos = max_photos, min_photos

    return {
        "min_reviews": min_reviews,
        "max_reviews": max_reviews,
        "min_rating": min_rating,
        "max_rating": max_rating,
        "min_photos": min_photos,
        "max_photos": max_photos,
        "require_no_website": _parse_bool(payload.get("require_no_website"), False),
        "require_website": _parse_bool(payload.get("require_website"), False),
        "require_social_media": _parse_bool(payload.get("require_social_media"), False),
        "require_phone": _parse_bool(payload.get("require_phone"), True),
    }


def _sanitize_business_types(selected_types):
    if not isinstance(selected_types, list) or not selected_types:
        return BUSINESS_TYPES
    allowed = set(BUSINESS_TYPES)
    cleaned = [business_type for business_type in selected_types if business_type in allowed]
    return cleaned if cleaned else BUSINESS_TYPES


def _populate_default_search_history(user):
    """Populate default search cities for a new user"""
    DEFAULT_CITIES = [
        "San Diego, CA",
        "El Cajon, CA",
        "Dallas, TX",
        "Richardson, TX",
        "Frisco, TX",
        "Lakeside, CA"
    ]
    
    for city in DEFAULT_CITIES:
        search = SearchHistory(user_id=user.id, location=city, is_default=True)
        db.session.add(search)
    
    db.session.commit()


def scrape_and_save(scrape_id: int, user_id: int, location: str, max_results: int, filters: dict, selected_business_types: list):
    """Background task to run scraper"""
    scrape = None
    with app.app_context():
        try:
            scrape = Scrape.query.filter_by(id=scrape_id, user_id=user_id).first()
            if not scrape:
                logger.error(f"Scrape {scrape_id} not found for user {user_id}")
                return
            scrape.status = 'running'
            db.session.commit()

            scraper = GooglePlacesScraper(GOOGLE_MAPS_API_KEY)
            results = scraper.scrape_location(location, selected_business_types, max_results, filters)

            scrape.total_results = len(results)
            scrape.businesses_found = len(results)

            if results:
                filename = f"businesses_{location.replace(' ', '_').replace(',', '')}_{datetime.now().strftime('%Y%m%d_%H%M%S')}.csv"
                scraper.export_to_csv(results, filename)
                scrape.csv_filename = filename

            scrape.status = 'completed'
            scrape.completed_at = datetime.utcnow()
            db.session.commit()

        except Exception as e:
            logger.error(f"Scrape failed: {str(e)}")
            if scrape is not None:
                scrape.status = 'failed'
                scrape.error_message = str(e)
                scrape.completed_at = datetime.utcnow()
                db.session.commit()


@app.route('/')
@login_required
def index():
    """Home page"""
    return render_template('index.html')


@app.route('/auth/register', methods=['GET', 'POST'])
def register():
    if current_user.is_authenticated:
        return redirect(url_for('index'))

    if request.method == 'POST':
        email = (request.form.get('email') or '').strip().lower()
        password = request.form.get('password') or ''
        full_name = (request.form.get('full_name') or '').strip()

        if not email or not password:
            flash('Email and password are required.', 'error')
            return render_template('register.html')

        if User.query.filter_by(email=email).first():
            flash('An account with that email already exists.', 'error')
            return render_template('register.html')

        user = User(email=email, full_name=full_name)
        user.set_password(password)
        user.last_login_at = datetime.utcnow()
        db.session.add(user)
        db.session.commit()
        
        # Populate default search history for new user
        _populate_default_search_history(user)
        
        login_user(user, remember=True)
        return redirect(url_for('index'))

    return render_template('register.html')


@app.route('/auth/login', methods=['GET', 'POST'])
def login():
    if current_user.is_authenticated:
        return redirect(url_for('index'))

    if request.method == 'POST':
        email = (request.form.get('email') or '').strip().lower()
        password = request.form.get('password') or ''
        remember = _parse_bool(request.form.get('remember'), True)

        user = User.query.filter_by(email=email).first()
        if not user or not user.check_password(password):
            flash('Invalid email or password.', 'error')
            return render_template('login.html', google_enabled=bool(GOOGLE_OAUTH_CLIENT_ID and GOOGLE_OAUTH_CLIENT_SECRET))

        user.last_login_at = datetime.utcnow()
        db.session.commit()
        login_user(user, remember=remember)
        return redirect(url_for('index'))

    return render_template('login.html', google_enabled=bool(GOOGLE_OAUTH_CLIENT_ID and GOOGLE_OAUTH_CLIENT_SECRET))


@app.route('/auth/google/login')
def google_login():
    if not (GOOGLE_OAUTH_CLIENT_ID and GOOGLE_OAUTH_CLIENT_SECRET):
        flash('Google login is not configured yet.', 'error')
        return redirect(url_for('login'))

    redirect_uri = url_for('google_callback', _external=True)
    return oauth.google.authorize_redirect(redirect_uri)


@app.route('/auth/google/callback')
def google_callback():
    if not (GOOGLE_OAUTH_CLIENT_ID and GOOGLE_OAUTH_CLIENT_SECRET):
        flash('Google login is not configured yet.', 'error')
        return redirect(url_for('login'))

    token = oauth.google.authorize_access_token()
    userinfo = token.get('userinfo')
    if not userinfo:
        userinfo = oauth.google.get('https://openidconnect.googleapis.com/v1/userinfo').json()

    email = (userinfo.get('email') or '').strip().lower()
    google_sub = userinfo.get('sub')
    full_name = userinfo.get('name')
    avatar_url = userinfo.get('picture')

    if not email:
        flash('Google account did not provide an email.', 'error')
        return redirect(url_for('login'))

    user = User.query.filter((User.google_sub == google_sub) | (User.email == email)).first()
    is_new_user = False
    if not user:
        user = User(email=email, full_name=full_name, google_sub=google_sub, avatar_url=avatar_url)
        db.session.add(user)
        db.session.commit()
        is_new_user = True
    else:
        user.google_sub = google_sub or user.google_sub
        user.avatar_url = avatar_url or user.avatar_url
        user.full_name = full_name or user.full_name

    user.last_login_at = datetime.utcnow()
    db.session.commit()
    
    # Populate default search history for new users
    if is_new_user:
        _populate_default_search_history(user)
    
    login_user(user, remember=True)
    return redirect(url_for('index'))


@app.route('/auth/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('login'))


@app.route('/api/start-scrape', methods=['POST'])
@login_required
def start_scrape():
    """Start a new scrape"""
    data = request.json
    location = data.get('location', '').strip()
    
    if not location:
        return jsonify({'error': 'Location is required'}), 400
    
    max_results = _coerce_int(data.get('max_results'), MAX_RESULTS_PER_SCRAPE)
    if max_results is None or max_results <= 0:
        max_results = MAX_RESULTS_PER_SCRAPE
    filters = _build_filters(data.get('filters', {}))
    selected_business_types = _sanitize_business_types(data.get('business_types', []))

    # Create new scrape record
    scrape = Scrape(location=location, status='pending', user_id=current_user.id)
    db.session.add(scrape)
    db.session.commit()
    
    # Save search to history
    search = SearchHistory.query.filter_by(user_id=current_user.id, location=location).first()
    if search:
        search.last_searched = datetime.utcnow()
    else:
        search = SearchHistory(user_id=current_user.id, location=location, last_searched=datetime.utcnow())
        db.session.add(search)
    db.session.commit()
    
    # Start background task
    thread = threading.Thread(target=scrape_and_save, args=(scrape.id, current_user.id, location, max_results, filters, selected_business_types))
    thread.daemon = True
    thread.start()
    
    return jsonify({
        'scrape_id': scrape.id,
        'message': f'Scrape started for {location}',
        'max_results': max_results,
        'business_types_count': len(selected_business_types),
    }), 201


@app.route('/api/business-types', methods=['GET'])
@login_required
def get_business_types():
    return jsonify({'business_types': BUSINESS_TYPES, 'count': len(BUSINESS_TYPES)}), 200


@app.route('/api/scrape-status/<int:scrape_id>', methods=['GET'])
@login_required
def scrape_status(scrape_id):
    """Get status of a scrape"""
    scrape = Scrape.query.filter_by(id=scrape_id, user_id=current_user.id).first()
    
    if not scrape:
        return jsonify({'error': 'Scrape not found'}), 404
    
    return jsonify(scrape.to_dict()), 200


@app.route('/api/scrapes', methods=['GET'])
@login_required
def get_scrapes():
    """Get all scrapes"""
    scrapes = Scrape.query.filter_by(user_id=current_user.id).order_by(Scrape.created_at.desc()).all()
    return jsonify([s.to_dict() for s in scrapes]), 200


@app.route('/api/results/<int:scrape_id>', methods=['GET'])
@login_required
def get_results(scrape_id):
    scrape = Scrape.query.filter_by(id=scrape_id, user_id=current_user.id).first()
    if not scrape:
        return jsonify({'error': 'Scrape not found'}), 404
    if not scrape.csv_filename:
        return jsonify({'results': [], 'count': 0}), 200

    filepath = os.path.join(OUTPUT_DIR, scrape.csv_filename)
    if not os.path.exists(filepath):
        return jsonify({'error': 'CSV file not found'}), 404

    min_rating = _coerce_float(request.args.get('min_rating'), 0.0)
    max_rating = _coerce_float(request.args.get('max_rating'), 5.0)
    min_reviews = _coerce_int(request.args.get('min_reviews'), 0)
    max_reviews = _coerce_int(request.args.get('max_reviews'), None)
    min_photos = _coerce_int(request.args.get('min_photos'), 0)
    max_photos = _coerce_int(request.args.get('max_photos'), None)
    has_website = request.args.get('has_website', 'any')

    if min_rating > max_rating:
        min_rating, max_rating = max_rating, min_rating
    if max_reviews is not None and min_reviews > max_reviews:
        min_reviews, max_reviews = max_reviews, min_reviews
    if max_photos is not None and min_photos > max_photos:
        min_photos, max_photos = max_photos, min_photos

    results = []
    with open(filepath, 'r', encoding='utf-8') as csvfile:
        reader = csv.DictReader(csvfile)
        for row in reader:
            rating = _coerce_float(row.get('rating'), 0.0)
            review_count = _coerce_int(row.get('review_count'), 0)
            photo_count = _coerce_int(row.get('photo_count'), 0)
            website = (row.get('website') or '').strip()

            if rating < min_rating:
                continue
            if rating > max_rating:
                continue
            if review_count < min_reviews:
                continue
            if max_reviews is not None and review_count > max_reviews:
                continue
            if photo_count < min_photos:
                continue
            if max_photos is not None and photo_count > max_photos:
                continue
            if has_website == 'yes' and not website:
                continue
            if has_website == 'no' and website:
                continue

            results.append(row)

    return jsonify({'results': results, 'count': len(results)}), 200


@app.route('/api/download/<filename>', methods=['GET'])
@login_required
def download_file(filename):
    """Download CSV file"""
    # Security: validate filename to prevent directory traversal
    if '..' in filename or '/' in filename:
        return jsonify({'error': 'Invalid filename'}), 400
    
    scrape = Scrape.query.filter_by(user_id=current_user.id, csv_filename=filename).first()
    if not scrape:
        return jsonify({'error': 'File not found'}), 404

    filepath = os.path.join(OUTPUT_DIR, scrape.csv_filename)
    
    if not os.path.exists(filepath):
        return jsonify({'error': 'File not found'}), 404
    
    return send_file(filepath, as_attachment=True)


@app.route('/api/delete-scrape/<int:scrape_id>', methods=['DELETE'])
@login_required
def delete_scrape(scrape_id):
    """Delete a scrape and its file"""
    scrape = Scrape.query.filter_by(id=scrape_id, user_id=current_user.id).first()
    
    if not scrape:
        return jsonify({'error': 'Scrape not found'}), 404
    
    # Delete file if exists
    if scrape.csv_filename:
        filepath = os.path.join(OUTPUT_DIR, scrape.csv_filename)
        if os.path.exists(filepath):
            os.remove(filepath)
    
    db.session.delete(scrape)
    db.session.commit()
    
    return jsonify({'message': 'Scrape deleted'}), 200


@app.route('/api/search-history', methods=['GET'])
@login_required
def get_search_history():
    """Get all search history for current user"""
    searches = SearchHistory.query.filter_by(user_id=current_user.id).order_by(SearchHistory.last_searched.desc(), SearchHistory.created_at.desc()).all()
    return jsonify({
        'searches': [search.to_dict() for search in searches]
    }), 200


@app.route('/api/search-history', methods=['POST'])
@login_required
def save_search_history():
    """Save a location to search history"""
    data = request.json
    location = (data.get('location') or '').strip()
    
    if not location:
        return jsonify({'error': 'Location is required'}), 400
    
    # Check if this location already exists in history
    search = SearchHistory.query.filter_by(user_id=current_user.id, location=location).first()
    if search:
        # Update last_searched timestamp
        search.last_searched = datetime.utcnow()
    else:
        # Create new search history entry
        search = SearchHistory(user_id=current_user.id, location=location)
    
    db.session.add(search)
    db.session.commit()
    
    return jsonify({'message': 'Search saved', 'search': search.to_dict()}), 200


if __name__ == '__main__':
    with app.app_context():
        os.makedirs(app.instance_path, exist_ok=True)
        db.create_all()
        _ensure_schema_columns()
    app.run(debug=True, host='127.0.0.1', port=5000)
