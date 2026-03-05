#!/usr/bin/env python3
import ftplib
import sys

ftp_host = "185.164.108.209"
ftp_user = "u300002008.ivory-lark-138468.hostingersite.com"
ftp_pass = "VinU9Y8kmNr!2l*0"
ftp_port = 21

local_file = "cairde-header-plugin/cairde-header.php"

print("Connecting to FTP server...")
try:
    ftp = ftplib.FTP()
    ftp.connect(ftp_host, ftp_port, timeout=10)
    ftp.login(ftp_user, ftp_pass)
    print("Connected!")
    
    print("Creating directory...")
    try:
        ftp.mkd("/public_html/wp-content/plugins/cairde-header")
        print("Created directory")
    except:
        print("Directory exists")
    
    ftp.cwd("/public_html/wp-content/plugins/cairde-header")
    
    print(f"Uploading {local_file}...")
    with open(local_file, "rb") as f:
        ftp.storbinary(f"STOR cairde-header.php", f)
    print("Upload complete!")
    
    files = ftp.nlst()
    if "cairde-header.php" in files:
        print("File verified on server!")
    
    ftp.quit()
    print("\nSUCCESS! File uploaded!")
    print("Go to: https://ivory-lark-138468.hostingersite.com/wp-admin/plugins.php")
    print("Find 'Cairde Header Injector' and click ACTIVATE")
    
except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
