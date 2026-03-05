from flask_sqlalchemy import SQLAlchemy
from datetime import datetime
from flask_login import UserMixin
from werkzeug.security import generate_password_hash, check_password_hash

db = SQLAlchemy()


class User(UserMixin, db.Model):
    __tablename__ = 'users'

    id = db.Column(db.Integer, primary_key=True)
    email = db.Column(db.String(255), unique=True, nullable=False, index=True)
    full_name = db.Column(db.String(255))
    password_hash = db.Column(db.String(255), nullable=True)
    google_sub = db.Column(db.String(255), unique=True, nullable=True)
    avatar_url = db.Column(db.String(500), nullable=True)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    last_login_at = db.Column(db.DateTime, nullable=True)

    scrapes = db.relationship('Scrape', backref='user', lazy=True)

    def set_password(self, password: str):
        self.password_hash = generate_password_hash(password)

    def check_password(self, password: str) -> bool:
        if not self.password_hash:
            return False
        return check_password_hash(self.password_hash, password)

    def to_dict(self):
        return {
            'id': self.id,
            'email': self.email,
            'full_name': self.full_name,
            'avatar_url': self.avatar_url,
        }


class Scrape(db.Model):
    """Database model for storing scrape history"""
    __tablename__ = 'scrapes'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=True, index=True)
    location = db.Column(db.String(255), nullable=False)
    status = db.Column(db.String(50), default='pending')  # pending, running, completed, failed
    total_results = db.Column(db.Integer, default=0)
    businesses_found = db.Column(db.Integer, default=0)
    csv_filename = db.Column(db.String(255))
    error_message = db.Column(db.Text)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    completed_at = db.Column(db.DateTime)
    
    def to_dict(self):
        return {
            'id': self.id,
            'user_id': self.user_id,
            'location': self.location,
            'status': self.status,
            'total_results': self.total_results,
            'businesses_found': self.businesses_found,
            'csv_filename': self.csv_filename,
            'created_at': self.created_at.isoformat(),
            'completed_at': self.completed_at.isoformat() if self.completed_at else None,
            'duration': str(self.completed_at - self.created_at) if self.completed_at else None
        }


class SearchHistory(db.Model):
    """Database model for storing user search history"""
    __tablename__ = 'search_history'
    
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False, index=True)
    location = db.Column(db.String(255), nullable=False)
    is_default = db.Column(db.Boolean, default=False)  # Flag for pre-populated default searches
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    last_searched = db.Column(db.DateTime, nullable=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'location': self.location,
            'last_searched': self.last_searched.isoformat() if self.last_searched else None,
        }
