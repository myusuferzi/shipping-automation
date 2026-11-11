<?php
$sayfaBasligi = 'Giriş yap';
$aktifSayfa = 'login';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik" class="container py-5">
    <div class="auth-panel">
        <h1 class="h2">Giriş yap</h1>
        <p class="text-secondary mb-4">Kargolarınızı takip etmek için hesabınıza giriş yapın.</p>

        <p class="alert alert-secondary small" id="form-notu">
            Hesap işlemleri henüz kullanıma açık değil.
        </p>

        <form action="/login.php" method="post" aria-describedby="form-notu">
            <div class="mb-3">
                <label class="form-label" for="eposta">E-posta adresi</label>
                <input class="form-control" type="email" id="eposta" name="eposta" autocomplete="email" required>
            </div>

            <div class="mb-4">
                <label class="form-label" for="sifre">Şifre</label>
                <input class="form-control" type="password" id="sifre" name="sifre" autocomplete="current-password" required>
            </div>

            <!-- Hesap işlemleri bağlandığında gönderim etkinleştirilecek. -->
            <button class="btn btn-primary w-100" type="submit" disabled>Giriş yap</button>
        </form>

        <p class="text-center small mt-4 mb-0">
            Hesabınız yok mu? <a href="/register.php">Kayıt olun</a>
        </p>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
