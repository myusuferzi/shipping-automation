<?php
require_once __DIR__ . '/includes/app.php';
$kullanici = girisGerekli(['yonetici']);
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$kargo = kargoGetir($id ?: 0);

if (!$kargo) {
    require __DIR__ . '/404.php';
    exit;
}

$hata = '';
$formBilgileri = $kargo;
$musteriler = array_filter(kullanicilariGetir(), function ($hesap) {
    return $hesap['rol'] === 'musteri' && $hesap['aktif'];
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    $formBilgileri = [
        'musteri_id' => (int) postMetni('musteri_id'),
        'gonderici_adi' => postMetni('gonderici_adi'),
        'gonderici_adres' => postMetni('gonderici_adres'),
        'alici_adi' => postMetni('alici_adi'),
        'alici_adres' => postMetni('alici_adres'),
        'teslimat_tarihi' => postMetni('teslimat_tarihi'),
    ];

    foreach (['gonderici_adi', 'alici_adi', 'gonderici_adres', 'alici_adres'] as $alan) {
        $sinir = in_array($alan, ['gonderici_adi', 'alici_adi'], true) ? 120 : 500;
        if ($formBilgileri[$alan] === '' || strlen($formBilgileri[$alan]) > $sinir) {
            $hata = 'Gönderici ve alıcı bilgilerini kontrol edin. Adlar en fazla 120, adresler 500 bayt olabilir.';
        }
    }

    $tarih = DateTime::createFromFormat('!Y-m-d', $formBilgileri['teslimat_tarihi']);
    if (!$tarih || $tarih->format('Y-m-d') !== $formBilgileri['teslimat_tarihi']) {
        $hata = 'Geçerli bir teslimat tarihi seçin.';
    }

    if (!in_array($formBilgileri['musteri_id'], array_column($musteriler, 'id'), true)) {
        $hata = 'Geçerli bir müşteri seçin.';
    }

    if (!$hata) {
        try {
            kargoBilgileriniGuncelle($id, $formBilgileri, $kullanici['id']);
            bildirimEkle('Gönderici, alıcı ve teslimat bilgileri güncellendi.');
            yonlendir('/kargo.php?id=' . $id);
        } catch (RuntimeException $istisna) {
            $hata = $istisna->getMessage();
        }
    }
}

$sayfaBasligi = 'Kargo düzenle';
$aktifSayfa = 'kargolar';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <a href="/kargo.php?id=<?= e($id) ?>">Kargo detayına dön</a>
    <h1 class="h2 mt-4">Kargo düzenle</h1>
    <p class="text-secondary"><?= e($kargo['takip_no']) ?></p>
    <p class="alert alert-light border small">
        Müşteri hesabını değiştirirseniz kargo, seçtiğiniz müşterinin listesine taşınır.
        Durum, konum ve personel ataması kargo detayından güncellenir.
    </p>

    <?php if ($hata): ?>
        <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
    <?php endif; ?>

    <form method="post" action="/kargo-duzenle.php?id=<?= e($id) ?>">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <div class="row g-4">
            <?php require __DIR__ . '/includes/kargo-alanlari.php'; ?>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary" type="submit">Kaydet</button>
            <a class="btn btn-outline-secondary" href="/kargo.php?id=<?= e($id) ?>">Vazgeç</a>
        </div>
    </form>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
