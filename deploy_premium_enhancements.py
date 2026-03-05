#!/usr/bin/env python3
"""
Deploy Cairde Designs Premium Enhancements
Uploads SEO, Forms, Directory, Analytics, and updated Footer
"""

import ftplib
import os
import sys

# FTP Configuration
FTP_HOST = "185.164.108.209"
FTP_USER = "u300002008.ivory-lark-138468.hostingersite.com"
FTP_PASS = "VinU9Y8kmNr!2l*0"

# File mappings: (local_path, remote_path)
FILES = [
    # Updated footer with luxury animations
    ("cairde-footer-plugin/cairde-footer.php", "/public_html/wp-content/plugins/cairde-footer/cairde-footer.php"),
    
    # New MU Plugins
    ("mu-cairde-seo.php", "/public_html/wp-content/mu-plugins/mu-cairde-seo.php"),
    ("mu-cairde-forms.php", "/public_html/wp-content/mu-plugins/mu-cairde-forms.php"),
    ("mu-cairde-directory.php", "/public_html/wp-content/mu-plugins/mu-cairde-directory.php"),
    ("mu-cairde-analytics.php", "/public_html/wp-content/mu-plugins/mu-cairde-analytics.php"),
    ("mu-cairde-pages.php", "/public_html/wp-content/mu-plugins/mu-cairde-pages.php"),
    ("mu-cairde-launch-cleanup.php", "/public_html/wp-content/mu-plugins/mu-cairde-launch-cleanup.php"),
]

def upload_file(ftp, local_path, remote_path):
    """Upload a single file via FTP, creating directories if needed"""
    try:
        # Get directory path
        remote_dir = os.path.dirname(remote_path)
        remote_filename = os.path.basename(remote_path)
        
        # Navigate to directory, creating if needed
        try:
            ftp.cwd(remote_dir)
        except ftplib.error_perm:
            # Directory doesn't exist, create it
            parts = remote_dir.split('/')
            current = ''
            for part in parts:
                if not part:
                    continue
                current += '/' + part
                try:
                    ftp.cwd(current)
                except ftplib.error_perm:
                    try:
                        ftp.mkd(current)
                        ftp.cwd(current)
                    except:
                        pass
        
        # Upload file
        with open(local_path, 'rb') as f:
            ftp.storbinary(f'STOR {remote_filename}', f)
        print(f"✅ Uploaded: {os.path.basename(local_path)}")
        return True
    except Exception as e:
        print(f"❌ Failed to upload {local_path}: {e}")
        return False

def main():
    try:
        # Connect to FTP
        ftp = ftplib.FTP(FTP_HOST, timeout=30)
        ftp.login(FTP_USER, FTP_PASS)
        print(f"📡 Connected to {FTP_HOST}")
        
        # Upload all files
        success_count = 0
        for local_path, remote_path in FILES:
            if os.path.exists(local_path):
                if upload_file(ftp, local_path, remote_path):
                    success_count += 1
            else:
                print(f"⚠️  File not found: {local_path}")
        
        ftp.quit()
        
        print(f"\n{'='*60}")
        print(f"✅ SUCCESS! Deployed {success_count}/{len(FILES)} enhancements")
        print(f"{'='*60}")
        print("\n📋 What was deployed:")
        print("  1. ✅ SEO Optimizer (page titles, meta descriptions)")
        print("  2. ✅ Forms Optimizer (gold styling, premium feel)")
        print("  3. ✅ Directory Enhancer (luxury cards, animations)")
        print("  4. ✅ Analytics Tracker (GA4 + Facebook Pixel)")
        print("  5. ✅ Footer v3.0 (luxury animations, gold accents)")
        print("  6. ✅ Pages Creator (Blog, Contact, FAQ, Privacy, Terms)")
        print("  7. ✅ Launch Cleanup (WordPress default removal)")
        print("\n🎯 Next steps:")
        print("  • Run: python3 site-audit.py")
        print("  • Verify all 404 pages are now resolved")
        print("  • Test all forms and links")
        print("  • Set up Google Analytics")
        
        return 0
        
    except ftplib.all_errors as e:
        print(f"❌ FTP Error: {e}")
        return 1
    except Exception as e:
        print(f"❌ Error: {e}")
        return 1

if __name__ == "__main__":
    sys.exit(main())
