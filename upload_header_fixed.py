from ftplib import FTP
import os

# FTP credentials - using raw string to avoid escape issues
host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = r"VinU9Y8kmNr!2l*0"  # Raw string preserves special chars

local_file = "includes/header-injector.php"
remote_path = "/public_html/wp-content/plugins/local-business-interviews/includes/header-injector.php"

print(f"📤 Uploading Local Legend Stories header...")
print(f"From: {local_file}")
print(f"To: {host}{remote_path}")

try:
    # Connect
    ftp = FTP()
    ftp.set_debuglevel(0)  # No debug output
    ftp.connect(host, timeout=10)
    print("✅ Connected to FTP server")
    
    # Login with properly escaped password
    response = ftp.login(user, password)
    print(f"✅ Logged in: {response}")
    
    # Navigate to directory
    ftp.cwd("/public_html/wp-content/plugins/local-business-interviews/includes")
    print(f"✅ Changed to directory")
    
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
    print("\n🔄 NEXT STEP: Clear your site cache")
    print("Visit: https://ivory-lark-138468.hostingersite.com/wp-admin/")
    print("Look for LiteSpeed Cache or similar plugin and click 'Purge All'")
    
except Exception as e:
    print(f"\n❌ ERROR: {e}")
    import traceback
    traceback.print_exc()
