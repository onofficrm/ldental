<?php
include_once './_common.php';

header('Content-Type: application/xml; charset=utf-8');

$pages = array(
    G5_URL . '/',
    ld_page_url('intro'),
    ld_page_url('treatment'),
    ld_page_url('hours'),
    ld_page_url('location'),
    ld_page_url('consult'),
    get_pretty_url('content', 'privacy'),
    get_pretty_url('content', 'provision'),
);

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
foreach ($pages as $loc) {
    echo '  <url><loc>' . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . '</loc></url>' . PHP_EOL;
}
echo '</urlset>';
