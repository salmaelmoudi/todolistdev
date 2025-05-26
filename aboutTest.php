<?php
// test_about.php

// 1. Request the about.php page
$aboutPage = file_get_contents('http://localhost/php-docs-hello-world-master/about.php');

// 2. Check if the response contains expected elements
if (strpos($aboutPage, '<h1>À propos</h1>') !== false &&
    strpos($aboutPage, 'Ce site est un exemple simple en PHP') !== false) {
    echo "Test passed: Content found.\n";
} else {
    echo "Test failed: Content missing.\n";
}
?>