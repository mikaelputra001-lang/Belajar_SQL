<?php
declare(strict_types=1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Token is invalid! Please try again.');
    }
}

function set_flash(string $massage, string $type = 'success'): void
{
    $_SESSION['flash'] = ['massage' => $massage, 'type' => $type];
}

function get_flash(): ? array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function validate_pasien(array $input): array
{
    $data = [
        'nama' => trim((string) ($input ['nama'] ?? '')),
        'alamat' => trim((string) ($input ['alamat'] ?? '')),
        'tanggal_lahir' => trim((string) ($input ['tanggal_lahir'] ?? '')),
        'jenis_kelamin' => trim((string) ($input ['jenis_kelamin'] ?? '')),
        'nomor_telepon' => trim((string) ($input ['nomor_telepon'] ?? '')),
    ];

    $errors = [];

    if ($data['nama'] === '') {
        $errors['nama'] = 'nama wajib di isi';
    } elseif (mb_strlen($data['nama']) > 100) {
        $errors['nama'] = 'maksimal 100 karakter';
    }

    if (mb_strlen($data['alamat']) > 255) {
        $errors['alamat'] = 'maksimal 255 karakter';
    }

    if ($data['tanggal_lahir'] !== '') {
        $date = DateTime::createFromFormat('Y-m-d', $data['tanggal_lahir']);
        if (!$date || $date -> format('Y-m-d') !== $data['tanggal_lahir']) {
            $errors['tanggal_lahir'] = 'Format tanggal lahir tidak valid';
        }
    }

    $allowedGender = ['Laki-laki', 'Perempuan'];
    if ($data['jenis_kelamin'] !== '' && !in_array($data['jenis_kelamin'], $allowedGender, true)) {
        $errors['jenis_kelamin'] = 'pilih jenis kelamin yang tersedia';
    }

    if (mb_strlen($data['nomor_telepon']) > 15) {
        $errors['nomor_telepon'] = 'maksimal 15 karakter';
    }

    return [$data, $errors];
}
?>