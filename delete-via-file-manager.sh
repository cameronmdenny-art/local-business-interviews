#!/bin/bash

# Use SFTP to delete the plugin folder

FTP_PASSWORD="VinU9Y8kmNr!2l*0"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_HOST="185.164.108.209"

echo "🗑️ Attempting to delete plugin folder via SFTP..."

# Create SFTP batch commands
cat > /tmp/sftp_delete_commands.txt << 'EOF'
cd public_html/wp-content/plugins
rm -r local-business-interviews
bye
EOF

# Execute via SFTP
sshpass -p "$FTP_PASSWORD" sftp -oBatchMode=no -b /tmp/sftp_delete_commands.txt "$FTP_USER@$FTP_HOST" 2>&1

rm /tmp/sftp_delete_commands.txt

echo ""
echo "If you see permission errors, use Hostinger File Manager:"
echo "1. Login to Hostinger → File Manager"
echo "2. Go to public_html/wp-content/plugins/"
echo "3. Delete 'local-business-interviews' folder"
echo "4. Then upload ZIP in WordPress admin"
