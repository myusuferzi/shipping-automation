<main id="icerik" class="error-page container">
    <div class="error-content">
        <p class="error-code"><?= htmlspecialchars((string) $hataKodu, ENT_QUOTES, 'UTF-8') ?></p>
        <h1><?= htmlspecialchars($sayfaBasligi, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-secondary my-4">
            <?= htmlspecialchars($hataAciklamasi, ENT_QUOTES, 'UTF-8') ?>
        </p>
        <a class="btn btn-primary px-4 py-2" href="/index.php">Ana sayfaya dön</a>
    </div>
</main>
