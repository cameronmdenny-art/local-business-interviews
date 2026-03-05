#!/usr/bin/env python3
"""
Deploy Cairde Color Theme MU Plugin
"""

import ftplib

# FTP Configuration
FTP_HOST = '185.164.108.209'
FTP_USER = 'u300002008.ivory-lark-138468.hostingersite.com'
FTP_PASS = 'VinU9Y8kmNr!2l*0'
FTP_PORT = 21

def upload_file(ftp, local_path, remote_path):
    """Upload a single file to FTP"""
    try:
        with open(local_path, 'rb') as f:
            ftp.storbinary(f'STOR {remote_path}', f)
        print(f"✅ Uploaded: {remote_path}")
        return True
    except Exception as e:
        print(f"❌ Failed to upload {remote_path}: {e}")
        return False

def main():
    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=10)
        ftp.login(FTP_USER, FTP_PASS)
        print(f"📡 Connected to {FTP_HOST}")

        # Navigate to mu-plugins directory
        try:
            ftp.cwd('/public_html/wp-content/mu-plugins')
            print("📁 Found mu-plugins directory")
        except ftplib.all_errors:
            ftp.cwd('/public_html/wp-content')
            ftp.mkd('mu-plugins')
            ftp.cwd('mu-plugins')
            print("📁 Created mu-plugins directory")

        # Upload color theme MU plugin
        upload_file(ftp, 'mu-cairde-colors.php', 'cairde-colors.php')

        ftp.quit()
        print("\n✅ SUCCESS! Cairde color theme deployed.\n")
        return True

    except Exception as e:
        print(f"❌ FTP Error: {e}")
        return False

if __name__ == '__main__':
    main()
