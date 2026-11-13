<?php
http_response_code(404);
$hataKodu = 404;
$sayfaBasligi = 'Sayfa bulunamadı';
$hataAciklamasi = 'Aradığınız sayfaya ulaşamadık. Adresi kontrol edebilir veya ana sayfaya dönebilirsiniz.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/hata-icerigi.php';
require __DIR__ . '/includes/footer.php';
