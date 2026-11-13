# İlerleme

- [x] Ortak header/footer, ana sayfa, 404 ve yönlendirme.
- [x] Session içinde örnek veriler ve ayrı veri fonksiyonları.
- [x] Giriş, müşteri kaydı, çıkış ve rol kontrolleri.
- [x] Müşteri kargo listesi, detay ve hareket geçmişi.
- [x] Personelin atanmış kargoları sıralaması ve güncellemesi.
- [x] Yöneticinin kargo oluşturması ve personel ataması.
- [x] Personel hesabı oluşturma, aktifleştirme ve pasife alma.
- [x] MySQL entegrasyonu için fonksiyon ve alan sözleşmesi.
- [x] Kayıt sonrası tek seferlik başarı mesajları ve hatalı kargo formunda değerlerin korunması.
- [x] Kargo listesinde takip numarası/alıcı araması ve durum filtresi.
- [x] Yetkisiz erişim ve geçersiz form gönderimleri için ortak 403 ekranı.
- [x] Yöneticinin personel bilgilerini düzenlemesi ve isteğe bağlı şifre yenilemesi.
- [x] Yöneticinin gönderici/alıcı, müşteri hesabı ve teslimat tarihini düzenlemesi.
- [x] Menüde aktif bölüm vurgusu ve kargo oluşturma alan sınırlarının kontrolü.
- [x] Ayrı JavaScript dosyasında şifre gösterme, şifre tekrarı kontrolü ve personel durum değişikliği onayı.
- [x] 403 ekranını köke taşıma ve 403/404/500 için ortak hata içeriği.
- [ ] Beklenmeyen sunucu hatalarını sade 500 ekranına bağlama.
- [ ] Tarayıcıda ekranların ve mobil görünümün birlikte incelenmesi.
- [x] MySQL tabloları ve veri fonksiyonlarının SQL ile uygulanması (veritabanı tarafı).
- [ ] Gerçek veritabanında çok kullanıcılı akışların test edilmesi.

Arayüz akışları örnek veri aşamasında geliştirilmiştir. Mevcut veri katmanı MySQL bağlantısı gerektirir; sunucunun yerel olması şart değildir. Veri bağlantısı adımları `VERI-BAGLANTISI.md` içindedir.

## MySQL geçişi inceleme bulguları

1. [x] Kurulum dosyaları veritabanı tarafında ele alındı; SQL şeması main dalına `schema.sql` olarak eklendi.
2. [x] Yeni kargo oluştururken seçilen `personel_id` değerini kaydet; personel seçilmediyse `null` tut.
3. [x] Kargo oluşturmadaki teknik hata ayrıntısını kullanıcı mesajından çıkar; kargo işlemlerinin hata ayrıntılarını PHP hata günlüğüne yaz.
4. [x] README'deki örnek veri modu ve MySQL olmadan çalıştırma açıklamalarını mevcut davranışa göre güncelle.

İkinci madde geçici SQLite veritabanıyla kontrol edildi; gerçek MySQL üzerinde doğrulama bekliyor.

SQL şeması main dalından bu dala birleştirildi; `schema.sql` proje kökünde bulunur. Şema tablo yapısını içerir, hesap kaydı eklemez. Gerçek MySQL kurulumu ve ilk yönetici hesabıyla uçtan uca kontrol bekliyor.
