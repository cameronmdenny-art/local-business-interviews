# Google Places API Configuration
GOOGLE_MAPS_API_KEY = "AIzaSyCgeZGyyzQA1nka3DYIW1REetwZQuIc8T0"

# High-Ticket Home Service Business Types
BUSINESS_TYPES = [
    # Kitchen & Bathroom
    "kitchen remodeling",
    "bathroom remodeling",
    "kitchen contractor",
    "bathroom contractor",
    
    # Structural & Foundation
    "concrete contractor",
    "foundation repair",
    "retaining wall",
    "mason contractor",
    
    # Pools & Water
    "pool repair",
    "pool replastering",
    "pool contractor",
    "pool builder",
    
    # Outdoor Living
    "deck builder",
    "deck contractor",
    "hardscape contractor",
    "fence contractor",
    "fence installer",
    
    # Roofing & Exterior
    "roof repair",
    "roof replacement",
    "roofer",
    "siding contractor",
    "siding installer",
    "window replacement",
    "door installation",
    "gutter service",
    "exterior painter",
    
    # Specialized Construction
    "ADU builder",
    "remodeling contractor",
    "general contractor",
    "home addition",
    "basement finishing",
    "home renovation",
    
    # Utilities & Systems
    "HVAC contractor",
    "electrical contractor",
    "plumbing contractor",
    "solar installation",
    
    # Trees & Landscaping
    "tree removal",
    "tree service",
    "arborist",
    
    # Additional High-Ticket Services
    "hardwood flooring",
    "tile installer",
    "countertop installation",
    "cabinet maker",
    "waterproofing contractor",
    "chimney repair",
    "chimney sweep",
    "garage door installation",
    "home theater installer",
    "smart home installer",
    "fireplace installation",
    "septic system",
    "well drilling",
    "stone work",
    "masonry",
    "drywall contractor",
    "painting contractor",
]

# Filtering Criteria
FILTERS = {
    "min_reviews": 0,  # Minimum number of reviews
    "max_reviews": None,  # Maximum number of reviews (None for no limit)
    "min_rating": 2.0,  # Minimum rating (1-5)
    "max_rating": 5.0,  # Maximum rating (1-5)
    "min_photos": 0,  # Minimum number of photos
    "max_photos": None,  # Maximum number of photos (None for no limit)
    "require_no_website": False,  # Only businesses WITHOUT websites
    "require_website": False,  # Only businesses WITH websites
    "require_social_media": False,  # Only businesses with social media
    "require_phone": True,  # Only businesses with phone numbers
}

# Output Settings
OUTPUT_DIR = "output"
LOGS_DIR = "logs"
MAX_RESULTS_PER_SCRAPE = 20
INCLUDE_FIELDS = [
    "name",
    "address",
    "phone",
    "website",
    "rating",
    "review_count",
    "photo_count",
    "types",
    "opening_hours",
    "hours_open_now",
]
