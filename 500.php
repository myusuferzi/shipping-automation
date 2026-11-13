<?php
http_response_code(500);
$hataKodu = 500;
$sayfaBasligi = 'Bir sorun oluştu';
$hataAciklamasi = 'İşleminizi şu anda tamamlayamıyoruz. Lütfen daha sonra tekrar deneyin.';

// Veritabanını yükleyen ortak header yerine bağımsız bir başlangıç kullanılır.
?>
<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bir sorun oluştu | Kargo</title>
        <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
        <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <header class="border-bottom py-3">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/index.php">
                    <span class="brand-mark" aria-hidden="true">K</span>
                    Kargo<span class="brand-dot">.</span>
                </a>
            </div>
        </header>

        <?php require __DIR__ . '/includes/hata-icerigi.php'; ?>
        <?php require __DIR__ . '/includes/footer.php'; ?>
