from ftplib import FTP
import sys

host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "VinU9Y8kmNr!2l*0"

print(f"Testing FTP: {host}")
print(f"User: {user}")

try:
    ftp = FTP(host, timeout=15)
    print("✅ Connected")
    
    ftp.login(user, password)
    print("✅ Logged in")
    
    print("Directory:", ftp.pwd())
    
    ftp.cwd('/public_html')
    print("✅ In /public_html")
    
    # Upload the header file
    print("\nUploading header-injector.php...")
    with open('includes/header-injector.php', 'rb') as f:
        ftp.storbinary('STOR wp-content/plugins/local-business-interviews/includes/header-injector.php', f)
    print("✅ Uploaded!")
    
    ftp.quit()
    print("\n✅ SUCCESS - File uploaded!")
    
except Exception as e:
    print(f"\n❌ ERROR: {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)
