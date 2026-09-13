<?php
declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/helpers.php';

$statement = $pdo -> query(
    'SELECT id, nama, alamat, tanggal_lahir, jenis_kelamin, nomor_telepon
    FROM pasien
    ORDER BY id DESC'
);
$pasien = $statement -> fetchAll();
$flash = get_flash();
$title = 'Data Pasien';

require __DIR__ . '/partials/header.php'
?>

<?php if (!$pasien): ?>
    <div class="empty-state">Belum ada data pasien. Silahakan tambah data pertama.</div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tanggal lahir</th>
                    <th>Jenis kelamin</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($pasien as $row): ?>
                <tr>
                    <td><?= e($row['nama']) ?></td>
                    <td><?= e($row['alamat'] ?: '-') ?></td>
                    <td><?= e($row['tanggal_lahir'] ?: '-') ?></td>
                    <td><?= e($row['jenis_kelamin'] ?: '-') ?></td>
                    <td><?= e($row['nomor_telepon'] ?: '-') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
