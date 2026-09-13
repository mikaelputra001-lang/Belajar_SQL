<?php
declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Penghapusan harus dilakukan melalui form POST.');
}

verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    set_flash('ID pasien tidak valid.', 'error');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM pasien WHERE id = :id');
$stmt->execute([':id' => $id]);
$deleted = $stmt->rowCount() > 0;

set_flash($deleted ? 'Data pasien berhasil dihapus.' : 'Data pasien tidak ditemukan.', $deleted ? 'success' : 'error');
header('Location: index.php');
exit;
