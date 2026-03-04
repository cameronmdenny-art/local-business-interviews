from ftplib import FTP
import sys

# FTP settings
host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "VinU9Y8kmNr!2l*0"

try:
    print(f"Connecting to {host}...")
    ftp = FTP(host, timeout=30)
    print("Connected. Logging in...")
    ftp.login(user, password)
    print("Login successful!")
    print("Current directory:", ftp.pwd())
    
    #Change to public_html
    try:
        ftp.cwd('/public_html')
        print("Changed to /public_html")
    except Exception as e:
        print(f"/public_html not found: {e}")
    
    # Upload the installer file
    print("\nUploading INSTALL_LOCAL_LEGEND.php...")
    with open('INSTALL_LOCAL_LEGEND.php', 'rb') as f:
        ftp.storbinary('STOR INSTALL_LOCAL_LEGEND.php', f)
    print("✅ Upload successful!")
    
    ftp.quit()
    print("\n✅ Access the installer at:")
    print("https://ivory-lark-138468.hostingersite.com/INSTALL_LOCAL_LEGEND.php?key=deploy-local-legend-now-2026")
    
except Exception as e:
    print(f"❌ Error: {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)
