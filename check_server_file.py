from ftplib import FTP
from io import BytesIO

host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "SbDX0coH0m4A0#cP"

print("Checking server file...")

try:
    ftp = FTP()
    ftp.connect(host, timeout=10)
    ftp.login(user, password)
    
    ftp.cwd("/public_html/wp-content/plugins/local-business-interviews/includes")
    files = ftp.nlst()
    
    if 'header-injector.php' in files:
        print("✅ header-injector.php EXISTS on server")
        
        size = ftp.size('header-injector.php')
        print(f"File size: {size} bytes")
        
        buffer = BytesIO()
        ftp.retrbinary('RETR header-injector.php', buffer.write)
        content = buffer.getvalue().decode('utf-8')
        
        print("\nFirst 15 lines:")
        for i, line in enumerate(content.split('\n')[:15], 1):
            print(f"{i}: {line}")
        
        if 'Local Legend Stories' in content:
            print("\n✅ File contains 'Local Legend Stories'")
        else:
            print("\n❌ File does NOT contain 'Local Legend Stories'")
            
        if 'LBI_Header_Injector' in content:
            print("✅ Contains LBI_Header_Injector class")
        
        if 'local-legend-header' in content:
            print("✅ Contains local-legend-header styles")
        else:
            print("❌ Missing local-legend-header styles")
    else:
        print("❌ header-injector.php NOT FOUND")
        print("Files:", files)
    
    ftp.quit()
    
except Exception as e:
    print(f"❌ ERROR: {e}")
