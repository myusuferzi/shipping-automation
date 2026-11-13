# Veri bağlantısı

## İş bölümü

Sayfalar, veri okumak ve yazmak için `data/veri.php` içindeki PDO sorgularını kullanır. Session yalnızca oturum, CSRF ve bildirim bilgilerini tutar; kargo ve kullanıcı kayıtları MySQL'dedir.

`data/ornek.php` eski örnek veri dosyasıdır ve mevcut uygulamada yüklenmez. Başka bir tarayıcı yeni bir örnek veri kümesi oluşturmaz; aynı MySQL sunucusuna bağlanan oturumlar ortak kayıtları kullanır.

## Kurulum ve bakım

- Veritabanı şeması proje kökündeki `schema.sql` dosyasıdır. Dört tablo içerir: `kullanicilar`, `adresler`, `kargolar`, `kargo_hareketleri`. Kullanıcı kayıtları içermez.
- `config/database.local.php` yerel bağlantı bilgilerini ve `$db` PDO nesnesini sağlar; Git dışında tutulur. `config/database.example.php` bağlantı değişkenlerini gösterir; PDO nesnesi yerel dosyada hazırlanmalıdır.
- İlk yönetici hesabı veritabanı tarafının belirlediği kurulum yöntemiyle oluşturulur. Şifre hash olarak saklanır; herkese açık yönetici kaydı bulunmaz.
- Kargo oluşturma/güncelleme ile hareket ekleme aynı transaction içinde kalmalıdır.
- Beklenen iş hataları kullanıcıya anlaşılır mesajlarla bildirilir. Kargo işlemlerindeki teknik hata ayrıntıları PHP hata günlüğüne yazılır.
- Fonksiyonların aşağıdaki dönüş alanları sayfalarla olan sözleşmedir; veritabanı değişirken bu alanlar korunmalıdır.

## Fonksiyonlar

| Fonksiyon | Dönüş / davranış |
| --- | --- |
| `kullanicilariGetir()` | Kullanıcı dizilerinden oluşan liste. |
| `kullaniciGetir($id)` | Kullanıcı dizisi veya `null`. |
| `epostaIleKullaniciGetir($eposta)` | Büyük/küçük harften bağımsız kullanıcı veya `null`. |
| `kullaniciEkle($ad, $eposta, $sifreHash, $rol)` | Yeni kullanıcı ID'si. Şifre zaten hashlenmiş gelir. Yinelenen e-postada hata. |
| `personelGuncelle($id, $ad, $eposta, $sifreHash = null)` | Yalnızca personelin adını/e-postasını değiştirir. Hash null ise mevcut şifreyi korur. Rol ve aktiflik değişmez. Başkasına ait e-postada hata; tüm değişiklikler birlikte uygulanır. |
| `personelAktifliginiDegistir($id)` | Personeli aktif/pasif yapar. Teslim edilmemiş kargosu varsa pasife almayı reddeder. |
| `kargolariGetir()` | Tüm kargoların listesi. |
| `kargoGetir($id)` | Kargo dizisi veya `null`. |
| `kargoEkle($bilgiler, $islemiYapan)` | Yeni kargo ID'si; benzersiz takip numarası ve ilk hareketi oluşturur. |
| `kargoGuncelle($id, $durum, $konum, $personelId, $aciklama, $kullaniciId)` | Durumu, konumu ve personeli değiştirir; hareket ekler. |
| `kargoBilgileriniGuncelle($id, $bilgiler, $kullaniciId)` | Müşteri hesabı, gönderici/alıcı adı ve adresleri, teslimat tarihini günceller. Takip numarası, durum, konum ve personel korunur. Güncelleme ve hareket ekleme aynı transaction içinde uygulanır. |
| `hareketEkle($id, $durum, $konum, $aciklama, $kullaniciId)` | Kargo hareketi ekler. |
| `kargoHareketleriGetir($id)` | En yeni hareket önce olacak şekilde liste. |

## Dönen alanlar

- Kullanıcı: `id` (int), `ad_soyad`, `eposta`, `sifre_hash`, `rol`, `aktif` (bool).
- Rol değerleri: `yonetici`, `personel`, `musteri`.
- Kargo: `id` (int), `takip_no`, `musteri_id` (int), `personel_id` (int veya null), `gonderici_adi`, `gonderici_adres`, `alici_adi`, `alici_adres`, `teslimat_tarihi` (`Y-m-d`), `konum`, `durum`.
- Hareket: `tarih` (`Y-m-d H:i:s`), `durum`, `konum`, `aciklama`, `kullanici_id` (int).
- Durum değerleri: `Kabul edildi`, `Transferde`, `Dağıtımda`, `Teslim edildi`.

PDO'dan dönen ID'leri int, `aktif` alanını bool yapın. Sayfalarda türü de kontrol eden karşılaştırmalar kullanılır. Dizilerdeki alanlar tablo düzenini zorunlu kılmaz; adresler ayrı tabloda tutulup sorguda birleştirilebilir.

`musteri_id`, kargoyu görebilecek müşteri hesabıdır. Gönderici ve alıcı adından otomatik olarak hesap eşleştirilmez; yönetici müşteri hesabını ayrıca seçer. Bu davranış değişecekse sayfa ve erişim kontrolleri birlikte güncellenmelidir.

## Sayfaların sorumluluğu

- `includes/app.php`: Session, CSRF, giriş/rol kontrolü, müşteriye ve personele göre görünür kargoların seçilmesi.
- `login.php`, `register.php`, `logout.php`: Kimlik doğrulama akışı; kayıt rolü sunucuda müşteri olarak belirlenir.
- `kargolar.php`: Role göre liste ve teslimat tarihine göre sıralama.
- `kargo.php`: Erişim kontrolü, detay, hareketler, yönetici/personel güncellemesi. Personel atamayı yalnızca yönetici yapar.
- `kargo-ekle.php`, `kargo-duzenle.php`, `personeller.php`, `personel-duzenle.php`: Yalnızca yönetici işlemleri.

Mevcut yaklaşım küçük veri için tüm kargoları getirip PHP'de filtreler. Büyük veri için kullanıcıya göre sorgulama ve sayfalama daha sonra ayrıca ele alınabilir. Veri katmanında değişiklik yaparken yetki kontrollerini koruyun.

Kargo oluşturma ve düzenleme sayfaları `includes/kargo-alanlari.php` içindeki ortak alanları kullanır. Yönetici müşteri hesabını değiştirdiğinde eski müşteri erişimini kaybeder; yeni müşteri kargoyu görebilir.
