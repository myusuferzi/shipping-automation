# Kargo Takip ve Yönetim Otomasyonu

Kargo kayıtlarını, personel atamalarını ve teslimat süreçlerini tek yerden yönetmek için geliştirilen web uygulaması.

## Genel yapı

Müşteri, personel ve yönetici ekranları MySQL veri katmanını kullanır. Giriş, kayıt, çıkış, kargo işlemleri ve personel yönetimi için çalışan bir veritabanı bağlantısı gerekir. Session yalnızca oturum, form doğrulama ve bildirimler için kullanılır.

## Özellikler

- **Yönetici:** Kargo oluşturma, gönderici ve alıcı bilgilerini kaydetme, personele kargo atama ve personel hesaplarını yönetme.
- **Kargo personeli:** Atanmış kargoları teslimat tarihine göre listeleme; konum, durum ve teslimat bilgilerini güncelleme.
- **Müşteri:** Kendi kargolarının detaylarını, güncel durumunu ve işlem geçmişini görüntüleme.
- Kargo listesinde özet kartları, arama, durum filtresi ve sayfalama; geciken teslimatların gösterimi.
- Kargo listesi ve detayında bağlantıyı kopyalama.
- Kullanıcı kaydı, giriş ve PHP session ile rol tabanlı erişim kontrolü.
- Üst menüden değiştirilebilen açık/karanlık tema. İlk açılışta sistem tercihi kullanılır; seçim tarayıcıda saklanır.

## Teknolojiler

- PHP
- MySQL
- HTML, CSS ve JavaScript
- Bootstrap 5.3.8 (yerel dosyalar)

## Dosya yapısı

```text
config/
├── database.example.php # Bağlantı ayarlarının paylaşılabilir örneği
└── database.local.php   # Yerel PDO bağlantısı; Git dışında tutulur
data/
└── veri.php          # PDO ile MySQL sorguları
kargolar.php          # Role göre kargo listesi
kargo.php             # Detay, hareket geçmişi ve güncelleme
kargo-ekle.php        # Yönetici kargo oluşturma
kargo-duzenle.php     # Yönetici gönderi bilgilerini düzenleme
personeller.php       # Yönetici personel yönetimi
personel-duzenle.php  # Personel bilgisi ve isteğe bağlı şifre güncelleme
logout.php            # POST ile çıkış
index.php             # Ana sayfa
login.php             # Giriş formu
register.php          # Müşteri kayıt formu
403.php               # Yetkisiz işlem ekranı
404.php               # Sayfa bulunamadı ekranı
500.php               # Bağımsız sunucu hatası ekranı
router.php            # Yerel sunucunun sayfa yönlendirmeleri
includes/
├── app.php           # Session, CSRF ve yetki yardımcıları
├── header.php        # Ortak HTML başlangıcı, CSS ve menü
├── footer.php        # Ortak alt bilgi, JavaScript ve kapanışlar
├── gonderi-karti.php # Ana sayfa ve detayın ortak gönderi kartı
├── kargo-alanlari.php # Kargo oluşturma ve düzenlemenin ortak form alanları
├── kopyala-dugmesi.php # Kargo bağlantısını kopyalama düğmesi
├── tema-dugmesi.php   # Güneş/ay simgeli tema anahtarı
└── hata-icerigi.php  # 403, 404 ve 500 ekranlarının ortak içeriği
assets/
├── css/style.css     # Projeye ait stiller
├── css/gonderi-karti.css # Gönderi kartı ve animasyonları
├── js/app.js         # Şifre alanları ve işlem onayı
├── js/tema.js        # Tema seçimi ve tarayıcıda saklanması
└── vendor/bootstrap/ # Yerel Bootstrap dosyaları ve lisansı
schema.sql            # Veritabanı tablo yapısı
```

## Kurulum

### Gereksinimler

- PHP 8.0 veya üzeri, session, PDO ve `pdo_mysql` desteği.
- Erişilebilir bir MySQL sunucusu ve uygulamanın tabloları.
- `config/database.local.php` içinde uygulamanın kullanacağı `$db` PDO bağlantısı.

MySQL sunucusu PHP'nin çalıştığı bilgisayarda olmak zorunda değildir. Uzak bir sunucuya bağlanılacaksa bağlantı ve erişim bilgileri yerel yapılandırmada belirtilmelidir.

### Veritabanı hazırlığı

Proje kökündeki `schema.sql`, `shipping_automation` veritabanının dört tablo yapısını içerir. phpMyAdmin'de `shipping_automation` veritabanını oluşturup seçin ve İçe aktar bölümünden `schema.sql` dosyasını aktarın.

SQL dosyası kullanıcı kaydı içermez. İlk yönetici hesabını veritabanı tarafının belirlediği kurulum yöntemiyle hazırlayın; kayıt sayfası yalnızca müşteri hesabı oluşturur. Şifre düz metin olarak saklanmamalı, PHP'nin `password_hash()` fonksiyonuyla üretilen hash kullanılmalıdır.

`config/database.example.php` bağlantı değişkenlerini gösterir; mevcut örnek tek başına `$db` nesnesini oluşturmaz. `config/database.local.php` dosyasında bağlantı bilgileri ve çalışan PDO bağlantısı bulunmalıdır. Bu yerel dosya Git'e eklenmez.

### PHP geliştirme sunucusu

Veritabanı hazırlığı ve bağlantısı tamamlandıktan sonra proje dizininde:

```bash
php -S localhost:8000 router.php
```

Tarayıcıda `http://localhost:8000` adresini açın. Bu komut yalnızca PHP sunucusunu başlatır; MySQL'i başlatmaz veya tabloları oluşturmaz. Bağlantı dosyası yoksa uygulama açılmaz.

### AppServ / Apache

Apache ve MySQL çalışır durumda olmalı, yerel PDO bağlantısı hazırlanmalıdır. Mevcut bağlantılar `/assets/` ve `/login.php` gibi site köküne göre yazılmıştır; Apache'nin site kökü bu proje dizinini göstermelidir. Projeyi doğrudan `/kargo/` gibi bir alt klasörden açmak mevcut yollarla desteklenmez.

`router.php` PHP geliştirme sunucusuna özeldir; Apache bu dosyayı kendiliğinden çalıştırmaz. Özel 404 davranışı için Apache yapılandırması gerekir.

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

## Hata ekranları

403, 404 ve 500 ekranları `includes/hata-icerigi.php` şablonunu kullanır. Kod, başlık ve açıklama sayfalarda belirlenir. 403 ve 404 ortak menüyü kullanır; 500, veritabanından bağımsız bir başlangıçla açılır.

Ortak başlangıç dosyası yüklendikten sonra yakalanmamış PHP hataları ve istisnalarında 500 ekranı HTTP 500 durum koduyla gösterilir. Teknik ayrıntılar PHP hata günlüğüne yazılır; yarım kalan sayfa içeriği temizlenir. Veritabanı bağlantı hataları da bu kapsamdadır. Ekran ayrıca `/500.php` adresinden incelenebilir.
