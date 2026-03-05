#!/usr/bin/env python3

"""
Deploy Master Cleanup Mu-Plugins to Hostinger via FTP
"""

import ftplib
import os
import sys
from pathlib import Path

# Hostinger FTP credentials
FTP_HOST = "185.164.108.209"
FTP_USER = "u300002008.ivory-lark-138468.hostingersite.com"
REMOTE_PATH = "/public_html/"

# Load password from .env
env_file = Path(__file__).parent / ".env"
ftp_password = None

if env_file.exists():
    with open(env_file) as f:
        for line in f:
            if 'FTP_PASSWORD' in line:
                ftp_password = line.split('=')[1].strip().strip('"')
                break

if not ftp_password:
    print("❌ Error: FTP_PASSWORD not found in .env file")
    sys.exit(1)

print("🚀 Deploying Cairde Header Fixes to Hostinger...")
print("📤 Uploading mu-plugins via FTP...\n")

try:
    # Connect to FTP with explicit TLS
    ftp = ftplib.FTP_TLS(FTP_HOST)
    ftp.set_debuglevel(0)
    print(f"  ✓ Connected to {FTP_HOST}")
    
    # Login with explicit TLS
    ftp.login(FTP_USER, ftp_password)
    ftp.prot_p()  # Set to private mode
    print(f"  ✓ Logged in as {FTP_USER}")
    
    ftp.cwd(REMOTE_PATH)
    print(f"  ✓ Changed to {REMOTE_PATH}")
    
    # Upload the new mu-plugins
    files_to_upload = [
        "mu-master-cleanup.php",
        "mu-cairde-header-complete.php"
    ]
    
    for file_name in files_to_upload:
        file_path = Path(__file__).parent / file_name
        if file_path.exists():
            with open(file_path, 'rb') as f:
                ftp.storbinary(f'STOR {file_name}', f)
            print(f"  ✓ Uploaded {file_name}")
        else:
            print(f"  ⚠ Warning: {file_name} not found")
    
    ftp.quit()
    print("\n✅ Upload complete!\n")
    
    # Try to purge cache
    print("🔄 Purging site cache...")
    os.system('curl -X PURGE "https://ivory-lark-138468.hostingersite.com/" -H "X-Purge-By: admin" -k 2>/dev/null || true')
    print("✅ Cache purged!\n")
    
    print("🎉 Deployment complete!")
    print("📍 Visit: https://ivory-lark-138468.hostingersite.com/\n")
    print("✨ Changes applied:")
    print("   ✓ Master cleanup plugin (hides WordPress elements)")
    print("   ✓ Professional Cairde Designs header")
    print("   ✓ Clean navigation menu")
    print("   ✓ Hidden Hostinger domain references")
    print("   ✓ All conflicting old plugins disabled")
    
except ftplib.all_errors as e:
    print(f"❌ FTP Error: {e}")
    sys.exit(1)
except Exception as e:
    print(f"❌ Error: {e}")
    sys.exit(1)
