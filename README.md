# Belajar CRUD PHP + MySQL: Data Pasien

Project ini adalah contoh CRUD sederhana untuk belajar PHP dan database MySQL.
CRUD berarti:

- **Create**: menambah data pasien (`tambah.php`)
- **Read**: menampilkan data pasien (`index.php`)
- **Update**: mengubah data pasien (`edit.php`)
- **Delete**: menghapus data pasien (`hapus.php`)

## 1. Siapkan database

1. Jalankan Apache dan MySQL dari XAMPP/Laragon.
2. Buka phpMyAdmin.
3. Import file `data_base.sql` atau jalankan isinya di tab SQL.
4. Pastikan database `db_rumah_sakit` dan tabel `pasien` sudah muncul.

File `.env` yang ada di folder ini dibaca oleh `config.php`. Konfigurasi yang dipakai:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=db_rumah_sakit
DB_USER=root
DB_PASS=
```

Jika MySQL Anda memakai port lain, ubah nilai `DB_PORT` sesuai konfigurasi MySQL Anda.

## 2. Jalankan aplikasi

Cara paling mudah dari PowerShell:

```powershell
cd D:\Belajar_Database\Belajar_SQL
php -S localhost:8000
```

Kemudian buka `http://localhost:8000` di browser.

## 3. Cara kerja program

### Koneksi database

`config.php` membuat objek `PDO`. PDO dipakai agar PHP dapat berbicara dengan MySQL. Konfigurasi koneksi diambil dari `.env`, lalu dibuat menjadi DSN:

```php
$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=db_rumah_sakit;charset=utf8mb4';
$pdo = new PDO($dsn, $username, $password, $options);
```

### Read

`index.php` menjalankan `SELECT` untuk mengambil semua pasien, lalu `foreach` menampilkan hasilnya sebagai baris tabel.

### Create

Form di `tambah.php` mengirim data dengan method `POST`. Data divalidasi, lalu disimpan menggunakan `INSERT` dan `execute()`.

### Update

`edit.php?id=3` mengambil pasien dengan ID 3. Saat form disimpan, program menjalankan `UPDATE ... WHERE id = :id`.

### Delete

Tombol hapus mengirim `POST` ke `hapus.php`. Program menjalankan `DELETE ... WHERE id = :id`, lalu kembali ke daftar pasien.

## 4. Hal penting untuk dipelajari

- `prepare()` dan `execute()` adalah prepared statement. Ini membantu mencegah SQL injection.
- `htmlspecialchars()` di fungsi `e()` membantu mencegah HTML/script dari input pengguna tampil sebagai kode.
- Validasi dilakukan di server, bukan hanya mengandalkan atribut `required` di HTML.
- Penghapusan menggunakan `POST`, bukan link `GET`, dan dilindungi token CSRF sederhana.
- Setelah `INSERT`, `UPDATE`, atau `DELETE`, browser diarahkan kembali ke `index.php` agar halaman tidak mengirim ulang form saat di-refresh.
