# Kargo Takip ve Yönetim Otomasyonu

Kargo kayıtlarını, personel atamalarını ve teslimat süreçlerini tek yerden yönetmek için geliştirilen web uygulaması.

## Proje durumu

Ortak sayfa düzeni, ana sayfa ve 404 ekranı hazırdır. Giriş ve kayıt formlarının arayüzleri hazırdır; gönderim butonları henüz etkin değildir. Hesap verisi kaydedilmez ve oturum açılmaz. Veritabanı şeması, giriş sistemi ve kargo işlemleri henüz geliştirilmemiştir.

## Planlanan özellikler

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
index.php             # Ana sayfa taslağı
login.php             # Giriş formu
register.php          # Müşteri kayıt formu
404.php               # Sayfa bulunamadı ekranı
router.php            # Yerel sunucunun sayfa yönlendirmeleri
includes/
├── header.php        # Ortak HTML başlangıcı, CSS ve menü
└── footer.php        # Ortak alt bilgi, JavaScript ve kapanışlar
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

Tarayıcıda `http://localhost:8000` adresini açın. Mevcut ana sayfa veritabanı gerektirmez. MySQL yapılandırması ve şema kurulum adımları ilgili özellikler geliştirildiğinde eklenecektir.

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
