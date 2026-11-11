<?php
require_once __DIR__ . '/includes/app.php';
$kullanici = girisGerekli();
$kargolar = kargolariGor($kullanici);
$siralama = ($_GET['sirala'] ?? '') === 'azalan' ? 'azalan' : 'artan';
$arama = isset($_GET['arama']) && is_string($_GET['arama']) ? trim($_GET['arama']) : '';
$durumFiltresi = isset($_GET['durum']) && is_string($_GET['durum']) ? $_GET['durum'] : '';
if (!in_array($durumFiltresi, kargoDurumlari(), true)) {
    $durumFiltresi = '';
}

// Önce erişim kontrolü yapılır; yalnızca kullanıcının görebildiği kayıtlar aranır.
$kargolar = array_values(array_filter($kargolar, function ($kargo) use ($arama, $durumFiltresi) {
    $aramaUyuyor = $arama === ''
        || stripos($kargo['takip_no'], $arama) !== false
        || stripos($kargo['alici_adi'], $arama) !== false;
    $durumUyuyor = $durumFiltresi === '' || $kargo['durum'] === $durumFiltresi;

    return $aramaUyuyor && $durumUyuyor;
}));


usort($kargolar, function ($a, $b) use ($siralama) {
    $sonuc = strcmp($a['teslimat_tarihi'], $b['teslimat_tarihi']);
    return $siralama === 'artan' ? $sonuc : -$sonuc;
});

// Bootstrap renkleri; durum metni renk olmadan da okunabilir.
$durumRenkleri = [
    'Kabul edildi' => 'bg-secondary-subtle text-secondary-emphasis',
    'Transferde' => 'bg-info-subtle text-info-emphasis',
    'Dağıtımda' => 'bg-warning-subtle text-warning-emphasis',
    'Teslim edildi' => 'bg-success-subtle text-success-emphasis',
];

$sayfaBasligi = $kullanici['rol'] === 'musteri' ? 'Kargolarım' : 'Kargolar';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2"><?= e($sayfaBasligi) ?></h1>
            <p class="text-secondary mb-0">Hoş geldiniz, <?= e($kullanici['ad_soyad']) ?>.</p>
        </div>
        <?php if ($kullanici['rol'] === 'yonetici'): ?>
            <a class="btn btn-primary" href="/kargo-ekle.php">Yeni kargo</a>
        <?php endif; ?>
    </div>

    <section class="shipment-list border rounded-3 overflow-hidden" aria-label="Kargo listesi">
        <div class="list-toolbar bg-success-subtle p-3 p-md-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
            <p class="mb-0 fw-semibold text-success-emphasis">
                Gönderiler
                <span class="badge rounded-pill bg-white text-success-emphasis border border-success-subtle ms-2"><?= count($kargolar) ?> kayıt</span>
            </p>

        </div>

        <form class="row g-3 m-0 p-3 border-bottom align-items-end" action="/kargolar.php" method="get">
            <div class="col-md-4">
                <label class="form-label small" for="arama">Takip numarası veya alıcı</label>
                <input class="form-control form-control-sm" type="search" id="arama" name="arama" value="<?= e($arama) ?>" placeholder="Kargo ara">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label small" for="durum">Durum</label>
                <select class="form-select form-select-sm" id="durum" name="durum">
                    <option value="">Tüm durumlar</option>
                    <?php foreach (kargoDurumlari() as $durum): ?>
                        <option <?= $durumFiltresi === $durum ? 'selected' : '' ?>><?= e($durum) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label small" for="sirala">Teslimat tarihi</label>
                <select class="form-select form-select-sm" id="sirala" name="sirala">
                    <option value="artan" <?= $siralama === 'artan' ? 'selected' : '' ?>>En yakın önce</option>
                    <option value="azalan" <?= $siralama === 'azalan' ? 'selected' : '' ?>>En uzak önce</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-sm btn-primary" type="submit">Uygula</button>
                <a class="btn btn-sm btn-outline-secondary" href="/kargolar.php">Temizle</a>
            </div>
        </form>

        <?php if (!$kargolar): ?>
            <div class="text-center p-5">
                <?php if ($arama !== '' || $durumFiltresi !== ''): ?>
                    <h2 class="h5">Aramanıza uygun kargo bulunamadı</h2>
                    <p class="text-secondary mb-0">Farklı bir arama deneyin veya filtreleri temizleyin.</p>
                <?php else: ?>
                    <h2 class="h5">Henüz kargo yok</h2>
                    <p class="text-secondary mb-0">Görüntüleyebileceğiniz gönderiler burada listelenecek.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Takip numarası</th>
                            <th scope="col">Alıcı</th>
                            <th scope="col">Durum</th>
                            <th scope="col">Konum</th>
                            <th scope="col">Planlanan teslimat</th>
                            <th scope="col" class="text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kargolar as $kargo): ?>
                            <tr>
                                <th scope="row">
                                    <a class="tracking-link" href="/kargo.php?id=<?= e($kargo['id']) ?>">
                                        <?= e($kargo['takip_no']) ?>
                                    </a>
                                </th>
                                <td><?= e($kargo['alici_adi']) ?></td>
                                <td>
                                    <span class="badge rounded-pill <?= e($durumRenkleri[$kargo['durum']] ?? 'text-bg-light') ?>">
                                        <?= e($kargo['durum']) ?>
                                    </span>
                                </td>
                                <td><?= e($kargo['konum']) ?></td>
                                <td class="text-nowrap"><?= e(date('d.m.Y', strtotime($kargo['teslimat_tarihi']))) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-success text-nowrap" href="/kargo.php?id=<?= e($kargo['id']) ?>">Detayı gör</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
