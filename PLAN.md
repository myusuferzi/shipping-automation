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
- [ ] Tarayıcıda ekranların ve mobil görünümün birlikte incelenmesi.
- [ ] MySQL tabloları ve veri fonksiyonlarının SQL ile uygulanması (veritabanı tarafı).
- [ ] Gerçek veritabanında çok kullanıcılı akışların test edilmesi.

Arayüz ve veritabanısız iş akışları bu depoda geliştirilir. MySQL kurulumu bu geliştirme aşaması için gerekli değildir. Veri bağlantısı adımları `VERI-BAGLANTISI.md` içindedir.

## MySQL geçişi inceleme bulguları

1. [ ] Kurulum için SQL şeması, PDO bağlantı örneği ve ilk yönetici hesabı adımlarını tamamla.
2. [x] Yeni kargo oluştururken seçilen `personel_id` değerini kaydet; personel seçilmediyse `null` tut.
3. [x] Kargo oluşturmadaki teknik hata ayrıntısını kullanıcı mesajından çıkar; kargo işlemlerinin hata ayrıntılarını PHP hata günlüğüne yaz.
4. [ ] README'deki örnek veri modu ve MySQL olmadan çalıştırma açıklamalarını mevcut davranışa göre güncelle.

İkinci madde geçici SQLite veritabanıyla kontrol edildi; gerçek MySQL üzerinde doğrulama bekliyor.
