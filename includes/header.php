<?php
require_once __DIR__ . '/app.php';
$oturumKullanicisi = mevcutKullanici();
$sayfaBasligi = $sayfaBasligi ?? 'Kargo Takip';
$aktifSayfa = $aktifSayfa ?? '';
$bildirim = $_SESSION['bildirim'] ?? '';
unset($_SESSION['bildirim']);
?>
<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Kargo takip ve yönetim otomasyonu: gönderilerinizi ve teslimat sürecini tek yerden takip edin.">
        <title><?= htmlspecialchars($sayfaBasligi, ENT_QUOTES, 'UTF-8') ?> | Kargo</title>
        <link rel="icon" type="image/svg+xml" href="/shipping-automation/assets/favicon.svg">
        <link rel="stylesheet" href="/shipping-automation/assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/shipping-automation/assets/css/style.css">
        <link rel="stylesheet" href="/shipping-automation/assets/css/gonderi-karti.css">
    </head>
    <body>
        <a class="visually-hidden-focusable skip-link" href="#icerik">İçeriğe geç</a>

        <!-- Üst menü -->
        <header class="border-bottom bg-white">
            <nav class="navbar navbar-expand-sm py-3" aria-label="Ana menü">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="/shipping-automation/index.php">
                        <span class="brand-mark" aria-hidden="true">K</span>
                        Kargo<span class="brand-dot">.</span>
                    </a>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#ana-menu"
                        aria-controls="ana-menu"
                        aria-expanded="false"
                        aria-label="Menüyü aç veya kapat"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="ana-menu">
                        <ul class="navbar-nav ms-auto gap-sm-3">
                            <li class="nav-item">
                                <a class="nav-link <?= $aktifSayfa === 'anasayfa' ? 'active' : '' ?>"
                                    <?= $aktifSayfa === 'anasayfa' ? 'aria-current="page"' : '' ?> href="/shipping-automation/index.php">Ana sayfa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/shipping-automation/index.php#surec">Teslimat süreci</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/shipping-automation/index.php#hakkinda">Hakkında</a>
                            </li>
                            <?php if ($oturumKullanicisi && $oturumKullanicisi['aktif']): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $aktifSayfa === 'kargolar' ? 'active fw-semibold' : '' ?>" href="/shipping-automation/kargolar.php">
                                        <?= $oturumKullanicisi['rol'] === 'musteri' ? 'Kargolarım' : 'Kargolar' ?>
                                    </a>
                                </li>
                                <?php if ($oturumKullanicisi['rol'] === 'yonetici'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $aktifSayfa === 'personeller' ? 'active fw-semibold' : '' ?>" href="/shipping-automation/personeller.php">Personeller</a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <form action="/shipping-automation/logout.php" method="post">
                                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                                        <button class="btn btn-outline-secondary" type="submit">Çıkış yap</button>
                                    </form>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $aktifSayfa === 'login' ? 'active' : '' ?>"
                                        <?= $aktifSayfa === 'login' ? 'aria-current="page"' : '' ?> href="/shipping-automation/login.php">Giriş yap</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $aktifSayfa === 'register' ? 'active' : '' ?>"
                                        <?= $aktifSayfa === 'register' ? 'aria-current="page"' : '' ?> href="/shipping-automation/register.php">Kayıt ol</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <?php if ($bildirim): ?>
            <div class="container mt-4">
                <p class="alert alert-success mb-0" role="status"><?= e($bildirim) ?></p>
            </div>
        <?php endif; ?>