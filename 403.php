<?php
http_response_code(403);
$hataKodu = 403;
$sayfaBasligi = 'İşleme izin verilmedi';
$hataAciklamasi = $mesaj ?? 'Bu işlem için yetkiniz bulunmuyor.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/hata-icerigi.php';
require __DIR__ . '/includes/footer.php';
