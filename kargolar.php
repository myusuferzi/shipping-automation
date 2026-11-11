<?php
require_once __DIR__ . '/includes/app.php';
$kullanici = girisGerekli();
$kargolar = kargolariGor($kullanici);
$siralama = ($_GET['sirala'] ?? '') === 'azalan' ? 'azalan' : 'artan';

usort($kargolar, function ($a, $b) use ($siralama) {
    $sonuc = strcmp($a['teslimat_tarihi'], $b['teslimat_tarihi']);
    return $siralama === 'artan' ? $sonuc : -$sonuc;
});

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

    <form class="d-flex align-items-end gap-2 mb-4" method="get">
        <div>
            <label class="form-label" for="sirala">Teslimat tarihine göre</label>
            <select class="form-select" id="sirala" name="sirala">
                <option value="artan" <?= $siralama === 'artan' ? 'selected' : '' ?>>En yakın tarih önce</option>
                <option value="azalan" <?= $siralama === 'azalan' ? 'selected' : '' ?>>En uzak tarih önce</option>
            </select>
        </div>
        <button class="btn btn-outline-secondary" type="submit">Sırala</button>
    </form>

    <?php if (!$kargolar): ?>
        <p class="alert alert-light border">Henüz görüntüleyebileceğiniz bir kargo yok.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Takip numarası</th>
                        <th scope="col">Alıcı</th>
                        <th scope="col">Durum</th>
                        <th scope="col">Konum</th>
                        <th scope="col">Planlanan teslimat</th>
                        <th scope="col">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kargolar as $kargo): ?>
                        <tr>
                            <td><?= e($kargo['takip_no']) ?></td>
                            <td><?= e($kargo['alici_adi']) ?></td>
                            <td><span class="badge text-bg-light border"><?= e($kargo['durum']) ?></span></td>
                            <td><?= e($kargo['konum']) ?></td>
                            <td><?= e($kargo['teslimat_tarihi']) ?></td>
                            <td><a href="/kargo.php?id=<?= e($kargo['id']) ?>">Detay</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
