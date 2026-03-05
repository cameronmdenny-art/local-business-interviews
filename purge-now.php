<?php
// Purge LiteSpeed Cache
header('X-LiteSpeed-Purge: *');
echo "Cache purged!\n";
sleep(1);
@unlink(__FILE__);
?>