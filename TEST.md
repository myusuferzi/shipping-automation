# Elle kontrol adımları

Bu liste çalışan MySQL bağlantısıyla tarayıcıda kontrol etmek içindir; tamamlandığı varsayılmaz. Yerel sunucu: `php -S localhost:8000 router.php`.

Test için veritabanında yönetici, personel ve iki ayrı müşteri hesabı hazırlayın. Gerçek kişilere ait veri kullanmayın. Aynı MySQL bağlantısını kullanan farklı tarayıcılar ortak kayıtları görür; her hesap yalnızca rolünün izin verdiği kayıtlara erişebilmelidir. SQL şeması tek başına test hesabı oluşturmaz.

## Hesaplar

- Hatalı şifreyle giriş yapmayı deneyin; oturum açılmamalı.
- Müşteri kaydı oluşturun. Şifre tekrarını farklı girince hata çıkmalı; doğru girince boş kargo listesi açılmalı.
- Aynı e-postayla tekrar kayıt oluşturulamadığını kontrol edin.
- Çıkıştan sonra korunan bir sayfayı yeniden açın; giriş sayfasına yönlenmeli.

## JavaScript ile form yardımları

- Giriş/kayıt ve personel formlarında Göster/Gizle butonlarını deneyin. Alanların değerleri ve form gönderim davranışı değişmemeli.
- Kayıt ve personel düzenlemede farklı şifreler yazın; tarayıcı gönderimi engellemeli. Her iki alanı da ayrı ayrı değiştirerek eşleştirin; hata temizlenmeli.
- Personel düzenlerken iki şifreyi boş bırakın; mevcut şifre korunmalı.
- Personel aktifliğini değiştirirken onayı iptal edin; hesap değişmemeli. Kabul edince normal işlem yapılmalı.
- JavaScript'i kapatarak formları tekrar deneyin. Göster/Gizle butonları görünmemeli; PHP hatalı şifre tekrarını ve yetkisiz işlemleri engellemeye devam etmeli.

## Kargo akışı

1. Yöneticiyle yeni bir müşteri kargosu oluşturup personele atayın.
2. Detayda takip numarasını, adresleri ve ilk hareketi kontrol edin.
3. Gönderi bilgilerini düzenleyin. Hatalı tarihte bilgiler kaydedilmemeli, formdaki girişler korunmalı.
4. Personel hesabına geçin. Atanan kargo listede görünmeli; durum ve konumu güncellenebilmeli.
5. Müşteri hesabına geçin. Güncel durum, konum ve hareket geçmişi görünmeli; düzenleme formu görünmemeli.
6. Yöneticiyle kargonun müşteri hesabını değiştirin. Eski müşteri artık detayı açamamalı; yeni müşteri görebilmeli.
7. Arama, durum filtresi ve sıralamayı birlikte deneyin. Erişim dışındaki kargolar sonuçlara girmemeli.

## Kargo özetleri ve gecikmeler

- Her rolün özet sayıları yalnızca erişebildiği kargoları içermeli.
- Toplam, dağıtımda, bugün teslim edilecek ve geciken kartlarına basınca ilgili kayıtlar listelenmeli.
- Bugün ve geciken sayıları teslim edilmiş kargoları içermemeli. Bugün tarihli kargo gecikmiş sayılmamalı.
- Geçmiş tarihli ve teslim edilmemiş kargoda “Gecikti” etiketi görünmeli; teslim edilince kalkmalı.
- Kart seçildikten sonra arama, durum ve sıralama uygulanabilmeli; kart sayıları aramadan etkilenmemeli. Temizle tüm listeye dönmeli.
- Seçilen grupta kayıt yoksa filtreye uygun boş sonuç mesajı görünmeli.
- Kartlar açık/karanlık temada, dar ekranda ve klavyeyle kontrol edilmeli.

## Kargo sayfalama

- 10'dan fazla erişilebilir kargoyla sayfa başına en fazla 10 kayıt gösterildiğini kontrol edin.
- Önceki/Sonraki bağlantıları kart seçimini, aramayı, durumu ve sıralamayı korumalı.
- Sayfa değişirken toplam kayıt sayısı ve özet kartları değişmemeli.
- Yeni filtre uygulayınca ilk sayfa açılmalı; son sayfada kalan kayıt sayısı doğru olmalı.
- `sayfa=0`, negatif, metin veya aşırı büyük değer deneyin; geçerli sayfa gösterilmeli.
- Boş sonuçta sayfalama görünmemeli; ilk/son sayfada ilgili gezinme düğmesi pasif olmalı.

