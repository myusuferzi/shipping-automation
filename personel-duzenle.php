<?php
require_once __DIR__ . '/includes/app.php';
girisGerekli(['yonetici']);
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$personel = kullaniciGetir($id ?: 0);

if (!$personel || $personel['rol'] !== 'personel') {
    require __DIR__ . '/404.php';
    exit;
}

$hata = '';
$ad = $personel['ad_soyad'];
$eposta = $personel['eposta'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    $ad = postMetni('ad_soyad');
    $eposta = postMetni('eposta');
    $sifre = isset($_POST['sifre']) && is_string($_POST['sifre']) ? $_POST['sifre'] : '';
    $tekrar = isset($_POST['sifre_tekrar']) && is_string($_POST['sifre_tekrar']) ? $_POST['sifre_tekrar'] : '';

    if ($ad === '' || strlen($ad) > 120 || !filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
        $hata = 'Geçerli bir ad soyad ve e-posta adresi girin.';
    } elseif ($sifre !== $tekrar || ($sifre !== '' && (strlen($sifre) < 8 || strlen($sifre) > 72))) {
        $hata = 'Yeni şifre 8–72 bayt olmalı ve şifre tekrarıyla eşleşmelidir.';
    } else {
        try {
            // Boş bırakılan şifre, mevcut şifrenin korunması demektir.
            $sifreHash = $sifre === '' ? null : password_hash($sifre, PASSWORD_DEFAULT);
            personelGuncelle($id, $ad, $eposta, $sifreHash);
            bildirimEkle('Personel bilgileri güncellendi.');
            yonlendir('/personeller.php');
        } catch (RuntimeException $istisna) {
            $hata = $istisna->getMessage();
        }
    }
}

$sayfaBasligi = 'Personel düzenle';
$aktifSayfa = 'personeller';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <div class="auth-panel">
        <a class="small" href="/personeller.php">Personel listesine dön</a>
        <h1 class="h2 mt-4 mb-3">Personel düzenle</h1>

        <?php if ($hata): ?>
            <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
        <?php endif; ?>

        <form action="/personel-duzenle.php?id=<?= e($id) ?>" method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

            <div class="mb-3">
                <label class="form-label" for="ad-soyad">Ad soyad</label>
                <input class="form-control" id="ad-soyad" name="ad_soyad" value="<?= e($ad) ?>" maxlength="120" required>
            </div>

            <div class="mb-4">
                <label class="form-label" for="eposta">E-posta</label>
                <input class="form-control" type="email" id="eposta" name="eposta" value="<?= e($eposta) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="sifre">Yeni şifre (isteğe bağlı)</label>
                <input class="form-control" type="password" id="sifre" name="sifre" minlength="8" autocomplete="new-password" aria-describedby="sifre-notu">
                <p class="form-text" id="sifre-notu">Mevcut şifreyi korumak için boş bırakın.</p>
            </div>

            <div class="mb-4">
                <label class="form-label" for="sifre-tekrar">Yeni şifre tekrar</label>
                <input class="form-control" type="password" id="sifre-tekrar" name="sifre_tekrar" minlength="8" autocomplete="new-password">
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Kaydet</button>
                <a class="btn btn-outline-secondary" href="/personeller.php">Vazgeç</a>
            </div>
        </form>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
