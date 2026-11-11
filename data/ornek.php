<?php
function ornekVeriler()
{
    // Bu hesaplar yalnızca yerel deneme içindir.
    $hash = password_hash('Deneme123!', PASSWORD_DEFAULT);
    $kullanicilar = [];
    $hesaplar = [
        [1, 'Örnek Yönetici', 'yonetici@example.test', 'yonetici'],
        [2, 'Örnek Personel', 'personel@example.test', 'personel'],
        [3, 'Örnek Müşteri', 'musteri@example.test', 'musteri'],
        [4, 'Diğer Müşteri', 'diger@example.test', 'musteri'],
    ];

    foreach ($hesaplar as [$id, $ad, $eposta, $rol]) {
        $kullanicilar[$id] = [
            'id' => $id,
            'ad_soyad' => $ad,
            'eposta' => $eposta,
            'sifre_hash' => $hash,
            'rol' => $rol,
            'aktif' => true,
        ];
    }

    $kargolar = [];
    $hareketler = [];
    foreach ([1, 2, 3] as $id) {
        $durum = $id === 1 ? 'Dağıtımda' : 'Kabul edildi';
        $kargolar[$id] = [
            'id' => $id,
            'takip_no' => 'KRG00000' . $id,
            'musteri_id' => $id === 3 ? 4 : 3,
            'personel_id' => $id === 3 ? null : 2,
            'gonderici_adi' => 'Örnek Gönderici',
            'gonderici_adres' => 'Örnek Mahallesi, Ankara',
            'alici_adi' => $id === 3 ? 'Diğer Müşteri' : 'Örnek Müşteri',
            'alici_adres' => 'Deneme Mahallesi, İstanbul',
            'teslimat_tarihi' => date('Y-m-d', strtotime('+' . $id . ' days')),
            'konum' => 'İstanbul Şubesi',
            'durum' => $durum,
        ];
        $hareketler[$id] = [[
            'tarih' => date('Y-m-d H:i:s'),
            'durum' => $durum,
            'konum' => 'İstanbul Şubesi',
            'aciklama' => 'Örnek hareket kaydı.',
            'kullanici_id' => 1,
        ]];
    }

    return ['kullanicilar' => $kullanicilar, 'kargolar' => $kargolar, 'hareketler' => $hareketler];
}
