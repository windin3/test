<?php
// 404.php - Redirects users to the admin page when they hit a non-existent URL
header("HTTP/1.1 301 Moved Permanently");
//header("Location: /admin/index.php");
exit();
?>