#!/bin/bash

# Upload diagnostic file

export FTP_PASSWORD="VinU9Y8kmNr!2l*0"

curl --ftp-create-dirs \
     -T "./diagnostic.php" \
     -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
     "ftp://185.164.108.209/public_html/wp-content/plugins/local-business-interviews/diagnostic.php"

echo "✅ Uploaded: https://ivory-lark-138468.hostingersite.com/wp-content/plugins/local-business-interviews/diagnostic.php"
