#!/usr/bin/env python3
import ftplib
import sys

ftp_host = "185.164.108.209"
ftp_user = "u300002008.ivory-lark-138468.hostingersite.com"
ftp_pass = "VinU9Y8kmNr!2l*0"
ftp_port = 21

local_file = "mu-mobile-optimizer.php"
remote_dir = "/public_html/wp-content/mu-plugins"
remote_file = "cairde-mobile-optimizer.php"

print("Connecting to FTP server...")
try:
    ftp = ftplib.FTP()
    ftp.connect(ftp_host, ftp_port, timeout=10)
    ftp.login(ftp_user, ftp_pass)
    print("Connected!")

    try:
        ftp.cwd(remote_dir)
    except Exception:
        ftp.mkd(remote_dir)
        ftp.cwd(remote_dir)

    print("Uploading mobile optimizer...")
    with open(local_file, "rb") as f:
        ftp.storbinary(f"STOR {remote_file}", f)

    files = ftp.nlst()
    if remote_file in files:
        print("File verified on server!")

    ftp.quit()
    print("SUCCESS! MU mobile optimizer deployed.")
except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
