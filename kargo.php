<?php
require_once __DIR__ . '/includes/app.php';
$kullanici = girisGerekli();
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$kargo = null;

// Detay adresini bilmek, kargoya erişim hakkı vermez.
foreach (kargolariGor($kullanici) as $kayit) {
    if ($kayit['id'] === $id) {
        $kargo = $kayit;
        break;
    }
}

if (!$kargo) {
    require __DIR__ . '/404.php';
    exit;
}

$duzenleyebilir = in_array($kullanici['rol'], ['yonetici', 'personel'], true);
$hata = '';
$personeller = array_filter(kullanicilariGetir(), function ($hesap) {
    return $hesap['rol'] === 'personel' && $hesap['aktif'];
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfKontrol();
    if (!$duzenleyebilir) {
        http_response_code(403);
        exit('Bu işlem için yetkiniz bulunmuyor.');
    }

    $durum = postMetni('durum');
    $konum = postMetni('konum');
    $aciklama = postMetni('aciklama');
    $personelId = $kargo['personel_id'];

    // Personel, kargoyu başka bir personele atayamaz.
    if ($kullanici['rol'] === 'yonetici') {
        $personelId = postMetni('personel_id') === '' ? null : (int) postMetni('personel_id');
        $personel = kullaniciGetir($personelId ?? 0);
        if ($personelId !== null && (!$personel || $personel['rol'] !== 'personel' || !$personel['aktif'])) {
            $hata = 'Geçerli bir personel seçin.';
        }
    }

    if (!in_array($durum, kargoDurumlari(), true) || $konum === '' || strlen($konum) > 200 || strlen($aciklama) > 1000) {
        $hata = 'Durum ve konum alanlarını kontrol edin. Açıklama en fazla 1000 bayt olabilir.';
    }

    if (!$hata) {
        kargoGuncelle($id, $durum, $konum, $personelId, $aciklama, $kullanici['id']);
        yonlendir('/kargo.php?id=' . $id);
    }
}

$sayfaBasligi = $kargo['takip_no'];
$hareketler = kargoHareketleriGetir($id);
$atananPersonel = kullaniciGetir($kargo['personel_id'] ?? 0);
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <a href="/kargolar.php">Kargo listesine dön</a>
    <h1 class="h2 mt-4"><?= e($kargo['takip_no']) ?></h1>
    <div class="row g-4 align-items-center my-3">
        <div class="col-lg-7">
            <div class="row g-4 my-3">
                <section class="col-md-6" aria-labelledby="gonderici-baslik">
                    <h2 class="h5" id="gonderici-baslik">Gönderici</h2>
                    <p><?= e($kargo['gonderici_adi']) ?><br><?= e($kargo['gonderici_adres']) ?></p>
                </section>
                <section class="col-md-6" aria-labelledby="alici-baslik">
                    <h2 class="h5" id="alici-baslik">Alıcı</h2>
                    <p><?= e($kargo['alici_adi']) ?><br><?= e($kargo['alici_adres']) ?></p>
                </section>
            </div>
            <p>Planlanan teslimat: <strong><?= e($kargo['teslimat_tarihi']) ?></strong></p>
            <p>Atanan personel: <?= e($atananPersonel['ad_soyad'] ?? 'Henüz atanmadı') ?></p>
        </div>
        <div class="col-lg-5">
            <?php
            $kartOrnek = false;
            $kartKargo = $kargo;
            require __DIR__ . '/includes/gonderi-karti.php';
            ?>
        </div>
    </div>

    <?php if ($duzenleyebilir): ?>
        <section class="border rounded p-4 my-4" aria-labelledby="guncelle-baslik">
            <h2 class="h5 mb-3" id="guncelle-baslik">Kargoyu güncelle</h2>
            <?php if ($hata): ?>
                <p class="alert alert-danger" role="alert"><?= e($hata) ?></p>
            <?php endif; ?>
            <form method="post" action="/kargo.php?id=<?= e($id) ?>">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="durum">Durum</label>
                        <select class="form-select" id="durum" name="durum" required>
                            <?php foreach (kargoDurumlari() as $durumSecenegi): ?>
                                <option <?= $kargo['durum'] === $durumSecenegi ? 'selected' : '' ?>><?= e($durumSecenegi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="konum">Mevcut konum</label>
                        <input class="form-control" id="konum" name="konum" value="<?= e($kargo['konum']) ?>" maxlength="200" required>
                    </div>
                    <?php if ($kullanici['rol'] === 'yonetici'): ?>
                        <div class="col-md-6">
                            <label class="form-label" for="personel">Atanan personel</label>
                            <select class="form-select" id="personel" name="personel_id">
                                <option value="">Atanmadı</option>
                                <?php foreach ($personeller as $personel): ?>
                                    <option value="<?= e($personel['id']) ?>" <?= $kargo['personel_id'] === $personel['id'] ? 'selected' : '' ?>><?= e($personel['ad_soyad']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <label class="form-label" for="aciklama">İşlem açıklaması</label>
                        <textarea class="form-control" id="aciklama" name="aciklama" rows="2" maxlength="1000"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Kaydet</button>
            </form>
        </section>
    <?php endif; ?>

    <h2 class="h4 mt-5 mb-3">Hareket geçmişi</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Tarih</th>
                    <th scope="col">Durum</th>
                    <th scope="col">Konum</th>
                    <th scope="col">Açıklama</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hareketler as $hareket): ?>
                    <tr>
                        <td><?= e($hareket['tarih']) ?></td>
                        <td><?= e($hareket['durum']) ?></td>
                        <td><?= e($hareket['konum']) ?></td>
                        <td><?= e($hareket['aciklama']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
