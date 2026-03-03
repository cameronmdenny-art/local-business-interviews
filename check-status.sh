#!/bin/bash

# Check if plugin is active via WP-CLI or curl

echo "Checking plugin status..."

# Try to get the recommend page and see what's rendering
curl -s "https://ivory-lark-138468.hostingersite.com/recommend/" | grep -A 5 -B 5 "lbi_recommend_form\|parse error" | head -15

echo ""
echo "---"
echo "Checking if class exists by looking at form field mentions..."
curl -s "https://ivory-lark-138468.hostingersite.com/recommend/" | grep -i "fieldset\|business_name\|lbi-form" | head -5
