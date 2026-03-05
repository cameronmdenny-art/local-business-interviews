#!/usr/bin/env python3
"""
Daily Scheduler for Business Scraper
Runs the scraper automatically on a schedule
"""

import os
import time
import logging
from datetime import datetime
from schedule import every, run_pending
import schedule
from scraper import GooglePlacesScraper
from config import GOOGLE_MAPS_API_KEY, BUSINESS_TYPES, LOGS_DIR

# Setup logging
if not os.path.exists(LOGS_DIR):
    os.makedirs(LOGS_DIR)

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s - %(levelname)s - %(message)s",
    handlers=[
        logging.FileHandler(os.path.join(LOGS_DIR, "scheduler.log")),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)


def scheduled_scrape(location: str):
    """Run scraper for a specific location"""
    logger.info(f"🤖 Starting scheduled scrape for {location}")
    
    scraper = GooglePlacesScraper(GOOGLE_MAPS_API_KEY)
    results = scraper.scrape_location(location, BUSINESS_TYPES)
    
    if results:
        timestamp = datetime.now().strftime("%Y%m%d")
        filename = f"businesses_{location.replace(' ', '_').replace(',', '')}_{timestamp}.csv"
        output_file = scraper.export_to_csv(results, filename)
        logger.info(f"✅ Scrape complete for {location}. Saved to {output_file}")
    else:
        logger.warning(f"⚠️  No results found for {location}")


def setup_daily_schedules(locations: list, time_of_day: str = "09:00"):
    """
    Setup daily schedules for multiple locations
    
    Args:
        locations: List of locations to scrape (e.g., ["Austin, TX", "Dallas, TX"])
        time_of_day: Time to run scrape in HH:MM format (default: 09:00)
    """
    logger.info(f"Setting up daily scrapes at {time_of_day} for locations: {locations}")
    
    for location in locations:
        every().day.at(time_of_day).do(scheduled_scrape, location=location)
        logger.info(f"Scheduled daily scrape for {location} at {time_of_day}")


def run_scheduler_loop(locations: list, time_of_day: str = "09:00"):
    """
    Run the scheduler in an infinite loop
    
    Args:
        locations: List of locations to scrape
        time_of_day: Time to run scrape in HH:MM format
    """
    setup_daily_schedules(locations, time_of_day)
    
    logger.info("Scheduler started. Press Ctrl+C to stop.")
    
    try:
        while True:
            run_pending()
            time.sleep(60)  # Check every minute
    except KeyboardInterrupt:
        logger.info("Scheduler stopped by user")


if __name__ == "__main__":
    # Example: Run daily scrapes for multiple cities
    locations = ["Austin, TX", "Dallas, TX"]  # Edit this to your preferred locations
    run_scheduler_loop(locations, time_of_day="09:00")  # Runs at 9 AM daily
