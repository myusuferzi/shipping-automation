# Kargo Takip ve Yönetim Otomasyonu

Web Programlama dersi kapsamında geliştirilen, kargo süreçlerinin yönetilmesini ve takip edilmesini amaçlayan web uygulaması.

## Proje durumu

Proje başlangıç aşamasındadır. Yerel Bootstrap kullanan, mobil uyumlu bir ana sayfa taslağı hazırlanmıştır. Bu taslak ekip değerlendirmesi için bir başlangıçtır. Veritabanı şeması, giriş sistemi ve kargo işlemleri henüz geliştirilmemiştir.

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
assets/
├── css/style.css     # Projeye ait stiller
└── vendor/bootstrap/ # Yerel Bootstrap dosyaları ve lisansı
AGENTS.md             # Geliştirme yönergeleri
README.md             # Proje açıklaması
```

## Kurulum

PHP kuruluysa proje dizininde şu komutla yerel geliştirme sunucusu başlatılabilir:

```bash
php -S localhost:8000
```

Tarayıcıda `http://localhost:8000` adresini açın. Mevcut ana sayfa veritabanı gerektirmez. MySQL yapılandırması ve şema kurulum adımları ilgili özellikler geliştirildiğinde eklenecektir.

## Üçüncü taraf yazılımlar

Bootstrap 5.3.8, MIT lisansı altında dağıtılmaktadır. Lisans metni: [assets/vendor/bootstrap/LICENSE](assets/vendor/bootstrap/LICENSE).
