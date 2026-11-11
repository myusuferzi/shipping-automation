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

    foreach (['gonderici_adi', 'gonderici_adres', 'alici_adi', 'alici_adres', 'konum'] as $alan) {
        if ($bilgiler[$alan] === '' || strlen($bilgiler[$alan]) > 500) {
            $hata = 'Tüm metin alanlarını doldurun; her alan en fazla 500 bayt olabilir.';
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

$sayfaBasligi = 'Yeni kargo';
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
            <div class="col-md-6">
                <label class="form-label" for="musteri">Kargoyu takip edecek müşteri hesabı</label>
                <select class="form-select" id="musteri" name="musteri_id" required>
                    <option value="">Müşteri seçin</option>
                    <?php foreach ($musteriler as $musteri): ?>
                        <option value="<?= e($musteri['id']) ?>" <?= postMetni('musteri_id') === (string) $musteri['id'] ? 'selected' : '' ?>><?= e($musteri['ad_soyad']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
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
                <label class="form-label" for="gonderici-adi">Gönderici adı</label>
                <input class="form-control" id="gonderici-adi" name="gonderici_adi" value="<?= e(postMetni('gonderici_adi')) ?>" maxlength="120" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="alici-adi">Alıcı adı</label>
                <input class="form-control" id="alici-adi" name="alici_adi" value="<?= e(postMetni('alici_adi')) ?>" maxlength="120" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="gonderici-adres">Gönderici adresi</label>
                <textarea class="form-control" id="gonderici-adres" name="gonderici_adres" rows="3" maxlength="500" required><?= e(postMetni('gonderici_adres')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="alici-adres">Alıcı adresi</label>
                <textarea class="form-control" id="alici-adres" name="alici_adres" rows="3" maxlength="500" required><?= e(postMetni('alici_adres')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="konum">Mevcut konum</label>
                <input class="form-control" id="konum" name="konum" value="<?= e(postMetni('konum')) ?>" maxlength="200" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="teslimat">Planlanan teslimat tarihi</label>
                <input class="form-control" type="date" id="teslimat" name="teslimat_tarihi" value="<?= e(postMetni('teslimat_tarihi')) ?>" required>
            </div>
        </div>
        <button class="btn btn-primary mt-4" type="submit">Kargo oluştur</button>
    </form>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
