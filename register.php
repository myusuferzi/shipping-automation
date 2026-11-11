<?php
$sayfaBasligi = 'Kayıt ol';
$aktifSayfa = 'register';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <div class="auth-panel">
        <h1 class="h2">Kayıt ol</h1>
        <p class="text-secondary mb-4">Kargolarınızı takip etmek için bir müşteri hesabı oluşturun.</p>

        <p class="alert alert-secondary small" id="form-notu">
            Hesap işlemleri henüz kullanıma açık değil.
        </p>

        <form action="/register.php" method="post" aria-describedby="form-notu">
            <div class="mb-3">
                <label class="form-label" for="ad-soyad">Ad soyad</label>
                <input class="form-control" type="text" id="ad-soyad" name="ad_soyad" autocomplete="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="eposta">E-posta adresi</label>
                <input class="form-control" type="email" id="eposta" name="eposta" autocomplete="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="sifre">Şifre</label>
                <input class="form-control" type="password" id="sifre" name="sifre" autocomplete="new-password" minlength="8" aria-describedby="sifre-notu" required>
                <p class="form-text" id="sifre-notu">En az 8 karakter kullanın.</p>
            </div>

            <div class="mb-4">
                <label class="form-label" for="sifre-tekrar">Şifre tekrar</label>
                <input class="form-control" type="password" id="sifre-tekrar" name="sifre_tekrar" autocomplete="new-password" minlength="8" required>
            </div>

            <!-- Hesap işlemleri bağlandığında gönderim etkinleştirilecek. -->
            <button class="btn btn-primary w-100" type="submit" disabled>Kayıt ol</button>
        </form>

        <p class="text-center small mt-4 mb-0">
            Zaten hesabınız var mı? <a href="/login.php">Giriş yapın</a>
        </p>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
