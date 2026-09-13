<?php
declare(strict_types=1);

function load_env(string $file): array
{
    if(!is_file($file)){
        return [];
    }
    $values = [];
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        $value = trim($value, "\"'");
        $values[$key] = $value;
    }
    return $values;
}

$env = load_env(__DIR__ . DIRECTORY_SEPARATOR . '.env');

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? $env['db_hostPORT'] ?? '3306';
$database = $env['DB_NAME'] ?? 'db_rumah_sakit';
$username = $env['DB_USER'] ?? 'root';
$password = $env['DB_PASS'] ?? '';

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $host,
    $port,
    $database
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $exeption){
    http_response_code(500);
    exit('koneksi database gagal, belet sia teh kehed');
}
?>
