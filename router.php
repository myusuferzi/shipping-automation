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

// Yeni sayfalar eklendikçe bu listeye de eklenir.
switch ($yol) {
    case '/':
    case '/index.php':
        require __DIR__ . '/index.php';
        break;

    case '/login.php':
        require __DIR__ . '/login.php';
        break;

    case '/register.php':
        require __DIR__ . '/register.php';
        break;

    default:
        require __DIR__ . '/404.php';
        break;
}
