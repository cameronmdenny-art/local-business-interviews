from ftplib import FTP

# Clear cache by uploading a cache-clearing PHP script
host = "185.164.108.209"
user = "u300002008.ivory-lark-138468.hostingersite.com"
password = "SbDX0coH0m4A0#cP"

cache_clear_code = """<?php
// Temporary cache clear script
if (file_exists('./wp-load.php')) {
    require_once './wp-load.php';
}

// Clear LiteSpeed cache if available
if (class_exists('LiteSpeed_Cache_API')) {
    LiteSpeed_Cache_API::purge_all();
    echo "✅ LiteSpeed cache cleared<br>";
}

// Clear WordPress cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "✅ WordPress cache cleared<br>";
}

// Clear object cache
wp_cache_delete('alloptions', 'options');
echo "✅ Options cache cleared<br>";

echo "<br><strong>Cache cleared! Redirecting to homepage...</strong>";
echo "<script>setTimeout(() => window.location.href = '/', 2000);</script>";
?>"""

print("📤 Uploading cache clear script...")

try:
    ftp = FTP()
    ftp.connect(host, timeout=10)
    ftp.login(user, password)
    print("✅ Connected")
    
    ftp.cwd("/public_html")
    
    # Write cache clear script
    from io import BytesIO
    ftp.storbinary('STOR clear-cache-now.php', BytesIO(cache_clear_code.encode('utf-8')))
    print("✅ Uploaded cache clear script")
    
    ftp.quit()
    print("\n🔗 Access this URL to clear cache:")
    print("https://ivory-lark-138468.hostingersite.com/clear-cache-now.php")
    
except Exception as e:
    print(f"❌ ERROR: {e}")
