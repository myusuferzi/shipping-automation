# Veri bağlantısı

## İş bölümü

Sayfalar, oturum ve yetki kontrolleri hazır PHP fonksiyonlarını kullanır. MySQL'e geçerken öncelikle `data/veri.php` içindeki fonksiyon gövdeleri değiştirilecektir. Sayfalar `$_SESSION['veriler']` dizisine doğrudan erişmez.

`data/ornek.php` yalnızca yerel örnek veriler üretir. Şu an örnek veriler her tarayıcının session'ında ayrıdır: başka tarayıcıda açılan hesap aynı değişiklikleri görmez. Çıkış yapmak verileri silmez; session sona erdiğinde örnek veriler sıfırlanır. Bu yapı gerçek çok kullanıcılı kayıt sistemi değildir.

## MySQL'e geçiş

1. Gerçek tablo yapısını ve alan adlarını birlikte netleştirin. En az dört ilişkili tablo gereksinimini koruyun; örneğin kullanıcılar, adresler, kargolar ve hareketler.
2. Bağlantıyı `config/database.local.php` içinde yapılandırın; bu dosya Git dışında tutulur. Parolasız bir örnek yapılandırma dosyası paylaşın.
3. `data/veri.php` içindeki örnek veri yükleme bölümünü kaldırıp MySQL bağlantısını ekleyin. `data/ornek.php` gerçek ortamda yüklenmemelidir.
4. Aşağıdaki fonksiyonları PDO ve parametreli sorgularla uygulayın. Dönüş alanlarını koruyun; gerekirse SQL alias kullanın.
5. Kargo oluşturma/güncelleme ile hareket eklemeyi aynı transaction içinde yapın. E-posta ve takip numarası için veritabanında benzersiz kısıt kullanın. E-posta benzersizliği büyük/küçük harfe duyarsız olmalıdır.
6. PDO hata ayrıntılarını kullanıcıya göstermeyin. Beklenen iş hatalarını anlaşılır `RuntimeException` mesajlarıyla bildirin.
7. Entegrasyonu doğruladıktan sonra ortak header'daki örnek veri uyarısını ve giriş ekranındaki örnek hesapları kaldırın. İlk yönetici hesabını güvenli bir kurulum işlemiyle oluşturun.

## Fonksiyonlar

| Fonksiyon | Dönüş / davranış |
| --- | --- |
| `kullanicilariGetir()` | Kullanıcı dizilerinden oluşan liste. |
| `kullaniciGetir($id)` | Kullanıcı dizisi veya `null`. |
| `epostaIleKullaniciGetir($eposta)` | Büyük/küçük harften bağımsız kullanıcı veya `null`. |
| `kullaniciEkle($ad, $eposta, $sifreHash, $rol)` | Yeni kullanıcı ID'si. Şifre zaten hashlenmiş gelir. Yinelenen e-postada hata. |
| `personelAktifliginiDegistir($id)` | Personeli aktif/pasif yapar. Teslim edilmemiş kargosu varsa pasife almayı reddeder. |
| `kargolariGetir()` | Tüm kargoların listesi. |
| `kargoGetir($id)` | Kargo dizisi veya `null`. |
| `kargoEkle($bilgiler, $islemiYapan)` | Yeni kargo ID'si; benzersiz takip numarası ve ilk hareketi oluşturur. |
| `kargoGuncelle($id, $durum, $konum, $personelId, $aciklama, $kullaniciId)` | Durumu, konumu ve personeli değiştirir; hareket ekler. |
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
- `kargo-ekle.php`, `personeller.php`: Yalnızca yönetici işlemleri.

Mevcut yaklaşım küçük veri için tüm kargoları getirip PHP'de filtreler. Büyük veri için kullanıcıya göre sorgulama ve sayfalama daha sonra ayrıca ele alınabilir. Yetki kontrollerini MySQL geçişinde kaldırmayın.
