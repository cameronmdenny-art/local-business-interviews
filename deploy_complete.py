from ftplib import FTP

host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "SbDX0coH0m4A0#cP"

files_to_upload = [
    ('local-business-interviews.php', '/public_html/wp-content/plugins/local-business-interviews/local-business-interviews.php'),
    ('includes/header-injector.php', '/public_html/wp-content/plugins/local-business-interviews/includes/header-injector.php'),
]

print("🚀 Deploying Local Legend Stories branding...")

ftp = FTP()
ftp.connect(host, timeout=10)
ftp.login(user, password)
print("✅ Connected to FTP")

for local_file, remote_file in files_to_upload:
    try:
        # Get directory and filename
        remote_dir = '/'.join(remote_file.split('/')[:-1])
        remote_filename = remote_file.split('/')[-1]
        
        # Change to directory
        ftp.cwd(remote_dir)
        
        # Upload
        with open(local_file, 'rb') as f:
            ftp.storbinary(f'STOR {remote_filename}', f)
        print(f"✅ Uploaded: {local_file}")
    except Exception as e:
        print(f"❌ Failed {local_file}: {e}")

ftp.quit()
print("\n🎉 Deployment complete!")
print("\n📋 Uploaded:")
print("  - Main plugin (v1.0.4)")
print("  - Local Legend Stories header & footer")
print("\n🔄 Now accessing site to trigger cache clear...")
