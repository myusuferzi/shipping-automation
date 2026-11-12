<?php
// Veritabanı bağlantısı dahil edildi. (ornek.php ve $_SESSION tamamen kaldırıldı)
require_once __DIR__ . '/../config/database.local.php';


// 1. KULLANICI FONKSİYONLARI
   
function kullanicilariGetir() {
    global $db;
    $sorgu = $db->query("SELECT * FROM kullanicilar");
    $kullanicilar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($kullanicilar as &$k) {
        $k['id'] = (int)$k['id'];
        $k['aktif'] = (bool)$k['aktif'];
    }
    return $kullanicilar;
}

function kullaniciGetir($id) {
    global $db;
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $sorgu->execute([$id]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);
    
    if ($kullanici) {
        $kullanici['id'] = (int)$kullanici['id'];
        $kullanici['aktif'] = (bool)$kullanici['aktif'];
        return $kullanici;
    }
    return null;
}

function epostaIleKullaniciGetir($eposta) {
    global $db;
    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE eposta = ?");
    $sorgu->execute([$eposta]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);
    
    if ($kullanici) {
        $kullanici['id'] = (int)$kullanici['id'];
        $kullanici['aktif'] = (bool)$kullanici['aktif'];
        return $kullanici;
    }
    return null;
}

function kullaniciEkle($ad, $eposta, $sifreHash, $rol) {
    global $db;
    if (epostaIleKullaniciGetir($eposta)) {
        throw new RuntimeException('Bu e-posta adresi zaten kayıtlı.');
    }
    
    $sorgu = $db->prepare("INSERT INTO kullanicilar (ad_soyad, eposta, sifre_hash, rol, aktif) VALUES (?, ?, ?, ?, 1)");
    $sorgu->execute([$ad, $eposta, $sifreHash, $rol]);
    
    return (int)$db->lastInsertId();
}

function personelGuncelle($id, $ad, $eposta, $sifreHash = null) {
    global $db;
    $personel = kullaniciGetir($id);
    
    if (!$personel || $personel['rol'] !== 'personel') {
        throw new RuntimeException('Personel bulunamadı.');
    }

    $digerHesap = epostaIleKullaniciGetir($eposta);
    if ($digerHesap && $digerHesap['id'] !== $id) {
        throw new RuntimeException('Bu e-posta adresi zaten kayıtlı.');
    }

    if ($sifreHash !== null) {
        $sorgu = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, eposta = ?, sifre_hash = ? WHERE id = ?");
        $sorgu->execute([$ad, $eposta, $sifreHash, $id]);
    } else {
        $sorgu = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, eposta = ? WHERE id = ?");
        $sorgu->execute([$ad, $eposta, $id]);
    }
}

function personelAktifliginiDegistir($id) {
    global $db;
    $personel = kullaniciGetir($id);
    
    if (!$personel || $personel['rol'] !== 'personel') {
        throw new RuntimeException('Personel bulunamadı.');
    }

    if ($personel['aktif']) {
        $sorgu = $db->prepare("SELECT COUNT(*) FROM kargolar WHERE personel_id = ? AND durum != 'Teslim edildi'");
        $sorgu->execute([$id]);
        if ($sorgu->fetchColumn() > 0) {
            throw new RuntimeException('Önce personelin devam eden kargolarını başka personele atayın.');
        }
    }

    $yeniDurum = $personel['aktif'] ? 0 : 1;
    $sorgu = $db->prepare("UPDATE kullanicilar SET aktif = ? WHERE id = ?");
    $sorgu->execute([$yeniDurum, $id]);
}


  // 2. KARGO VE HAREKET FONKSİYONLARI
  

function kargolariGetir() {
    global $db;
    // Adresler tablosu ile JOIN yaparak gönderici ve alıcı adres metinlerini çekilir.
    $sql = "SELECT k.*, a1.adres_metni AS gonderici_adres, a2.adres_metni AS alici_adres 
            FROM kargolar k 
            JOIN adresler a1 ON k.gonderici_adres_id = a1.id 
            JOIN adresler a2 ON k.alici_adres_id = a2.id";
            
    $kargolar = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($kargolar as &$k) {
        $k['id'] = (int)$k['id'];
        $k['musteri_id'] = (int)$k['musteri_id'];
        $k['personel_id'] = $k['personel_id'] !== null ? (int)$k['personel_id'] : null;
    }
    return $kargolar;
}

function kargoGetir($id) {
    global $db;
    $sql = "SELECT k.*, a1.adres_metni AS gonderici_adres, a2.adres_metni AS alici_adres 
            FROM kargolar k 
            JOIN adresler a1 ON k.gonderici_adres_id = a1.id 
            JOIN adresler a2 ON k.alici_adres_id = a2.id 
            WHERE k.id = ?";
            
    $sorgu = $db->prepare($sql);
    $sorgu->execute([$id]);
    $kargo = $sorgu->fetch(PDO::FETCH_ASSOC);
    
    if ($kargo) {
        $kargo['id'] = (int)$kargo['id'];
        $kargo['musteri_id'] = (int)$kargo['musteri_id'];
        $kargo['personel_id'] = $kargo['personel_id'] !== null ? (int)$kargo['personel_id'] : null;
        return $kargo;
    }
    return null;
}

