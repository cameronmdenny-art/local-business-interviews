from ftplib import FTP
import os

# NEW FTP credentials
host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "SbDX0coH0m4A0#cP"

local_file = "includes/header-injector.php"
remote_path = "/public_html/wp-content/plugins/local-business-interviews/includes/header-injector.php"

print(f"📤 Uploading Local Legend Stories header with NEW password...")
print(f"From: {local_file}")
print(f"To: {host}{remote_path}")

try:
    # Connect
    ftp = FTP()
    ftp.set_debuglevel(0)
    ftp.connect(host, timeout=10)
    print("✅ Connected to FTP server")
    
    # Login with new password
    response = ftp.login(user, password)
    print(f"✅ Logged in successfully: {response}")
    
    # Navigate to directory
    ftp.cwd("/public_html/wp-content/plugins/local-business-interviews/includes")
    print(f"✅ Changed to includes directory")
    
    # Upload file in binary mode
    with open(local_file, 'rb') as file:
        response = ftp.storbinary(f'STOR header-injector.php', file)
        print(f"✅ Upload complete: {response}")
    
    # Verify file exists
    files = ftp.nlst()
    if 'header-injector.php' in files:
        print("✅ Verified: header-injector.php is on server")
    
    ftp.quit()
    print("\n🎉 SUCCESS! Local Legend Stories header is now deployed!")
    print("\n📋 What was uploaded:")
    print("   - Custom header with 'Local Legend Stories' branding")
    print("   - Professional footer")
    print("   - Hides old 'Cairde Designs' elements")
    print("\n🔄 NEXT: Clear your site cache to see changes")
    
except Exception as e:
    print(f"\n❌ ERROR: {e}")
    import traceback
    traceback.print_exc()
