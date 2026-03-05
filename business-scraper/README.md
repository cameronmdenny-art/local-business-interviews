# 🏗️ High-Ticket Home Service Business Scraper

Automatically scrapes Google Places for high-ticket home service businesses with advanced filtering capabilities.

## 📋 Features

- **Multiple Business Types**: Searches 40+ high-ticket home service categories
- **Advanced Filtering**:
  - Review count (0-5 reviews, 100+ reviews, etc.)
  - Star rating (2-3 stars, 5 stars only)
  - Photo count (5+ photos, etc.)
  - Website presence (with/without)
  - Phone number availability
  - Social media presence
  - Business status (excludes closed businesses)

- **Search Anywhere**: Search any US location
- **Automatic Scheduling**: Run daily at a set time
- **CSV Export**: Results exported to organized CSV files
- **Detailed Logging**: Track all scrapes with timestamps

## 🚀 Quick Start

### 1. Install Dependencies
```bash
cd business-scraper
pip install -r requirements.txt
```

### 2. Run a Single Search
```bash
python scraper.py
```
Enter your desired location when prompted (e.g., "Austin, TX")

### 3. Run Daily Automatically (Optional)
```bash
python scheduler.py
```

## ⚙️ Configuration

Edit `config.py` to customize:

- **API Key**: Your Google Places API key (already configured)
- **Business Types**: Add/remove service categories
- **Filters**: Adjust rating, review count, photo count criteria
- **Output**: Change CSV field names and locations

### Example: Filter for Businesses WITHOUT Websites
```python
FILTERS = {
    "require_no_website": True,  # Only businesses without websites
    "min_reviews": 0,
    "max_reviews": 50,  # Low review count = less established online
}
```

### Example: Filter for Only 5-Star Businesses
```python
FILTERS = {
    "min_rating": 5.0,
    "max_rating": 5.0,
}
```

## 📊 Output

Results are saved to `output/` folder as CSV files with:
- Business name
- Address
- Phone number
- Website (if available)
- Rating
- Review count
- Photo count
- Business types
- Opening hours
- Google Places URL

## 📝 Logs

All scrapes are logged to `logs/` folder with timestamps and details.

## 🔧 Daily Automation on Mac

To run the scraper every day at 9 AM:

### Option 1: Using Cron (Recommended)
```bash
crontab -e
```
Add this line (runs at 9 AM daily):
```
0 9 * * * cd /Users/camerondenny/Desktop/local-business-interviews/business-scraper && python scheduler.py >> logs/cron.log 2>&1
```

### Option 2: Using LaunchAgent (Best for Mac)
Create file: `~/Library/LaunchAgents/com.business-scraper.plist`
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.business-scraper</string>
    <key>ProgramArguments</key>
    <array>
        <string>/usr/bin/python3</string>
        <string>/Users/camerondenny/Desktop/local-business-interviews/business-scraper/scheduler.py</string>
    </array>
    <key>StartInterval</key>
    <integer>86400</integer>
    <key>StandardOutPath</key>
    <string>/Users/camerondenny/Desktop/local-business-interviews/business-scraper/logs/launchd.log</string>
    <key>StandardErrorPath</key>
    <string>/Users/camerondenny/Desktop/local-business-interviews/business-scraper/logs/launchd_error.log</string>
</dict>
</plist>
```

Then load it:
```bash
launchctl load ~/Library/LaunchAgents/com.business-scraper.plist
```

## 💰 API Cost

- **Free Tier**: 200 requests/day
- **Your Usage**: ~10-30 searches/day = ~$0-5/month
- **Cost**: Negligible for personal use

## 🔐 Security

API key is stored in `config.py` (not committed if using git). Keep this private!

## 📞 Next Steps

Once you have your data:
1. Filter for businesses without websites
2. Check their review profiles for common complaints
3. Manually verify contact info
4. Use email enrichment API (Hunter.io) for missing emails
5. Create personalized outreach emails with Loom video link

## 📈 Tips for SEO/Website Services

Look for businesses with:
- 0-20 reviews (underdeveloped online presence)
- 2-3 star ratings (potential frustrated customers)
- No website listed
- Limited Google Business Profile optimization
- Few photos (not leveraging Google visibility)

These are your best prospects!

---

**Questions?** Check the logs in `logs/` for detailed execution info.
