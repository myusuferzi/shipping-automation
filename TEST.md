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

## Görünüm ve klavye

- Dar ekranda menü açılmalı; formlar taşmamalı, geniş tablolar kendi içinde kaydırılmalı.
- Tab tuşuyla menüye, form alanlarına ve butonlara erişilebilmeli.
- Kargo detayındaki bar kayıtlı duruma kadar bir kez dolmalı; ana sayfadaki örnek sabit kalmalı.
- Hareket azaltma tercihi açıkken kart animasyonu oynamamalı.

## Veritabanı ve çok kullanıcılı kontrol

- Aynı akışı farklı iki tarayıcıdan deneyin; kayıtların ortak ve kalıcı olduğunu doğrulayın.
- Aynı e-postayla eşzamanlı kayıt denemesinde tek hesap oluşmalı.
- Kargo güncellemesi başarısız olursa hareket kaydı da eklenmemeli (transaction).
