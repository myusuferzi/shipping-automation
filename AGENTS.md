# Proje yönergeleri

## Amaç ve kaynaklar

Bu dizinde **Kargo Takip ve Yönetim Otomasyonu** geliştirilecektir.

Proje kapsamı ve teknik gereksinimler aşağıda özetlenmiştir. Kapsam değişikliklerinde zorunlu gereksinimleri koru.

## Zorunlu gereksinimler

- HTML, CSS, JavaScript ve PHP kullanılmalıdır.
- Veritabanında birbiriyle ilişkili en az dört tablo bulunmalıdır.
- Kullanıcılar tablosu bulunmalıdır.
- Giriş ve kayıt sayfaları ile sayfalar arasında gezinme menüsü olmalıdır.
- Oturum yönetimi PHP session yapısıyla yapılmalıdır.
- Giriş yapmamış ziyaretçiler ekleme, değiştirme ve silme gibi işlemleri yapamamalıdır.
- Bootstrap, jQuery ve Laravel kullanımına izin verilir; bunların kullanılması zorunlu değildir.

## Kullanıcı rolleri ve kapsam

### Yönetici
- Kargo kaydı oluşturur; gönderici ve alıcı bilgilerini girer.
- Kargoları ilgili personele atar.
- Personel hesaplarını yönetir.
- Tüm kargoları ve durumlarını görüntüler, kargo süreçlerini yönetir.

### Kargo personeli
- Kendisine atanmış kargoları listeler ve planlanan teslimat tarihine göre sıralar.
- Atanmış kargoların mevcut konumunu ve durumunu günceller.
- Teslim alma, dağıtıma çıkarma ve müşteriye teslim etme işlemlerini kaydeder.

### Müşteri
- Giriş yaparak kendisine ait kargoları listeler ve detaylarını görüntüler.
- Kargolarının güncel konumunu, durumunu ve işlem geçmişini takip eder.

## Giriş ve hesap oluşturma

- Giriş sayfası yönetici, kargo personeli ve müşteri için ortaktır.
- Başarılı girişten sonra kullanıcı veritabanındaki rolüne göre ilgili sayfaya yönlendirilir. Rol, giriş formundan alınmaz.
- Herkese açık kayıt sayfası yalnızca müşteri hesabı oluşturur. Kullanıcıya rol seçimi sunulmaz; müşteri rolü sunucu tarafında atanır.
- Personel hesaplarını yönetici oluşturur.
- İlk yönetici hesabı kurulum sırasında oluşturulur; herkese açık yönetici kaydı bulunmaz.

## Çalışma yaklaşımı

- Kodu okunabilir, açıklanabilir ve gereksinimlerle orantılı tut.
- Arayüz ve dokümantasyonda uygulamanın işlevlerini anlat.
- Düz PHP ve MySQL kullanılacaktır. Laravel veya başka bir PHP çatısı ekleme.
- Arayüzde Bootstrap kullanılacaktır. Bootstrap dosyalarını proje içinde yerel olarak tut; sayfalarda CDN bağlantısı kullanma.
- Projeye ait CSS ve JavaScript ayrı dosyalarda tutulmalıdır. HTML içine inline stil veya JavaScript yazma.
- Veritabanı bağlantı bilgilerini yerel yapılandırmada tut; örnek yapılandırmalarda gerçek kimlik bilgileri kullanma.
- HTML iç içe bloklarını belirgin girintilerle yaz; CSS kurallarında her özelliği ayrı satırda tut. Ana bölümleri kısa açıklamalar ve boş satırlarla ayır.
- Projede bir yapı oluştuktan sonra mevcut düzeni ve isimlendirme biçimini takip et.
- Özellik eklerken ilgili rolün erişim sınırlarını sunucu tarafında uygula; yalnızca arayüzde düğme gizlemeye güvenme.
- Parolaları güvenli biçimde hashleyerek sakla, veritabanı sorgularında parametreli sorgular kullan ve form işlemlerinde CSRF koruması uygula.
- Gizli bilgileri ve gerçek kullanıcı verilerini kaynak koduna ekleme.
- Değişiklikleri uygun kontrollerle doğrula; çalıştırılamayan kontrolleri açıkça belirt.
- Çalıştırma ve kurulum adımları netleşince bunları `README.md` içinde belgele.

## Veri katmanı ve iş bölümü

- Kullanıcının bilgisayarına MySQL kurulmasını zorunlu tutma; erişilebilir uzak bir MySQL sunucusu da kullanılabilir. Mevcut uygulama çalışan bir MySQL bağlantısı gerektirir.
- Sayfalar veri okumak/yazmak için `data/veri.php` fonksiyonlarını kullanır. Sayfalara SQL veya örnek veri dizileri yerleştirme.
- MySQL bağlantısı ve sorguları ekip arkadaşı tarafından uygulanmıştır. Şema main dalındaki `schema.sql` içindedir. Fonksiyonlar ve dönüş alanları `VERI-BAGLANTISI.md` içinde belgelenir.
- Kullanıcı ve kargo verileri MySQL içinde ortak ve kalıcıdır. Session oturum, CSRF ve bildirim içindir; `data/ornek.php` mevcut akışta yüklenmez.

## Plan ve ilerleme

Bu dosya kalıcı proje yönergeleri içindir. Ayrıntılı görev ve ilerleme takibi gerektiğinde ayrı bir `PLAN.md` kullan; tamamlanan adımları orada güncelle.
