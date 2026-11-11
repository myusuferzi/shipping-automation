<?php
$sayfaBasligi = 'İşleme izin verilmedi';
require __DIR__ . '/header.php';
?>

<main id="icerik" class="error-page container">
    <div class="error-content">
        <p class="error-code">403</p>
        <h1>İşleme izin verilmedi</h1>
        <p class="text-secondary my-4"><?= e($mesaj) ?></p>
        <a class="btn btn-primary px-4 py-2" href="/index.php">Ana sayfaya dön</a>
    </div>
</main>

<?php require __DIR__ . '/footer.php'; ?>
