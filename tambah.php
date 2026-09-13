<?php
declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/helpers.php';

$pasien = [
    'nama' => '',
    'alamat' => '',
    'tanggal_lahir' => '',
    'jenis_kelamin' => '',
    'nomor_telepon' => '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$pasien, $errors] = validate_pasien($_POST);

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO pasien (nama, alamat, tanggal_lahir, jenis_kelamin, nomor_telepon)
             VALUES (:nama, :alamat, :tanggal_lahir, :jenis_kelamin, :nomor_telepon)'
        );
        $stmt->execute([
            ':nama' => $pasien['nama'],
            ':alamat' => $pasien['alamat'] ?: null,
            ':tanggal_lahir' => $pasien['tanggal_lahir'] ?: null,
            ':jenis_kelamin' => $pasien['jenis_kelamin'] ?: null,
            ':nomor_telepon' => $pasien['nomor_telepon'] ?: null,
        ]);

        set_flash('Data pasien berhasil ditambahkan.');
        header('Location: index.php');
        exit;
    }
}

$title = 'Tambah Pasien';
require __DIR__ . '/partials/header.php';
?>

<form class="card form-grid" method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="field full">
        <label for="nama">Nama *</label>
        <input id="nama" name="nama" value="<?= e($pasien['nama']) ?>" maxlength="100" required>
        <?php if (isset($errors['nama'])): ?><small class="error"><?= e($errors['nama']) ?></small><?php endif; ?>
    </div>

    <div class="field full">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" maxlength="255" rows="3"><?= e($pasien['alamat']) ?></textarea>
        <?php if (isset($errors['alamat'])): ?><small class="error"><?= e($errors['alamat']) ?></small><?php endif; ?>
    </div>

    <div class="field">
        <label for="tanggal_lahir">Tanggal lahir</label>
        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="<?= e($pasien['tanggal_lahir']) ?>">
        <?php if (isset($errors['tanggal_lahir'])): ?><small class="error"><?= e($errors['tanggal_lahir']) ?></small><?php endif; ?>
    </div>

    <div class="field">
        <label for="jenis_kelamin">Jenis kelamin</label>
        <select id="jenis_kelamin" name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <?php foreach (['Laki-laki', 'Perempuan'] as $gender): ?>
                <option value="<?= e($gender) ?>" <?= $pasien['jenis_kelamin'] === $gender ? 'selected' : '' ?>><?= e($gender) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['jenis_kelamin'])): ?><small class="error"><?= e($errors['jenis_kelamin']) ?></small><?php endif; ?>
    </div>

    <div class="field">
        <label for="nomor_telepon">Nomor telepon</label>
        <input id="nomor_telepon" name="nomor_telepon" value="<?= e($pasien['nomor_telepon']) ?>" maxlength="15">
        <?php if (isset($errors['nomor_telepon'])): ?><small class="error"><?= e($errors['nomor_telepon']) ?></small><?php endif; ?>
    </div>

    <div class="form-actions full">
        <a class="button secondary" href="index.php">Batal</a>
        <button class="button" type="submit">Simpan</button>
    </div>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
