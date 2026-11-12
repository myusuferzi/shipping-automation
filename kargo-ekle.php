<?php
require_once __DIR__ . '/includes/app.php';
$kullanici = girisGerekli(['yonetici']);
$hata = '';
$musteriler = [];
$personeller = [];

foreach (kullanicilariGetir() as $hesap) {
    if ($hesap['rol'] === 'musteri' && $hesap['aktif']) {
        $musteriler[] = $hesap;
    } elseif ($hesap['rol'] === 'personel' && $hesap['aktif']) {
        $personeller[] = $hesap;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    $bilgiler = [
        'musteri_id' => (int) postMetni('musteri_id'),
        'personel_id' => postMetni('personel_id') === '' ? null : (int) postMetni('personel_id'),
        'gonderici_adi' => postMetni('gonderici_adi'),
        'gonderici_adres' => postMetni('gonderici_adres'),
        'alici_adi' => postMetni('alici_adi'),
        'alici_adres' => postMetni('alici_adres'),
        'teslimat_tarihi' => postMetni('teslimat_tarihi'),
        'konum' => postMetni('konum'),
    ];

    $alanSinirlari = [
        'gonderici_adi' => 120,
        'alici_adi' => 120,
        'gonderici_adres' => 500,
        'alici_adres' => 500,
        'konum' => 200,
    ];
    foreach ($alanSinirlari as $alan => $sinir) {
        if ($bilgiler[$alan] === '' || strlen($bilgiler[$alan]) > $sinir) {
            $hata = 'Alanları kontrol edin: adlar 120, adresler 500, konum 200 baytı aşmamalıdır.';
        }
    }

    $tarih = DateTime::createFromFormat('!Y-m-d', $bilgiler['teslimat_tarihi']);
    if (!$tarih || $tarih->format('Y-m-d') !== $bilgiler['teslimat_tarihi']) {
        $hata = 'Geçerli bir teslimat tarihi seçin.';
    }

    if (!in_array($bilgiler['musteri_id'], array_column($musteriler, 'id'), true)) {
        $hata = 'Geçerli bir müşteri seçin.';
    }
    if ($bilgiler['personel_id'] !== null && !in_array($bilgiler['personel_id'], array_column($personeller, 'id'), true)) {
        $hata = 'Geçerli bir personel seçin.';
    }

    if (!$hata) {
        try {
            $id = kargoEkle($bilgiler, $kullanici['id']);
            bildirimEkle('Kargo oluşturuldu.');
            yonlendir('/kargo.php?id=' . $id);
        } catch (RuntimeException $istisna) {
            $hata = $istisna->getMessage();
        }
    }
}

$formBilgileri = [
    'musteri_id' => postMetni('musteri_id'),
    'gonderici_adi' => postMetni('gonderici_adi'),
    'gonderici_adres' => postMetni('gonderici_adres'),
    'alici_adi' => postMetni('alici_adi'),
    'alici_adres' => postMetni('alici_adres'),
    'teslimat_tarihi' => postMetni('teslimat_tarihi'),
];
$sayfaBasligi = 'Yeni kargo';
$aktifSayfa = 'kargolar';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <a href="/kargolar.php">Kargo listesine dön</a>
    <h1 class="h2 my-4">Yeni kargo</h1>
    <?php if ($hata): ?>
        <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
    <?php endif; ?>

    <form method="post" action="/kargo-ekle.php">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <div class="row g-4">
            <?php require __DIR__ . '/includes/kargo-alanlari.php'; ?>
            <div class="col-md-6">
                <label class="form-label" for="personel">Atanacak personel</label>
                <select class="form-select" id="personel" name="personel_id">
                    <option value="">Daha sonra ata</option>
                    <?php foreach ($personeller as $personel): ?>
                        <option value="<?= e($personel['id']) ?>" <?= postMetni('personel_id') === (string) $personel['id'] ? 'selected' : '' ?>><?= e($personel['ad_soyad']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="konum">Mevcut konum</label>
                <input class="form-control" id="konum" name="konum" value="<?= e(postMetni('konum')) ?>" maxlength="200" required>
            </div>
        </div>
        <button class="btn btn-primary mt-4" type="submit">Kargo oluştur</button>
    </form>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
