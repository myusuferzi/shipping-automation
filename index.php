<?php
$sayfaBasligi = 'Ana sayfa';
$aktifSayfa = 'anasayfa';
require __DIR__ . '/includes/header.php';
?>

<main id="icerik">

    <!-- Ana tanıtım -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <p class="eyebrow">KARGO TAKİP VE YÖNETİM</p>
                    <h1>
                        Gönderinizin yolculuğu,
                        <br>
                        <span>tek bir yerde.</span>
                    </h1>
                    <p class="hero-description">Kargonuzun nerede olduğunu ve teslimat sürecinin hangi aşamada olduğunu kolayca takip edin.</p>
                    <a class="btn btn-primary px-4 py-3" href="#surec">
                        Teslimat sürecini keşfet
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
                <div class="col-lg-5">
                    <?php
                    $kartOrnek = true;
                    $kartKargo = ['durum' => 'Dağıtımda'];
                    require __DIR__ . '/includes/gonderi-karti.php';
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Teslimat adımları -->
    <section class="process-section" id="surec" aria-labelledby="surec-baslik">
        <div class="container">
            <p class="eyebrow">ADIM ADIM TESLİMAT</p>
            <h2 id="surec-baslik">Bir gönderi, dört adım.</h2>
            <div class="row g-4 mt-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="process-step">
                        <span class="step-number">01</span>
                        <h3>Kargo kabulü</h3>
                        <p>Gönderici ve alıcı bilgileriyle kargonun kaydı oluşturulur.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="process-step">
                        <span class="step-number">02</span>
                        <h3>Transfer</h3>
                        <p>Kargonun yolculuğu boyunca konum ve durum bilgileri güncellenir.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="process-step">
                        <span class="step-number">03</span>
                        <h3>Dağıtıma çıkış</h3>
                        <p>Görevli personel, kendisine atanan gönderinin teslimatını yürütür.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="process-step">
                        <span class="step-number">04</span>
                        <h3>Teslimat</h3>
                        <p>Teslim işlemi kaydedilir ve gönderinin hareket geçmişinde yerini alır.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hakkında -->
    <section class="container pb-5" id="hakkinda" aria-labelledby="hakkinda-baslik">
        <div class="about-panel">
            <div>
                <p class="eyebrow">HAKKIMIZDA</p>
                <h2 id="hakkinda-baslik">Daha düzenli bir kargo süreci.</h2>
                <p class="mb-0 text-secondary">Gönderilerinizi oluşturun, teslimat sürecini yönetin ve kargo hareketlerini tek yerden takip edin.</p>
            </div>

        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
