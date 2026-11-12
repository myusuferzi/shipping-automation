<!-- Kargo oluşturma ve düzenleme sayfalarının ortak alanları. -->
<div class="col-md-6">
    <label class="form-label" for="musteri">Kargoyu takip edecek müşteri hesabı</label>
    <select class="form-select" id="musteri" name="musteri_id" required>
        <option value="">Müşteri seçin</option>
        <?php foreach ($musteriler as $musteri): ?>
            <option value="<?= e($musteri['id']) ?>" <?= (string) $formBilgileri['musteri_id'] === (string) $musteri['id'] ? 'selected' : '' ?>><?= e($musteri['ad_soyad']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-6">
    <label class="form-label" for="gonderici-adi">Gönderici adı</label>
    <input class="form-control" id="gonderici-adi" name="gonderici_adi" value="<?= e($formBilgileri['gonderici_adi']) ?>" maxlength="120" required>
</div>
<div class="col-md-6">
    <label class="form-label" for="alici-adi">Alıcı adı</label>
    <input class="form-control" id="alici-adi" name="alici_adi" value="<?= e($formBilgileri['alici_adi']) ?>" maxlength="120" required>
</div>
<div class="col-md-6">
    <label class="form-label" for="gonderici-adres">Gönderici adresi</label>
    <textarea class="form-control" id="gonderici-adres" name="gonderici_adres" rows="3" maxlength="500" required><?= e($formBilgileri['gonderici_adres']) ?></textarea>
</div>
<div class="col-md-6">
    <label class="form-label" for="alici-adres">Alıcı adresi</label>
    <textarea class="form-control" id="alici-adres" name="alici_adres" rows="3" maxlength="500" required><?= e($formBilgileri['alici_adres']) ?></textarea>
</div>
<div class="col-md-6">
    <label class="form-label" for="teslimat">Planlanan teslimat tarihi</label>
    <input class="form-control" type="date" id="teslimat" name="teslimat_tarihi" value="<?= e($formBilgileri['teslimat_tarihi']) ?>" required>
</div>