## Personel yönetimi

- Yönetici yeni personel oluşturabilmeli, ad/e-posta düzenleyebilmeli.
- Şifre alanını boş bırakarak düzenleyin; eski şifre çalışmaya devam etmeli.
- Yeni şifre belirleyin; eski şifreyle giriş reddedilmeli.
- Devam eden kargosu olan personel pasife alınamamalı. Kargoları başka personele atayıp tekrar deneyin.
- Pasif personel giriş yapamamalı ve yeni kargoya atanamamalı.

## Yetki ve hata ekranları

- Müşteri veya personelle `/personeller.php` ve `/kargo-duzenle.php?id=1` adresini açın: 403 ekranı çıkmalı.
- Müşteriyle başka müşterinin kargo detayını açın: 404 ekranı çıkmalı.
- Var olmayan bir adresi ve iç içe bir adresi açın: 404 ekranının CSS'i ve ana sayfa bağlantısı çalışmalı.
- Eski bir formu çıkış/giriş yaptıktan sonra gönderin: CSRF kontrolü işlemi reddetmeli.
- `/500.php` adresini açın: ortak hata görünümü HTTP 500 koduyla gelmeli. Bu ekranın açılması MySQL bağlantısı gerektirmez.
- Yerel testte bağlantı dosyasını geçici olarak devre dışı bırakıp ana sayfayı açın: teknik ayrıntı yerine HTTP 500 koduyla hata ekranı gelmeli; ayrıntılar PHP hata günlüğüne yazılmalı. Ardından bağlantı dosyasını geri koyun.
- Sayfanın bir kısmı üretildikten sonra yakalanmamış bir hata oluştuğunda yarım HTML yerine tek bir 500 ekranı gösterilmeli.

## Görünüm ve klavye

- Dar ekranda menü açılmalı; formlar taşmamalı, geniş tablolar kendi içinde kaydırılmalı.
- Tab tuşuyla menüye, form alanlarına ve butonlara erişilebilmeli.
- Kargo detayındaki bar kayıtlı duruma kadar bir kez dolmalı; ana sayfadaki örnek sabit kalmalı.
- Hareket azaltma tercihi açıkken kart animasyonu oynamamalı.

## Açık ve karanlık tema

- Üst menüde Karanlık tema düğmesine basın; arka plan, yazılar, tablolar, formlar ve gönderi kartı yumuşak geçişle değişmeli.
- Ana sayfa, giriş/kayıt, kargo listesi/detayı, yönetim formları ve 403/404/500 ekranlarında iki temayı kontrol edin.
- Sayfa değiştirince ve yenileyince seçim korunmalı; açılışta açık tema parlamamalı.
- Kayıtlı tercih yokken sistemin açık/karanlık tercihi kullanılmalı.
- Hareket azaltma tercihi açıkken tema renkleri animasyonsuz değişmeli.
- Mobil menüde ve klavyeyle tema düğmesine erişilebilmeli. Ekran okuyucu karanlık temanın açık olup olmadığını bildirmeli.

## Veritabanı ve çok kullanıcılı kontrol

- Aynı akışı farklı iki tarayıcıdan deneyin; kayıtların ortak ve kalıcı olduğunu doğrulayın.
- Aynı e-postayla eşzamanlı kayıt denemesinde tek hesap oluşmalı.
- Kargo güncellemesi başarısız olursa hareket kaydı da eklenmemeli (transaction).

## Kargo bağlantısını kopyalama

- Liste ve detayda takip numarasının sağındaki simgeye gelince “Kopyala” görünmeli; klavyeyle odaklanınca da okunabilmeli.
- Düğmeye basıp bir metin alanına yapıştırın: mevcut sitenin adresi ve ilgili kargonun kimliğiyle tam bağlantı gelmeli.
- Başarıda “Kopyalandı” görünmeli; iki saniye sonra tekrar “Kopyala” yazmalı.
- Pano erişimi engelliyse elle kopyalanabilecek bağlantı penceresi açılmalı; yanlış başarı mesajı gösterilmemeli.
- JavaScript kapalıyken kopyalama düğmesi gizli kalmalı.
- Bağlantı erişim yetkisi vermez: başka müşterinin kargosu açılmamalı. Oturum kapalıysa giriş gerekir; mevcut giriş akışı kargo listesine döner.
