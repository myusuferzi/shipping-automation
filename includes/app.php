<?php
// Ortak başlangıç: oturum, veri fonksiyonları ve form yardımcıları.
date_default_timezone_set('Europe/Istanbul');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'use_strict_mode' => true,
    ]);
}

require_once __DIR__ . '/../data/veri.php';

function e($metin)
{
    return htmlspecialchars((string) $metin, ENT_QUOTES, 'UTF-8');
}

function postMetni($alan)
{
    return isset($_POST[$alan]) && is_string($_POST[$alan]) ? trim($_POST[$alan]) : '';
}

function yonlendir($adres)
{
    header('Location: ' . $adres);
    exit;
}

// Yönlendirmeden sonra yalnızca bir kez gösterilecek başarı mesajı.
function bildirimEkle($mesaj)
{
    $_SESSION['bildirim'] = $mesaj;
}

function mevcutKullanici()
{
    return kullaniciGetir($_SESSION['kullanici_id'] ?? 0);
}

// Hem rol kontrolü hem de geçersiz form gönderimi aynı ekranı kullanır.
function erisimEngelle($mesaj)
{
    http_response_code(403);
    require __DIR__ . '/erisim-engeli.php';
    exit;
}

function girisGerekli($roller = [])
{
    $kullanici = mevcutKullanici();

    if (!$kullanici || !$kullanici['aktif']) {
        unset($_SESSION['kullanici_id']);
        yonlendir('/login.php');
    }

    if ($roller && !in_array($kullanici['rol'], $roller, true)) {
        erisimEngelle('Bu sayfayı görüntülemek veya işlemi yapmak için yetkiniz bulunmuyor.');
    }

    return $kullanici;
}

function csrfToken()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function csrfKontrol()
{
    if (!hash_equals(csrfToken(), postMetni('csrf'))) {
        erisimEngelle('Form doğrulanamadı veya oturumun süresi dolmuş olabilir. İlgili sayfayı yeniden açıp formu tekrar gönderin.');
    }
}

function kargolariGor($kullanici)
{
    return array_values(array_filter(kargolariGetir(), function ($kargo) use ($kullanici) {
        return $kullanici['rol'] === 'yonetici'
            || ($kullanici['rol'] === 'personel' && $kargo['personel_id'] === $kullanici['id'])
            || ($kullanici['rol'] === 'musteri' && $kargo['musteri_id'] === $kullanici['id']);
    }));
}

function kargoDurumlari()
{
    return ['Kabul edildi', 'Transferde', 'Dağıtımda', 'Teslim edildi'];
}
