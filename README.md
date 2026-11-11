# Kargo Takip ve Yönetim Otomasyonu

Kargo kayıtlarını, personel atamalarını ve teslimat süreçlerini tek yerden yönetmek için geliştirilen web uygulaması.

## Proje durumu

Müşteri, personel ve yönetici ekranları session'da tutulan örnek verilerle çalışır. Giriş, kayıt, çıkış, kargo listesi/detayı, kargo oluşturma/güncelleme ve personel yönetimi denenebilir. MySQL bağlantısı henüz uygulanmamıştır.

Örnek veriler her tarayıcı oturumunda ayrıdır; başka bir tarayıcıda aynı hesapla giriş yapmak aynı kayıtları paylaşmaz. Oturum verisi silindiğinde değişiklikler sıfırlanır. Yerel örnek hesapların tamamında şifre `Deneme123!`:

| Rol | E-posta |
| --- | --- |
| Yönetici | `yonetici@example.test` |
| Personel | `personel@example.test` |
| Müşteri | `musteri@example.test` |

MySQL iş bölümü ve fonksiyonlar: [Veri bağlantısı](VERI-BAGLANTISI.md). İlerleme: [PLAN.md](PLAN.md).

## Özellikler

- **Yönetici:** Kargo oluşturma, gönderici ve alıcı bilgilerini kaydetme, personele kargo atama ve personel hesaplarını yönetme.
- **Kargo personeli:** Atanmış kargoları teslimat tarihine göre listeleme; konum, durum ve teslimat bilgilerini güncelleme.
- **Müşteri:** Kendi kargolarının detaylarını, güncel durumunu ve işlem geçmişini görüntüleme.
- Kullanıcı kaydı, giriş ve PHP session ile rol tabanlı erişim kontrolü.

## Teknolojiler

- PHP
- MySQL
- HTML, CSS ve JavaScript
- Bootstrap 5.3.8 (yerel dosyalar)

## Dosya yapısı

```text
data/
├── ornek.php         # Örnek hesap ve kayıtlar
└── veri.php          # MySQL geçişinde değiştirilecek fonksiyonlar
kargolar.php          # Role göre kargo listesi
kargo.php             # Detay, hareket geçmişi ve güncelleme
kargo-ekle.php        # Yönetici kargo oluşturma
personeller.php       # Yönetici personel yönetimi
logout.php            # POST ile çıkış
index.php             # Ana sayfa
login.php             # Giriş formu
register.php          # Müşteri kayıt formu
404.php               # Sayfa bulunamadı ekranı
router.php            # Yerel sunucunun sayfa yönlendirmeleri
includes/
├── app.php           # Session, CSRF ve yetki yardımcıları
├── header.php        # Ortak HTML başlangıcı, CSS ve menü
├── footer.php        # Ortak alt bilgi, JavaScript ve kapanışlar
└── gonderi-karti.php # Ana sayfa ve detayın ortak gönderi kartı
assets/
├── css/style.css     # Projeye ait stiller
└── vendor/bootstrap/ # Yerel Bootstrap dosyaları ve lisansı
AGENTS.md             # Geliştirme yönergeleri
README.md             # Proje açıklaması
```

## Kurulum

PHP kuruluysa proje dizininde şu komutla yerel geliştirme sunucusu başlatılabilir:

```bash
php -S localhost:8000 router.php
```

Tarayıcıda `http://localhost:8000` adresini açın. Mevcut örnek veri modu MySQL gerektirmez. PHP 8.0 veya üzeri ve session desteği gerekir. Gerçek MySQL bağlantısı için `VERI-BAGLANTISI.md` dosyasını izleyin.

## Ortak sayfa düzeni

Her sayfa `$sayfaBasligi` değişkenini belirleyip `includes/header.php` dosyasını dahil eder. Sayfaya özgü içerik `<main id="icerik">` içine yazılır; ardından `includes/footer.php` dahil edilir. `$aktifSayfa = 'anasayfa'` değeri ana sayfa menü bağlantısını vurgular.

```php
<?php
$sayfaBasligi = 'Yeni sayfa';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <h1>Yeni sayfa</h1>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
```

Şimdilik sayfa dosyaları proje kökünde tutulur. Ortak dosyaların birleştirilmesi için PHP sunucusu gerekir; dosyaları çift tıklayarak açmak yeterli değildir.

Yerel sunucu `router.php` ile başlatıldığında bulunamayan adresler otomatik olarak 404 ekranını HTTP 404 durum koduyla gösterir. Örneğin `/olmayan-sayfa` veya `/bir/olmayan/sayfa` adreslerini deneyebilirsiniz. Yeni sayfaları `router.php` içindeki listeye ekleyin. Dosya bağlantıları site köküne göre yazılmıştır; geliştirme sunucusunu proje dizininde başlatın. Bu yönlendirme dosyası PHP geliştirme sunucusu içindir; Apache veya Nginx kullanılırsa ayrıca sunucu yapılandırması gerekir.

## Üçüncü taraf yazılımlar

Bootstrap 5.3.8, MIT lisansı altında dağıtılmaktadır. Lisans metni: [assets/vendor/bootstrap/LICENSE](assets/vendor/bootstrap/LICENSE).

## Gönderi kartı

`includes/gonderi-karti.php`, ana sayfada örnek bir gönderiyle, kargo detayında ise erişim kontrolünden geçen kargoyla kullanılır. `$kartKargo` dizisinden durum, konum ve planlanan teslimat tarihi okunur; örnek olmayan kartta takip numarası da gösterilir. `$kartOrnek`, örnek etiketinin gösterilip gösterilmeyeceğini belirler.

Kartın görünümü ve CSS animasyonları `assets/css/gonderi-karti.css` içindedir. Ana sayfadaki örnek sabittir. Detay açıldığında bar kayıtlı aşamaya kadar bir kez dolar ve kutu örnekteki konumuna yerleşir. Durum değişikliği kaydedilince kart yeni durumu gösterir. Animasyon veri güncellemez; hareket azaltma tercihinde kapalıdır.
