<?php
declare(strict_types=1);

$title = $title ?? 'CRUD Data Pasien';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <header class="page-header">
            <div>
                <p class="eyebrow">MySQL + PHP (ril buatan gweh)</p>
                <h1><?= e($title) ?></h1>
            </div>
            <a href="index.php" class="button_sekend">Daftar Pasien</a>
        </header>