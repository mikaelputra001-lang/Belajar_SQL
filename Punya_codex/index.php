<?php
declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/helpers.php';

$stmt = $pdo->query(
    'SELECT id, nama, alamat, tanggal_lahir, jenis_kelamin, nomor_telepon
     FROM pasien
     ORDER BY id DESC'
);
$pasien = $stmt->fetchAll();
$flash = get_flash();
$title = 'Data Pasien';

require __DIR__ . '/partials/header.php';
?>

<?php if ($flash): ?>
    <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>

<div class="toolbar">
    <p><?= count($pasien) ?> data pasien</p>
    <a class="button" href="tambah.php">+ Tambah pasien</a>
</div>

<?php if (!$pasien): ?>
    <div class="empty-state">Belum ada data pasien. Silakan tambahkan data pertama.</div>
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
            <?php foreach ($pasien as $row): ?>
                <tr>
                    <td><?= e($row['nama']) ?></td>
                    <td><?= e($row['alamat'] ?: '-') ?></td>
                    <td><?= e($row['tanggal_lahir'] ?: '-') ?></td>
                    <td><?= e($row['jenis_kelamin'] ?: '-') ?></td>
                    <td><?= e($row['nomor_telepon'] ?: '-') ?></td>
                    <td class="actions">
                        <a class="button small secondary" href="edit.php?id=<?= (int) $row['id'] ?>">Edit</a>
                        <form method="post" action="hapus.php" onsubmit="return confirm('Hapus data ini?');">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                            <button class="button small danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
