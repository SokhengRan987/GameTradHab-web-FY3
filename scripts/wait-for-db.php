<?php
// Short script to wait for database availability before running migrations.
$timeout = 120; // seconds
$start = time();
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: (getenv('DB_CONNECTION') === 'mysql' ? '3306' : '5432');
$database = getenv('DB_DATABASE') ?: '';
$username = getenv('DB_USERNAME') ?: '';
$password = getenv('DB_PASSWORD') ?: '';
$driver = getenv('DB_CONNECTION') ?: 'pgsql';

function tryConnect($driver, $host, $port, $database, $username, $password)
{
    try {
        if ($driver === 'mysql' || $driver === 'mariadb') {
            $dsn = "mysql:host={$host};port={$port};dbname={$database}";
        } else {
            $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
        }
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ];
        $pdo = new PDO($dsn, $username, $password, $opts);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

echo "Waiting for database ({$driver}) at {$host}:{$port}...\n";
while (true) {
    if (tryConnect($driver, $host, $port, $database, $username, $password)) {
        echo "Database is available.\n";
        exit(0);
    }
    if ((time() - $start) > $timeout) {
        fwrite(STDERR, "Timed out waiting for database after {$timeout} seconds.\n");
        exit(1);
    }
    sleep(1);
}
