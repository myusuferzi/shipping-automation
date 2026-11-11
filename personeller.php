<?php
require_once __DIR__ . '/includes/app.php';
$kullanici = girisGerekli(['yonetici']);
$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    try {
        if (postMetni('islem') === 'aktiflik') {
            personelAktifliginiDegistir((int) postMetni('id'));
        } elseif (postMetni('islem') === 'ekle') {
            $ad = postMetni('ad_soyad');
            $eposta = postMetni('eposta');
            $sifre = isset($_POST['sifre']) && is_string($_POST['sifre']) ? $_POST['sifre'] : '';
            if ($ad === '' || strlen($ad) > 120 || !filter_var($eposta, FILTER_VALIDATE_EMAIL) || strlen($sifre) < 8 || strlen($sifre) > 72) {
                throw new RuntimeException('Ad soyad, geçerli e-posta ve 8–72 bayt uzunluğunda şifre girin.');
            }
            kullaniciEkle($ad, $eposta, password_hash($sifre, PASSWORD_DEFAULT), 'personel');
        } else {
            throw new RuntimeException('Geçersiz işlem.');
        }
        yonlendir('/personeller.php');
    } catch (RuntimeException $istisna) {
        $hata = $istisna->getMessage();
    }
}

$personeller = array_filter(kullanicilariGetir(), function ($hesap) {
    return $hesap['rol'] === 'personel';
});
$sayfaBasligi = 'Personeller';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <h1 class="h2 mb-4">Personeller</h1>
    <?php if ($hata): ?>
        <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
    <?php endif; ?>

    <div class="table-responsive mb-5">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col">Ad soyad</th>
                    <th scope="col">E-posta</th>
                    <th scope="col">Durum</th>
                    <th scope="col">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personeller as $personel): ?>
                    <tr>
                        <td><?= e($personel['ad_soyad']) ?></td>
                        <td><?= e($personel['eposta']) ?></td>
                        <td><?= $personel['aktif'] ? 'Aktif' : 'Pasif' ?></td>
                        <td>
                            <form method="post" action="/personeller.php">
                                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="islem" value="aktiflik">
                                <input type="hidden" name="id" value="<?= e($personel['id']) ?>">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">
                                    <?= $personel['aktif'] ? 'Pasife al' : 'Aktifleştir' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <section class="border rounded p-4" aria-labelledby="personel-ekle">
        <h2 class="h4 mb-3" id="personel-ekle">Personel ekle</h2>
        <form method="post" action="/personeller.php">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="islem" value="ekle">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="ad-soyad">Ad soyad</label>
                    <input class="form-control" id="ad-soyad" name="ad_soyad" value="<?= e(postMetni('ad_soyad')) ?>" maxlength="120" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="eposta">E-posta</label>
                    <input class="form-control" type="email" id="eposta" name="eposta" value="<?= e(postMetni('eposta')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="sifre">Başlangıç şifresi</label>
                    <input class="form-control" type="password" id="sifre" name="sifre" minlength="8" autocomplete="new-password" required>
                </div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Personel oluştur</button>
        </form>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
