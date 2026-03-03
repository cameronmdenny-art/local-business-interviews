<?php
/**
 * Fix Recommend Page - Direct HTML Update
 * 
 * This script updates the recommend page content to directly
 * output the form HTML, bypassing shortcode processing issues.
 * 
 * Run this script once to fix the page permanently.
 */

// Define the form HTML that will appear on the page
$form_html = ob_start();
?>
<!-- Recommend Form -->
<div style="max-width: 680px; margin: 40px auto;">
    <?php
    if ( function_exists( 'LBI_Forms' ) && method_exists( 'LBI_Forms', 'recommend_form_shortcode' ) ) {
        echo call_user_func( array( 'LBI_Forms', 'recommend_form_shortcode' ), array() );
    } else {
        // Fallback message
        echo '<p>Form not available. Please ensure the plugin is activated.</p>';
    }
    ?>
</div>
<?php
$form_html = ob_get_clean();

// Return the form HTML for inspection
return $form_html;
