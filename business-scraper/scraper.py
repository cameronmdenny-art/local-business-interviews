#!/usr/bin/env python3
"""
Google Places Business Scraper
Finds high-ticket home service businesses with detailed filtering
"""

import os
import csv
import json
import logging
from datetime import datetime
from typing import Dict, List, Optional
import requests
from config import (
    GOOGLE_MAPS_API_KEY,
    BUSINESS_TYPES,
    FILTERS,
    OUTPUT_DIR,
    LOGS_DIR,
    MAX_RESULTS_PER_SCRAPE,
    INCLUDE_FIELDS,
)

# Setup logging
if not os.path.exists(LOGS_DIR):
    os.makedirs(LOGS_DIR)

log_filename = os.path.join(LOGS_DIR, f"scraper_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log")
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s - %(levelname)s - %(message)s",
    handlers=[
        logging.FileHandler(log_filename),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)


class GooglePlacesScraper:
    """Scrapes Google Places API for business data"""
    
    def __init__(self, api_key: str):
        self.api_key = api_key
        self.base_url = "https://maps.googleapis.com/maps/api/place"
        self.results = []
        self.session = requests.Session()
        
    def text_search(self, query: str, location: str, radius: int = 50000) -> List[Dict]:
        """
        Search for businesses using text search
        
        Args:
            query: Business type/name to search
            location: City/location name
            radius: Search radius in meters (default 50km)
            
        Returns:
            List of business results
        """
        endpoint = f"{self.base_url}/textsearch/json"
        
        search_query = f"{query} in {location}"
        
        params = {
            "query": search_query,
            "key": self.api_key,
            "type": "business"
        }
        
        try:
            logger.info(f"Searching: {search_query}")
            response = self.session.get(endpoint, params=params, timeout=10)
            response.raise_for_status()
            data = response.json()
            
            if data["status"] != "OK":
                logger.warning(f"API Response: {data['status']}")
                return []
            
            results = data.get("results", [])
            logger.info(f"Found {len(results)} results for '{query}'")
            
            return results
            
        except requests.exceptions.RequestException as e:
            logger.error(f"Request failed for '{query}': {str(e)}")
            return []
    
    def get_place_details(self, place_id: str) -> Dict:
        """
        Get detailed information about a place
        
        Args:
            place_id: Google Places place ID
            
        Returns:
            Place details dictionary
        """
        endpoint = f"{self.base_url}/details/json"
        
        params = {
            "place_id": place_id,
            "key": self.api_key,
            "fields": "name,formatted_address,international_phone_number,website,url,rating,user_ratings_total,photos,opening_hours,types,business_status"
        }
        
        try:
            response = self.session.get(endpoint, params=params, timeout=10)
            response.raise_for_status()
            data = response.json()
            
            if data["status"] == "OK":
                return data.get("result", {})
            else:
                logger.warning(f"Details API returned: {data['status']}")
                return {}
                
        except requests.exceptions.RequestException as e:
            logger.error(f"Failed to get details for {place_id}: {str(e)}")
            return {}
    
    def filter_result(self, result: Dict, filters: Dict) -> bool:
        """
        Apply filters to a business result
        
        Args:
            result: Business data dictionary
            
        Returns:
            True if result passes all filters
        """
        # Rating filter
        rating = result.get("rating")
        if rating is not None:
            if rating < filters["min_rating"] or rating > filters["max_rating"]:
                return False
        
        # Review count filter
        review_count = result.get("user_ratings_total", result.get("review_count", 0))
        if review_count < filters["min_reviews"]:
            return False
        
        if filters["max_reviews"] is not None and review_count > filters["max_reviews"]:
            return False
        
        # Photo count filter
        photo_count = len(result.get("photos", []))
        if photo_count < filters["min_photos"]:
            return False
        if filters.get("max_photos") is not None and photo_count > filters["max_photos"]:
            return False
        
        # Website filters
        has_website = bool(result.get("website"))
        if filters["require_no_website"] and has_website:
            return False
        if filters["require_website"] and not has_website:
            return False
        
        # Social media filter (check if social links in website or business description)
        if filters["require_social_media"]:
            has_social = self._has_social_media(result)
            if not has_social:
                return False
        
        # Phone filter
        if filters["require_phone"] and not result.get("international_phone_number"):
            return False
        
        # Business status filter
        if result.get("business_status") == "CLOSED_PERMANENTLY":
            return False
        
        return True
    
    def _has_social_media(self, result: Dict) -> bool:
        """Check if business has social media presence"""
        social_keywords = ["facebook", "instagram", "linkedin", "youtube", "twitter", "tiktok"]
        
        # Check website if available
        website = result.get("website", "")
        if website:
            website_lower = website.lower()
            return any(keyword in website_lower for keyword in social_keywords)
        
        # Could extend this to check all URLs in result
        return False
    
    def format_result(self, result: Dict) -> Dict:
        """Format result for output"""
        formatted = {}
        
        field_mapping = {
            "name": "name",
            "address": "formatted_address",
            "phone": "international_phone_number",
            "website": "website",
            "rating": "rating",
            "review_count": "user_ratings_total",
            "photo_count": "photos",
            "types": "types",
            "opening_hours": "opening_hours",
            "hours_open_now": "opening_hours",
            "url": "url",
            "place_id": "place_id",
        }
        
        for output_field, source_field in field_mapping.items():
            if output_field in INCLUDE_FIELDS or output_field in ["url", "place_id"]:
                if source_field == "photos":
                    formatted[output_field] = len(result.get(source_field, []))
                elif source_field == "opening_hours":
                    if output_field == "hours_open_now":
                        formatted[output_field] = result.get(source_field, {}).get("open_now", "Unknown")
                    else:
                        formatted[output_field] = json.dumps(result.get(source_field, {}))
                elif source_field == "types":
                    formatted[output_field] = ", ".join(result.get(source_field, []))
                else:
                    formatted[output_field] = result.get(source_field, "")

        location = result.get("geometry", {}).get("location", {})
        formatted["latitude"] = location.get("lat", "")
        formatted["longitude"] = location.get("lng", "")
        formatted["maps_url"] = result.get("url", "")
        
        return formatted
    
    def scrape_location(
        self,
        location: str,
        business_types: List[str],
        max_results: int = MAX_RESULTS_PER_SCRAPE,
        filters: Optional[Dict] = None,
    ) -> List[Dict]:
        """
        Scrape all business types for a location
        
        Args:
            location: City/location to search
            business_types: List of business types to search for
            
        Returns:
            List of filtered and formatted results
        """
        logger.info(f"=== Starting scrape for: {location} ===")
        all_results = []
        seen_places = set()  # Avoid duplicates
        active_filters = {**FILTERS, **(filters or {})}
        
        for business_type in business_types:
            if len(all_results) >= max_results:
                logger.info(f"Reached max results limit ({max_results}). Stopping early.")
                break

            results = self.text_search(business_type, location)
            
            for result in results:
                if len(all_results) >= max_results:
                    break

                place_id = result.get("place_id")
                
                # Skip duplicates
                if place_id in seen_places:
                    continue
                seen_places.add(place_id)
                
                # Get detailed information
                details = self.get_place_details(place_id)
                if not details:
                    continue
                
                # Merge basic and detailed results
                merged = {**result, **details}
                
                # Apply filters
                if self.filter_result(merged, active_filters):
                    formatted = self.format_result(merged)
                    all_results.append(formatted)
                    if len(all_results) >= max_results:
                        logger.info(f"Reached max results limit ({max_results}).")
                        break
        
        logger.info(f"Found {len(all_results)} results matching filters for {location}")
        return all_results
    
    def export_to_csv(self, results: List[Dict], filename: str = None) -> str:
        """
        Export results to CSV
        
        Args:
            results: List of business results
            filename: Output filename (default: auto-generated)
            
        Returns:
            Path to output file
        """
        if not os.path.exists(OUTPUT_DIR):
            os.makedirs(OUTPUT_DIR)
        
        if filename is None:
            filename = f"businesses_{datetime.now().strftime('%Y%m%d_%H%M%S')}.csv"
        
        filepath = os.path.join(OUTPUT_DIR, filename)
        
        if results:
            fieldnames = list(results[0].keys())
            with open(filepath, "w", newline="", encoding="utf-8") as csvfile:
                writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
                writer.writeheader()
                writer.writerows(results)
            
            logger.info(f"Exported {len(results)} results to {filepath}")
            return filepath
        else:
            logger.warning("No results to export")
            return None


def main():
    """Main execution"""
    
    # User input
    location = input("\n🔍 Enter location to search (e.g., 'Austin, TX', 'New York, NY'): ").strip()
    
    if not location:
        logger.error("Location is required")
        return
    
    scraper = GooglePlacesScraper(GOOGLE_MAPS_API_KEY)
    results = scraper.scrape_location(location, BUSINESS_TYPES, MAX_RESULTS_PER_SCRAPE)
    
    if results:
        output_file = scraper.export_to_csv(results)
        print(f"\n✅ Scraping complete! Found {len(results)} businesses")
        print(f"📊 Results saved to: {output_file}")
    else:
        print(f"\n⚠️  No results found for {location}")
    
    print(f"📝 Log file: {log_filename}")


if __name__ == "__main__":
    main()
