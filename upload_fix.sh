#!/bin/bash
# Upload Local Legend Stories header via FTP - properly escaped

HOST="185.164.108.209"
USER="u300002008.ivory-lark-138468.hostingersite.com"
PASS='VinU9Y8kmNr!2l*0'

echo "Uploading Local Legend Stories header-injector.php..."

ftp -n <<EOF
open $HOST
user $USER $PASS
cd /public_html/wp-content/plugins/local-business-interviews/includes
put includes/header-injector.php header-injector.php
bye
EOF

echo "✅ Upload complete!"
echo "Now clear your site cache at: https://ivory-lark-138468.hostingersite.com/wp-admin/"
