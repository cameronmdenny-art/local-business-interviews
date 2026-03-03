# Recommendation Form Setup Guide

## 🎯 Overview
The recommendation form collects business recommendations from visitors with a clean, premium Apple/Google-style UI. Submissions can be sent to GoHighLevel for email marketing campaigns.

## 📋 Quick Setup (3 Steps)

### Step 1: Create the Page in WordPress
1. Go to **WordPress Admin → Pages → Add New**
2. Title: "Recommend a Business" (or any title you prefer)
3. **IMPORTANT**: Set the page slug to `recommend`
   - Click "Edit" next to the permalink
   - Change slug to: `recommend`
4. Leave the content area empty (the plugin handles the form)
5. Publish the page

**Your form will be live at:** `https://yoursite.com/recommend/`

### Step 2: Connect to GoHighLevel (Optional)
To send submissions to GHL for email campaigns:

1. Get your GHL webhook URL from GoHighLevel:
   - Go to GHL → Automation → Workflows
   - Create a new workflow with a "Webhook" trigger
   - Copy the webhook URL

2. Add the webhook to WordPress:
   - Go to **WordPress Admin → Settings → General**
   - Add this line to your `wp-config.php` file:
   ```php
   // Add this above the "That's all, stop editing!" line
   define( 'LBI_GHL_WEBHOOK_URL', 'your-ghl-webhook-url-here' );
   ```
   
   **OR** use the WordPress options table:
   ```php
   // Run this once via phpMyAdmin or plugins
   update_option( 'lbi_ghl_webhook_url', 'your-ghl-webhook-url-here' );
   ```

### Step 3: Test the Form
1. Visit `https://yoursite.com/recommend/`
2. Fill out and submit a test recommendation
3. Check:
   - ✅ Redirects to `/interviews/` after 2 seconds
   - ✅ Submission appears in **WordPress Admin → Directory** (as Draft)
   - ✅ Admin receives email notification
   - ✅ Data appears in GHL (if webhook configured)

## 🎨 Using the Shortcode
You can also add the form anywhere using the shortcode:

```
[lbi_recommend_form]
```

Works in: Posts, Pages, Widgets

## 📊 Submission Data Captured

### Business Information (Required)
- Project/Business Name *
- Email *
- Phone *
- Website (optional)
- Social Media (optional)

### Recommender Information (Optional)
- Your Name
- Your Email
- Your Phone (only if different from business)

## 🔒 Security Features
- ✅ WordPress nonce verification
- ✅ Rate limiting (3 submissions per hour per IP)
- ✅ Email validation
- ✅ Input sanitization
- ✅ Ajax submission (no page reload)

## 📬 GoHighLevel Webhook Payload

When a submission is sent to GHL, the webhook receives:

```json
{
  "project_name": "Artisan Coffee Co.",
  "business_email": "hello@artisancoffee.com",
  "business_phone": "(555) 123-4567",
  "website": "https://artisancoffee.com",
  "social_media": "@artisancoffee",
  "recommender_name": "John Doe",
  "recommender_email": "john@example.com",
  "recommender_phone": "(555) 987-6543",
  "source": "Website Recommendation Form",
  "submitted_at": "2026-03-02T14:30:00Z"
}
```

## 🎯 Post-Submission Flow

1. **User submits form** → Ajax request to WordPress
2. **WordPress creates draft** → Stored as "Directory" custom post type
3. **Sends to GHL** → Webhook fires (if configured)
4. **Emails admin** → Notification to site admin email
5. **Redirects user** → To `/interviews/` page after 2 seconds
6. **Rate limit applied** → IP tracked for 1 hour

## 🛠️ Customization

### Change Redirect URL
Edit `page-recommend.php` line ~370 or `includes/forms.php` line ~1037:
```javascript
window.location.href = '<?php echo esc_url( home_url( '/your-custom-page/' ) ); ?>';
```

### Adjust Rate Limit
Edit `includes/forms.php` line ~985:
```php
if ( $rate_count && $rate_count >= 3 ) { // Change 3 to your desired limit
```

### Customize Form Fields
Edit `page-recommend.php` (lines 180-340) or shortcode in `includes/forms.php` (lines 850-1000)

## 📍 Page URL
**Live URL:** `https://ivory-lark-138468.hostingersite.com/recommend/`

## ✅ Success!
Your recommendation form is now live and ready to collect submissions!
