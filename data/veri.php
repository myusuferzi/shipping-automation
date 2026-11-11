<?php
// Şimdilik tüm veriler bu tarayıcının PHP session'ında tutulur.
// MySQL bağlantısında değiştirilecek fonksiyonlar bu dosyadadır.
require_once __DIR__ . '/ornek.php';

if (!isset($_SESSION['veriler'])) {
    $_SESSION['veriler'] = ornekVeriler();
}

function kullanicilariGetir()
{
    return array_values($_SESSION['veriler']['kullanicilar']);
}

function kullaniciGetir($id)
{
    return $_SESSION['veriler']['kullanicilar'][$id] ?? null;
}

function epostaIleKullaniciGetir($eposta)
{
    foreach (kullanicilariGetir() as $kullanici) {
        if (strtolower($kullanici['eposta']) === strtolower($eposta)) {
            return $kullanici;
        }
    }

    return null;
}

function kullaniciEkle($ad, $eposta, $sifreHash, $rol)
{
    if (epostaIleKullaniciGetir($eposta)) {
        throw new RuntimeException('Bu e-posta adresi zaten kayıtlı.');
    }

    $id = max(array_keys($_SESSION['veriler']['kullanicilar'])) + 1;
    $_SESSION['veriler']['kullanicilar'][$id] = [
        'id' => $id,
        'ad_soyad' => $ad,
        'eposta' => strtolower($eposta),
        'sifre_hash' => $sifreHash,
        'rol' => $rol,
        'aktif' => true,
    ];

    return $id;
}

function personelAktifliginiDegistir($id)
{
    $personel = kullaniciGetir($id);
    if (!$personel || $personel['rol'] !== 'personel') {
        throw new RuntimeException('Personel bulunamadı.');
    }

    if ($personel['aktif']) {
        foreach (kargolariGetir() as $kargo) {
            if ($kargo['personel_id'] === $id && $kargo['durum'] !== 'Teslim edildi') {
                throw new RuntimeException('Önce personelin devam eden kargolarını başka personele atayın.');
            }
        }
    }

    $_SESSION['veriler']['kullanicilar'][$id]['aktif'] = !$personel['aktif'];
}

function kargolariGetir()
{
    return array_values($_SESSION['veriler']['kargolar']);
}

function kargoGetir($id)
{
    return $_SESSION['veriler']['kargolar'][$id] ?? null;
}

function kargoEkle($bilgiler, $islemiYapan)
{
    $id = max(array_keys($_SESSION['veriler']['kargolar'])) + 1;
    $kargo = $bilgiler;
    $kargo['id'] = $id;
    $kargo['takip_no'] = 'KRG' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    $kargo['durum'] = 'Kabul edildi';
    $_SESSION['veriler']['kargolar'][$id] = $kargo;
    $_SESSION['veriler']['hareketler'][$id] = [];
    hareketEkle($id, 'Kabul edildi', $kargo['konum'], 'Kargo kaydı oluşturuldu.', $islemiYapan);

    return $id;
}

function hareketEkle($id, $durum, $konum, $aciklama, $kullaniciId)
{
    $_SESSION['veriler']['hareketler'][$id][] = [
        'tarih' => date('Y-m-d H:i:s'),
        'durum' => $durum,
        'konum' => $konum,
        'aciklama' => $aciklama,
        'kullanici_id' => $kullaniciId,
    ];
}

function kargoGuncelle($id, $durum, $konum, $personelId, $aciklama, $kullaniciId)
{
    $_SESSION['veriler']['kargolar'][$id]['durum'] = $durum;
    $_SESSION['veriler']['kargolar'][$id]['konum'] = $konum;
    $_SESSION['veriler']['kargolar'][$id]['personel_id'] = $personelId;
    hareketEkle($id, $durum, $konum, $aciklama, $kullaniciId);
}

function kargoHareketleriGetir($id)
{
    return array_reverse($_SESSION['veriler']['hareketler'][$id] ?? []);
}
