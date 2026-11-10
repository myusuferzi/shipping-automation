# Kargo Takip ve Yönetim Otomasyonu

Web Programlama dersi kapsamında geliştirilen, kargo süreçlerinin yönetilmesini ve takip edilmesini amaçlayan web uygulaması.

## Proje durumu

Proje başlangıç aşamasındadır. Proje yönergeleri ve yerel Bootstrap dosyaları hazırlanmıştır. Henüz çalışan uygulama, veritabanı şeması veya giriş sistemi bulunmamaktadır.

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
assets/
└── vendor/
    └── bootstrap/    # Bootstrap CSS, JavaScript ve lisans dosyaları
AGENTS.md             # Geliştirme yönergeleri
README.md             # Proje açıklaması
```

## Kurulum

Uygulama iskeleti ve veritabanı şeması oluşturulduğunda PHP/MySQL gereksinimleri, yapılandırma ve çalıştırma adımları bu bölüme eklenecektir.

## Üçüncü taraf yazılımlar

Bootstrap 5.3.8, MIT lisansı altında dağıtılmaktadır. Lisans metni: [assets/vendor/bootstrap/LICENSE](assets/vendor/bootstrap/LICENSE).
