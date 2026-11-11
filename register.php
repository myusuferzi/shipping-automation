<?php
require_once __DIR__ . '/includes/app.php';
$hata = '';
$ad = postMetni('ad_soyad');
$eposta = postMetni('eposta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    $sifre = isset($_POST['sifre']) && is_string($_POST['sifre']) ? $_POST['sifre'] : '';
    $tekrar = isset($_POST['sifre_tekrar']) && is_string($_POST['sifre_tekrar']) ? $_POST['sifre_tekrar'] : '';

    if ($ad === '' || strlen($ad) > 120 || !filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
        $hata = 'Geçerli bir ad soyad ve e-posta adresi girin.';
    } elseif (strlen($sifre) < 8 || strlen($sifre) > 72 || $sifre !== $tekrar) {
        $hata = 'Şifre 8–72 bayt olmalı ve şifre tekrarıyla eşleşmelidir.';
    } else {
        try {
            $id = kullaniciEkle($ad, $eposta, password_hash($sifre, PASSWORD_DEFAULT), 'musteri');
            session_regenerate_id(true);
            $_SESSION['kullanici_id'] = $id;
            unset($_SESSION['csrf']);
            bildirimEkle('Hesabınız oluşturuldu. Hoş geldiniz.');
            yonlendir('/kargolar.php');
        } catch (RuntimeException $istisna) {
            $hata = $istisna->getMessage();
        }
    }
}

$sayfaBasligi = 'Kayıt ol';
$aktifSayfa = 'register';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <div class="auth-panel">
        <h1 class="h2">Kayıt ol</h1>
        <p class="text-secondary mb-4">Kargolarınızı takip etmek için bir müşteri hesabı oluşturun.</p>

        <?php if ($hata): ?>
            <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
        <?php endif; ?>

        <form action="/register.php" method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <div class="mb-3">
                <label class="form-label" for="ad-soyad">Ad soyad</label>
                <input class="form-control" type="text" id="ad-soyad" name="ad_soyad" value="<?= e($ad) ?>" autocomplete="name" maxlength="120" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="eposta">E-posta adresi</label>
                <input class="form-control" type="email" id="eposta" name="eposta" value="<?= e($eposta) ?>" autocomplete="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="sifre">Şifre</label>
                <input class="form-control" type="password" id="sifre" name="sifre" autocomplete="new-password" minlength="8" aria-describedby="sifre-notu" required>
                <p class="form-text" id="sifre-notu">En az 8 karakter kullanın.</p>
            </div>

            <div class="mb-4">
                <label class="form-label" for="sifre-tekrar">Şifre tekrar</label>
                <input class="form-control" type="password" id="sifre-tekrar" name="sifre_tekrar" autocomplete="new-password" minlength="8" required>
            </div>

            <button class="btn btn-primary w-100" type="submit">Kayıt ol</button>
        </form>

        <p class="text-center small mt-4 mb-0">
            Zaten hesabınız var mı? <a href="/login.php">Giriş yapın</a>
        </p>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
