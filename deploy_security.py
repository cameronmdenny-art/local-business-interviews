#!/usr/bin/env python3
import ftplib
import sys
import os

ftp_host = "185.164.108.209"
ftp_user = "u300002008.ivory-lark-138468.hostingersite.com"
ftp_pass = "VinU9Y8kmNr!2l*0"
ftp_port = 21

local_file = "secure-htaccess"
remote_file = ".htaccess"

print("=" * 60)
print("DEPLOYING SECURITY .HTACCESS FILE")
print("=" * 60)

try:
    print("\n1. Connecting to FTP server...")
    ftp = ftplib.FTP()
    ftp.connect(ftp_host, ftp_port, timeout=10)
    ftp.login(ftp_user, ftp_pass)
    print("   ✅ Connected!")
    
    print("\n2. Navigating to /public_html/...")
    ftp.cwd("/public_html")
    
    print("\n3. Uploading .htaccess file...")
    with open(local_file, "rb") as f:
        ftp.storbinary(f"STOR {remote_file}", f)
    print("   ✅ Upload complete!")
    
    print("\n4. Verifying file...")
    files = ftp.nlst()
    if ".htaccess" in files:
        print("   ✅ File verified on server!")
    else:
        print("   ❌ ERROR: File not found on server!")
        sys.exit(1)
    
    ftp.quit()
    
    print("\n" + "=" * 60)
    print("✅ SUCCESS!")
    print("=" * 60)
    print("\n📌 SECURITY FEATURES DEPLOYED:")
    print("   • PHP execution blocked in /wp-content/uploads/")
    print("   • Directory listing disabled")
    print("   • Sensitive files protected (.htaccess, wp-config.php)")
    print("   • XML-RPC disabled (prevents DDoS)")
    print("   • Security headers enabled")
    print("   • Gzip compression enabled")
    print("   • LiteSpeed Cache optimized")
    
    print("\n⚠️  NEXT STEPS:")
    print("   1. Go to Hostinger File Manager")
    print("   2. Find /wp-config.php in /public_html/")
    print("   3. Add security hardening code (see SECURITY-HARDENING-GUIDE.md)")
    print("   4. Change database table prefix from wp_ to cairde_")
    print("   5. Install Wordfence Security plugin")
    print("   6. Set up Cloudflare for additional protection")
    
    print("\n📖 Full guide: SECURITY-HARDENING-GUIDE.md")
    
except Exception as e:
    print(f"\n❌ Error: {e}")
    sys.exit(1)