function kargoEkle($bilgiler, $islemiYapan) {
    global $db;
    
    try {
        $db->beginTransaction(); // Transaction Başlangıcı
        
        // 1. Gönderici Adresini Ekle
        $sorguAdres = $db->prepare("INSERT INTO adresler (adres_metni) VALUES (?)");
        $sorguAdres->execute([$bilgiler['gonderici_adres']]);
        $gondericiAdresId = $db->lastInsertId();
        
        // 2. Alıcı Adresini Ekle
        $sorguAdres->execute([$bilgiler['alici_adres']]);
        $aliciAdresId = $db->lastInsertId();
        
        // 3. Kargoyu Ekle (Takip numarasını ID'ye göre oluşturmak için önce geçici bir takip no verilir)
        $geciciTakip = 'TMP_' . uniqid();
        $sorguKargo = $db->prepare("INSERT INTO kargolar (takip_no, musteri_id, gonderici_adi, gonderici_adres_id, alici_adi, alici_adres_id, teslimat_tarihi, konum, durum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Kabul edildi')");
        $sorguKargo->execute([
            $geciciTakip, $bilgiler['musteri_id'], $bilgiler['gonderici_adi'], $gondericiAdresId, 
            $bilgiler['alici_adi'], $aliciAdresId, $bilgiler['teslimat_tarihi'], $bilgiler['konum']
        ]);
        
        $kargoId = $db->lastInsertId();
        
        // 4. Takip Numarasını Güncelle (KRG + 6 Haneli ID)
        $takipNo = 'KRG' . str_pad($kargoId, 6, '0', STR_PAD_LEFT);
        $db->prepare("UPDATE kargolar SET takip_no = ? WHERE id = ?")->execute([$takipNo, $kargoId]);
        
        // 5. İlk Hareketi Ekle
        hareketEkleIsTavri($db, $kargoId, 'Kabul edildi', $bilgiler['konum'], 'Kargo kaydı oluşturuldu.', $islemiYapan);
        
        $db->commit(); // Hata yoksa işlemi onayla
        return (int)$kargoId;
        
    } catch (Exception $e) {
        $db->rollBack(); // Hata varsa hiçbirini kaydetme, geri al
        throw new RuntimeException("Kargo eklenirken bir hata oluştu: " . $e->getMessage());
    }
}

function hareketEkle($id, $durum, $konum, $aciklama, $kullaniciId) {
    global $db;
    hareketEkleIsTavri($db, $id, $durum, $konum, $aciklama, $kullaniciId);
}

// Transaction içinden çağrılabilmesi için PDO nesnesini parametre olarak alan yardımcı fonksiyon
function hareketEkleIsTavri($db, $id, $durum, $konum, $aciklama, $kullaniciId) {
    $sorgu = $db->prepare("INSERT INTO kargo_hareketleri (kargo_id, tarih, durum, konum, aciklama, kullanici_id) VALUES (?, NOW(), ?, ?, ?, ?)");
    $sorgu->execute([$id, $durum, $konum, $aciklama, $kullaniciId]);
}

function kargoGuncelle($id, $durum, $konum, $personelId, $aciklama, $kullaniciId) {
    global $db;
    
    try {
        $db->beginTransaction();
        
        $sorgu = $db->prepare("UPDATE kargolar SET durum = ?, konum = ?, personel_id = ? WHERE id = ?");
        $sorgu->execute([$durum, $konum, $personelId, $id]);
        
        hareketEkleIsTavri($db, $id, $durum, $konum, $aciklama, $kullaniciId);
        
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw new RuntimeException("Kargo güncellenirken bir hata oluştu.");
    }
}

function kargoBilgileriniGuncelle($id, $bilgiler, $kullaniciId) {
    global $db;
    $kargo = kargoGetir($id);
    
    if (!$kargo) {
        throw new RuntimeException('Kargo bulunamadı.');
    }

    try {
        $db->beginTransaction();
        
        // Adresleri günceller(ID'leri join'den aldınan anahtar ile kullanılır)
        $sorguAdres = $db->prepare("UPDATE adresler SET adres_metni = ? WHERE id = ?");
        $sorguAdres->execute([$bilgiler['gonderici_adres'], $kargo['gonderici_adres_id']]);
        $sorguAdres->execute([$bilgiler['alici_adres'], $kargo['alici_adres_id']]);
        
        // Kargo bilgilerini günceller
        $sorguKargo = $db->prepare("UPDATE kargolar SET musteri_id = ?, gonderici_adi = ?, alici_adi = ?, teslimat_tarihi = ? WHERE id = ?");
        $sorguKargo->execute([
            $bilgiler['musteri_id'], $bilgiler['gonderici_adi'], 
            $bilgiler['alici_adi'], $bilgiler['teslimat_tarihi'], $id
        ]);
        
        // Hareket eklenir
        hareketEkleIsTavri($db, $id, $kargo['durum'], $kargo['konum'], 'Gönderi bilgileri düzenlendi.', $kullaniciId);
        
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw new RuntimeException("Kargo bilgileri güncellenirken bir hata oluştu.");
    }
}

function kargoHareketleriGetir($id) {
    global $db;
    $sorgu = $db->prepare("SELECT * FROM kargo_hareketleri WHERE kargo_id = ? ORDER BY tarih DESC, id DESC");
    $sorgu->execute([$id]);
    
    $hareketler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($hareketler as &$h) {
        $h['id'] = (int)$h['id'];
        $h['kargo_id'] = (int)$h['kargo_id'];
        $h['kullanici_id'] = (int)$h['kullanici_id'];
    }
    
    return $hareketler;
}

?>