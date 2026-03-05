#!/usr/bin/env python3
import ftplib
import sys

ftp_host = "185.164.108.209"
ftp_user = "u300002008.ivory-lark-138468.hostingersite.com"
ftp_pass = "VinU9Y8kmNr!2l*0"
ftp_port = 21

uploads = [
    ("mu-luxury-motion.php", "/public_html/wp-content/mu-plugins", "cairde-luxury-motion.php"),
    ("cairde-header-plugin/cairde-header.php", "/public_html/wp-content/plugins/cairde-header", "cairde-header.php"),
]

print("Connecting to FTP server...")
try:
    ftp = ftplib.FTP()
    ftp.connect(ftp_host, ftp_port, timeout=10)
    ftp.login(ftp_user, ftp_pass)
    print("Connected!")

    for local_file, remote_dir, remote_file in uploads:
        try:
            ftp.cwd(remote_dir)
        except Exception:
            ftp.mkd(remote_dir)
            ftp.cwd(remote_dir)

        print(f"Uploading {local_file} -> {remote_dir}/{remote_file}")
        with open(local_file, "rb") as f:
            ftp.storbinary(f"STOR {remote_file}", f)

    ftp.quit()
    print("SUCCESS! Luxury motion + header upgrade deployed.")
except Exception as e:
    print(f"Error: {e}")
    sys.exit(1)
