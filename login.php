<?php
require_once __DIR__ . '/includes/app.php';
$hata = '';
$eposta = postMetni('eposta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    $hesap = epostaIleKullaniciGetir($eposta);
    $sifre = isset($_POST['sifre']) && is_string($_POST['sifre']) ? $_POST['sifre'] : '';

    if ($hesap && $hesap['aktif'] && password_verify($sifre, $hesap['sifre_hash'])) {
        session_regenerate_id(true);
        $_SESSION['kullanici_id'] = $hesap['id'];
        unset($_SESSION['csrf']);
        yonlendir('/kargolar.php');
    }

    $hata = 'E-posta veya şifre hatalı ya da hesap aktif değil.';
}

$sayfaBasligi = 'Giriş yap';
$aktifSayfa = 'login';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <div class="auth-panel">
        <h1 class="h2">Giriş yap</h1>
        <p class="text-secondary mb-4">Kargolarınızı takip etmek için hesabınıza giriş yapın.</p>

        <?php if ($hata): ?>
            <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
        <?php endif; ?>

        <details class="mb-4">
            <summary>Örnek hesaplar</summary>
            <p class="small mt-2 mb-0">yonetici@example.test<br>personel@example.test<br>musteri@example.test<br>Ortak şifre: <code>Deneme123!</code></p>
        </details>

        <form action="/login.php" method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <div class="mb-3">
                <label class="form-label" for="eposta">E-posta adresi</label>
                <input class="form-control" type="email" id="eposta" name="eposta" value="<?= e($eposta) ?>" autocomplete="email" required>
            </div>

            <div class="mb-4">
                <label class="form-label" for="sifre">Şifre</label>
                <div class="input-group">
                    <input class="form-control" type="password" id="sifre" name="sifre" autocomplete="current-password" required>
                    <button class="btn btn-outline-secondary" type="button"
                        data-sifre-goster="sifre" aria-controls="sifre" hidden>Göster</button>
                </div>
            </div>

            <button class="btn btn-primary w-100" type="submit">Giriş yap</button>
        </form>

        <p class="text-center small mt-4 mb-0">
            Hesabınız yok mu? <a href="/register.php">Kayıt olun</a>
        </p>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
