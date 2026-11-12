<?php
// Kullanım: $kartKargo dizisini ve $kartOrnek değerini belirleyip bu dosyayı dahil edin.
$kartAsamalar = kargoDurumlari();
$kartAsamaAdlari = ['Kabul', 'Transfer', 'Dağıtım', 'Teslimat'];
$kartAktifAsama = array_search($kartKargo['durum'], $kartAsamalar, true);
// Ana sayfadaki örnek sabittir; yalnızca detay kartı animasyon oynatır.
$kartSinifi = $kartOrnek ? 'shipment-example' : 'shipment-tracking stage-' . $kartAktifAsama;
?>

<section class="shipment-preview <?= e($kartSinifi) ?>" aria-label="Gönderi yolculuğu">
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
        <span class="fw-semibold">Gönderi yolculuğu</span>
        <span class="badge rounded-pill preview-label">
            <?= $kartOrnek ? 'Örnek görünüm' : e($kartKargo['takip_no']) ?>
        </span>
    </div>

    <div class="parcel-illustration" aria-hidden="true">
        <span>↑ ↑</span>
        <strong>K.</strong>
    </div>

    <?php if ($kartOrnek): ?>
        <p class="text-center fw-semibold mt-4 mb-2">Her adımda güncel bilgi</p>
        <p class="text-center text-secondary small mb-0">Kabulden teslimata kadar tüm hareketler.</p>
        <div class="preview-stages mt-4" aria-hidden="true">
            <span>Kabul</span>
            <span>Transfer</span>
            <span>Dağıtım</span>
            <span>Teslimat</span>
        </div>
    <?php else: ?>
        <p class="text-center fw-semibold mt-4 mb-2"><?= e($kartKargo['durum']) ?></p>
        <p class="text-center text-secondary small mb-0"><?= e($kartKargo['konum']) ?></p>
        <p class="text-center text-secondary small mt-2 mb-0">
            Planlanan teslimat: <?= e($kartKargo['teslimat_tarihi']) ?>
        </p>

        <ol class="preview-stages mt-4" aria-label="Teslimat aşamaları">
            <?php foreach ($kartAsamalar as $kartSira => $kartAsama): ?>
                <?php
                $kartAsamaSinifi = '';
                if ($kartAktifAsama !== false && $kartSira < $kartAktifAsama) {
                    $kartAsamaSinifi = 'stage-complete';
                } elseif ($kartSira === $kartAktifAsama) {
                    $kartAsamaSinifi = 'stage-current';
                }
                ?>
                <li class="<?= e($kartAsamaSinifi) ?>" <?= $kartSira === $kartAktifAsama ? 'aria-current="step"' : '' ?>>
                    <span class="stage-dot" aria-hidden="true"></span>
                    <?= e($kartAsamaAdlari[$kartSira]) ?>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>
