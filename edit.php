<?php
declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/helpers.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID pasien tidak valid.');
}

$stmt = $pdo->prepare('SELECT * FROM pasien WHERE id = :id');
$stmt->execute([':id' => $id]);
$pasien = $stmt->fetch();

if (!$pasien) {
    http_response_code(404);
    exit('Data pasien tidak ditemukan.');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$pasien, $errors] = validate_pasien($_POST);

    if (!$errors) {
        $update = $pdo->prepare(
            'UPDATE pasien
             SET nama = :nama,
                 alamat = :alamat,
                 tanggal_lahir = :tanggal_lahir,
                 jenis_kelamin = :jenis_kelamin,
                 nomor_telepon = :nomor_telepon
             WHERE id = :id'
        );
        $update->execute([
            ':nama' => $pasien['nama'],
            ':alamat' => $pasien['alamat'] ?: null,
            ':tanggal_lahir' => $pasien['tanggal_lahir'] ?: null,
            ':jenis_kelamin' => $pasien['jenis_kelamin'] ?: null,
            ':nomor_telepon' => $pasien['nomor_telepon'] ?: null,
            ':id' => $id,
        ]);

        set_flash('Data pasien berhasil diubah.');
        header('Location: index.php');
        exit;
    }
}

$title = 'Edit Pasien';
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
        <textarea id="alamat" name="alamat" maxlength="255" rows="3"><?= e($pasien['alamat'] ?? '') ?></textarea>
        <?php if (isset($errors['alamat'])): ?><small class="error"><?= e($errors['alamat']) ?></small><?php endif; ?>
    </div>

    <div class="field">
        <label for="tanggal_lahir">Tanggal lahir</label>
        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="<?= e($pasien['tanggal_lahir'] ?? '') ?>">
        <?php if (isset($errors['tanggal_lahir'])): ?><small class="error"><?= e($errors['tanggal_lahir']) ?></small><?php endif; ?>
    </div>

    <div class="field">
        <label for="jenis_kelamin">Jenis kelamin</label>
        <select id="jenis_kelamin" name="jenis_kelamin">
            <option value="">-- Pilih --</option>
            <?php foreach (['Laki-laki', 'Perempuan'] as $gender): ?>
                <option value="<?= e($gender) ?>" <?= ($pasien['jenis_kelamin'] ?? '') === $gender ? 'selected' : '' ?>><?= e($gender) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['jenis_kelamin'])): ?><small class="error"><?= e($errors['jenis_kelamin']) ?></small><?php endif; ?>
    </div>

    <div class="field">
        <label for="nomor_telepon">Nomor telepon</label>
        <input id="nomor_telepon" name="nomor_telepon" value="<?= e($pasien['nomor_telepon'] ?? '') ?>" maxlength="15">
        <?php if (isset($errors['nomor_telepon'])): ?><small class="error"><?= e($errors['nomor_telepon']) ?></small><?php endif; ?>
    </div>

    <div class="form-actions full">
        <a class="button secondary" href="index.php">Batal</a>
        <button class="button" type="submit">Simpan perubahan</button>
    </div>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
