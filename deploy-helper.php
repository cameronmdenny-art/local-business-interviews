<?php
/**
 * Cairde Deployment Helper
 * 
 * Use this to verify the site setup and manually copy files if needed
 * Run: php deploy-helper.php
 */

echo "═══════════════════════════════════════════════════════════════\n";
echo "  CAIRDE DESIGNS - SITE FIX DEPLOYMENT HELPER\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Check if we're in the right directory
$required_files = [
    'mu-master-cleanup.php',
    'mu-cairde-header-complete.php',
];

echo "📋 Checking required files...\n";
$all_exist = true;

foreach ($required_files as $file) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path);
    $status = $exists ? '✓' : '✗';
    echo "  $status $file (" . ($exists ? 'OK' : 'MISSING') . ")\n";
    if (!$exists) $all_exist = false;
}

if (!$all_exist) {
    echo "\n❌ Some required files are missing!\n";
    exit(1);
}

echo "\n✅ All files present!\n\n";

// Directory structure check
echo "📁 Directory structure:\n";
if (is_dir(__DIR__)) {
    echo "  Working directory: " . __DIR__ . "\n";
}

// Check if WordPress is available
if (function_exists('add_action')) {
    echo "\n🔌 WordPress environment detected!\n";
    echo "  WordPress is loaded and mu-plugins should auto-activate.\n";
} else {
    echo "\n⚠️  WordPress not currently loaded (normal for CLI).\n";
}

// Information about deployment
echo "\n" . str_repeat('═', 63) . "\n";
echo "DEPLOYMENT INFORMATION\n";
echo str_repeat('═', 63) . "\n\n";

echo "These files need to be uploaded to your server:\n";
echo "  Remote Location: /public_html/\n\n";

echo "File details:\n";
foreach ($required_files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        $lines = count(file($path));
        echo "  • $file\n";
        echo "    Size: " . number_format($size) . " bytes\n";
        echo "    Lines: $lines\n\n";
    }
}

// Generate FTP commands
echo "📝 FTP Commands (for manual upload):\n";
echo "───────────────────────────────────\n";
echo "cd /public_html/\n";
echo "put mu-master-cleanup.php\n";
echo "put mu-cairde-header-complete.php\n";
echo "bye\n\n";

// Generate curl commands
echo "📝 Curl Commands (alternative):\n";
echo "───────────────────────────────\n";
echo "curl -T mu-master-cleanup.php -u username:password ftp://your-ftp-host/public_html/\n";
echo "curl -T mu-cairde-header-complete.php -u username:password ftp://your-ftp-host/public_html/\n\n";

// Summary
echo "═══════════════════════════════════════════════════════════════\n";
echo "NEXT STEPS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "1. Upload both files to your server's /public_html/ directory\n";
echo "2. Clear your site's cache (LiteSpeed, WordPress, etc.)\n";
echo "3. Visit your site and verify the fixes:\n";
echo "   ✓ Professional Cairde Designs header is visible\n";
echo "   ✓ No Hostinger domain URL visible\n";
echo "   ✓ Only one clean navigation menu shows\n";
echo "   ✓ No WordPress theme elements visible\n\n";

echo "Information:\n";
echo "  • These are WordPress mu-plugins (must-use plugins)\n";
echo "  • They auto-activate when placed in /public_html/\n";
echo "  • No WordPress admin panel action needed\n";
echo "  • Safe to activate - only hides and replaces theme elements\n\n";

echo "✅ Files are ready for deployment!\n\n";

?>
