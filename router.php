<?php
// PHP'nin yerel geliştirme sunucusu her isteği önce bu dosyaya gönderir.
$yol = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');

// Var olan CSS, JavaScript ve görselleri sunucunun kendisi sunar.
$dosya = realpath(__DIR__ . $yol);
$assetsDizini = __DIR__ . '/assets/';

if (
    $dosya !== false
    && str_starts_with($dosya, $assetsDizini)
    && is_file($dosya)
) {
    return false;
}

// Yalnızca bu listedeki sayfalar ziyaret edilebilir.
$sayfalar = [
    '/' => 'index.php',
    '/index.php' => 'index.php',
    '/login.php' => 'login.php',
    '/register.php' => 'register.php',
    '/logout.php' => 'logout.php',
    '/kargolar.php' => 'kargolar.php',
    '/kargo.php' => 'kargo.php',
    '/kargo-ekle.php' => 'kargo-ekle.php',
    '/personeller.php' => 'personeller.php',
];

require __DIR__ . '/' . ($sayfalar[$yol] ?? '404.php');
