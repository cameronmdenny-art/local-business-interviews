<?php
// Onefile solution: put this in /public_html and access like https://site.com/fix-pages-NOW.php
// This directly updates the WordPress wp_posts table with the shortcodes

$db_name = 'ivory_lark_db';
$db_user = 'ivory_lark_user';
$db_pass = 'Rin92NSD92mNs9N2';
$db_host = 'localhost';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $updates = array(
        13  => '[lbi_directory_form]',
        46  => '[lbi_recommend_form]',
        10  => '[lbi_interview_form]',
        21  => '<p>Welcome to our Local Business Directory</p>',
    );
    
    echo "=== DIRECT DATABASE UPDATE ===\n\n";
    
    foreach ($updates as $id => $content) {
        $stmt = $pdo->prepare("UPDATE wp_posts SET post_content = ? WHERE ID = ?");
        if ($stmt->execute(array($content, $id))) {
            echo "✓ Page $id updated\n";
        }
    }
    
    echo "\n✅ All pages updated successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
