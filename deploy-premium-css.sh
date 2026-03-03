#!/bin/bash

# Deploy premium CSS

export FTP_PASSWORD="VinU9Y8kmNr!2l*0"

echo "🎨 Deploying premium Apple-inspired CSS..."

curl -s -T "./assets/css/frontend.css" \
     -u "u300002008.ivory-lark-138468.hostingersite.com:$FTP_PASSWORD" \
     "ftp://185.164.108.209/public_html/wp-content/plugins/local-business-interviews/assets/css/frontend.css"

echo "✅ Premium CSS deployed!"
echo ""
echo "🌐 Clear your browser cache and visit:"
echo "   👉 https://ivory-lark-138468.hostingersite.com/recommend/"
echo ""
echo "You'll see:"
echo "   ✨ Smooth floating animations"
echo "   🎯 Apple-style focus states"
echo "   💫 Subtle hover effects"
echo "   🌊 Fluid transitions"
echo "   📱 Responsive design"
