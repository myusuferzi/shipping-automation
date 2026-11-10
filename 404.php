<?php
http_response_code(404);
$sayfaBasligi = 'Sayfa bulunamadı';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="error-page container">
    <div class="error-content">
        <p class="error-code">404</p>
        <h1>Sayfa bulunamadı</h1>
        <p class="text-secondary my-4">
            Aradığınız sayfaya ulaşamadık. Adresi kontrol edebilir
            veya ana sayfaya dönebilirsiniz.
        </p>
        <a class="btn btn-primary px-4 py-2" href="/index.php">Ana sayfaya dön</a>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
