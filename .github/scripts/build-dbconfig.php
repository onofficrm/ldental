<?php
$host = trim((string) getenv('DB_HOST'));
$user = trim((string) getenv('DB_USER'));
$pass = trim((string) getenv('DB_PASSWORD'));
$db = trim((string) getenv('DB_NAME'));
$key = trim((string) getenv('DB_TOKEN_KEY'));
if ($key === '') {
    $key = bin2hex(random_bytes(16));
}

if ($host === '' || $user === '' || $pass === '' || $db === '') {
    fwrite(STDERR, "Missing DB_* environment variables.\n");
    exit(1);
}

$sample = file_get_contents(__DIR__ . '/../../public_html/data/dbconfig.php.sample');
if ($sample === false) {
    fwrite(STDERR, "dbconfig.php.sample not found.\n");
    exit(1);
}

$replacements = array(
    'G5_MYSQL_HOST' => $host,
    'G5_MYSQL_USER' => $user,
    'G5_MYSQL_PASSWORD' => $pass,
    'G5_MYSQL_DB' => $db,
    'G5_TOKEN_ENCRYPTION_KEY' => $key,
);

$out = $sample;
foreach ($replacements as $const => $value) {
    $count = 0;
    $out = preg_replace(
        "/define\\('" . preg_quote($const, '/') . "',\\s*'[^']*'\\);/",
        'define(\'' . $const . '\', ' . var_export($value, true) . ');',
        $out,
        1,
        $count
    );
    if ($count !== 1) {
        fwrite(STDERR, "Failed to replace $const\n");
        exit(1);
    }
}

if (strpos($out, '여기에_DB비밀번호') !== false) {
    fwrite(STDERR, "Password placeholder was not replaced.\n");
    exit(1);
}

fwrite(STDERR, 'dbconfig password_len=' . strlen($pass) . "\n");
file_put_contents(getcwd() . '/dbconfig.php', $out);
echo "dbconfig.php generated.\n";
