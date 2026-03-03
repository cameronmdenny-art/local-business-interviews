#!/bin/bash

# Delete the existing plugin folder so WordPress can install from ZIP

FTP_PASSWORD="VinU9Y8kmNr!2l*0"
FTP_USER="u300002008.ivory-lark-138468.hostingersite.com"
FTP_HOST="185.164.108.209"
PLUGIN_DIR="/public_html/wp-content/plugins/local-business-interviews"

echo "🗑️  Deleting existing plugin folder on server..."

# Function to recursively delete a directory via FTP
delete_ftp_dir() {
    local dir="$1"
    
    # List directory contents
    FILES=$(curl -s -u "$FTP_USER:$FTP_PASSWORD" "ftp://$FTP_HOST$dir/" | awk '{print $NF}')
    
    for file in $FILES; do
        if [[ "$file" == "." || "$file" == ".." ]]; then
            continue
        fi
        
        # Try to delete as file first
        curl -s --quote "DELE $dir/$file" -u "$FTP_USER:$FTP_PASSWORD" "ftp://$FTP_HOST/" > /dev/null 2>&1
        
        # If that failed, it might be a directory
        if [ $? -ne 0 ]; then
            delete_ftp_dir "$dir/$file"
            curl -s --quote "RMD $dir/$file" -u "$FTP_USER:$FTP_PASSWORD" "ftp://$FTP_HOST/" > /dev/null 2>&1
        fi
    done
}

# Delete all contents first
echo "📂 Removing plugin files..."
delete_ftp_dir "$PLUGIN_DIR"

# Delete the main directory
curl -s --quote "RMD $PLUGIN_DIR" -u "$FTP_USER:$FTP_PASSWORD" "ftp://$FTP_HOST/"

echo ""
echo "✅ Plugin folder deleted from server!"
echo ""
echo "👉 Now click 'Go to Plugin Installer' in WordPress"
echo "👉 Click 'Upload Plugin' again"
echo "👉 Select local-business-interviews.zip"
echo "👉 Click 'Install Now'"
echo "👉 Then click 'Activate Plugin'"
