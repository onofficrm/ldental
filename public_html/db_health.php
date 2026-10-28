<?php
header('Content-Type: text/plain; charset=utf-8');

$file = __DIR__ . '/data/dbconfig.php';
echo 'dbconfig_exists=' . (is_file($file) ? '1' : '0') . PHP_EOL;
echo 'dbconfig_readable=' . (is_readable($file) ? '1' : '0') . PHP_EOL;
echo 'dbconfig_size=' . (is_file($file) ? filesize($file) : 0) . PHP_EOL;

if (!is_file($file)) {
    exit;
}

define('_GNUBOARD_', true);
include $file;

echo 'host_defined=' . (defined('G5_MYSQL_HOST') ? '1' : '0') . PHP_EOL;
echo 'user_defined=' . (defined('G5_MYSQL_USER') ? '1' : '0') . PHP_EOL;
echo 'pass_defined=' . (defined('G5_MYSQL_PASSWORD') ? '1' : '0') . PHP_EOL;
echo 'db_defined=' . (defined('G5_MYSQL_DB') ? '1' : '0') . PHP_EOL;
echo 'shop=' . ((defined('G5_USE_SHOP') && G5_USE_SHOP) ? 'on' : 'off') . PHP_EOL;

if (!defined('G5_MYSQL_HOST') || !defined('G5_MYSQL_USER') || !defined('G5_MYSQL_PASSWORD') || !defined('G5_MYSQL_DB')) {
    exit;
}

mysqli_report(MYSQLI_REPORT_OFF);
$m = @mysqli_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD, G5_MYSQL_DB);
if ($m) {
    echo 'connect=ok' . PHP_EOL;
    mysqli_close($m);
    exit;
}

echo 'connect=fail errno=' . mysqli_connect_errno() . PHP_EOL;

$m = @mysqli_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD);
if (!$m) {
    echo 'server_connect=fail errno=' . mysqli_connect_errno() . PHP_EOL;
    exit;
}

echo 'server_connect=ok' . PHP_EOL;
$r = @mysqli_query($m, 'SHOW DATABASES');
$names = array();
if ($r) {
    while ($row = mysqli_fetch_row($r)) {
        if (!in_array($row[0], array('information_schema', 'performance_schema', 'mysql', 'sys'), true)) {
            $names[] = $row[0];
        }
    }
}
echo 'databases=' . implode(',', $names) . PHP_EOL;
mysqli_close($m);
