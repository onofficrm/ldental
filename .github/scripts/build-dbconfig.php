<?php
$host = getenv('DB_HOST') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASSWORD') ?: '';
$db = getenv('DB_NAME') ?: '';
$key = getenv('DB_TOKEN_KEY') ?: bin2hex(random_bytes(16));

if ($host === '' || $user === '' || $pass === '' || $db === '') {
    fwrite(STDERR, "Missing DB_* environment variables.\n");
    exit(1);
}

$sample = file_get_contents(__DIR__ . '/../../public_html/data/dbconfig.php.sample');
if ($sample === false) {
    fwrite(STDERR, "dbconfig.php.sample not found.\n");
    exit(1);
}

$out = str_replace(
    [
        "define('G5_MYSQL_HOST', 'wuk2002.sldb.iwinv.net');",
        "define('G5_MYSQL_USER', 'ldental2');",
        "define('G5_MYSQL_PASSWORD', '여기에_DB비밀번호');",
        "define('G5_MYSQL_DB', 'ldental2');",
        "define('G5_TOKEN_ENCRYPTION_KEY', 'b663f24d64582d921335c4245d39cff0');",
    ],
    [
        "define('G5_MYSQL_HOST', " . var_export($host, true) . ');',
        "define('G5_MYSQL_USER', " . var_export($user, true) . ');',
        "define('G5_MYSQL_PASSWORD', " . var_export($pass, true) . ');',
        "define('G5_MYSQL_DB', " . var_export($db, true) . ');',
        "define('G5_TOKEN_ENCRYPTION_KEY', " . var_export($key, true) . ');',
    ],
    $sample
);

file_put_contents(getcwd() . '/dbconfig.php', $out);
echo "dbconfig.php generated.\n";
