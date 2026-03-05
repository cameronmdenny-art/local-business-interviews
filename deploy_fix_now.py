from ftplib import FTP

host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "SbDX0coH0m4A0#cP"

print("Deploying FIX for blank page...")

ftp = FTP()
ftp.connect(host, timeout=10)
ftp.login(user, password)
print("Connected")

# Upload header-injector.php
ftp.cwd("/public_html/wp-content/plugins/local-business-interviews/includes")
with open("includes/header-injector.php", "rb") as f:
    ftp.storbinary("STOR header-injector.php", f)
print("Uploaded header-injector.php")

# Upload main plugin
ftp.cwd("/public_html/wp-content/plugins/local-business-interviews")
with open("local-business-interviews.php", "rb") as f:
    ftp.storbinary("STOR local-business-interviews.php", f)
print("Uploaded local-business-interviews.php")

ftp.quit()
print("\nDeploy complete!")
