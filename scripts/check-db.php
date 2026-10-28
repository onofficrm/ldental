<?php
define('_GNUBOARD_', true);
require dirname(__DIR__) . '/public_html/data/dbconfig.php';

mysqli_report(MYSQLI_REPORT_OFF);

$hosts = array_unique([G5_MYSQL_HOST, 'localhost', '127.0.0.1']);

foreach ($hosts as $host) {
    echo "try {$host}:3306 ... ";
    $m = mysqli_init();
    mysqli_options($m, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
        mysqli_options($m, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    }
    $ok = @mysqli_real_connect(
        $m,
        $host,
        G5_MYSQL_USER,
        G5_MYSQL_PASSWORD,
        G5_MYSQL_DB,
        3306,
        null,
        MYSQLI_CLIENT_SSL
    );
    if (!$ok) {
        $ok = @mysqli_real_connect($m, $host, G5_MYSQL_USER, G5_MYSQL_PASSWORD, G5_MYSQL_DB, 3306);
    }
    if (!$ok) {
        echo 'FAIL: [' . mysqli_connect_errno() . '] ' . mysqli_connect_error() . PHP_EOL;
        continue;
    }
    echo 'OK' . PHP_EOL;
    $r = mysqli_query($m, "SHOW TABLES LIKE 'g5_config'");
    echo ($r && mysqli_num_rows($r)) ? "g5_config: yes\n" : "g5_config: no (DB empty — install/import needed)\n";
    mysqli_close($m);
    exit(0);
}
exit(1);
