<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Kargo takip ve yönetim otomasyonu: gönderilerinizi ve teslimat sürecini tek yerden takip edin.">
        <title>Kargo | Takip ve Yönetim</title>
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    </head>
    <body>
        <a class="visually-hidden-focusable skip-link" href="#icerik">İçeriğe geç</a>

        <!-- Üst menü -->
        <header class="border-bottom bg-white">
            <nav class="navbar navbar-expand-sm py-3" aria-label="Ana menü">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="index.php">
                        <span class="brand-mark" aria-hidden="true">K</span>
                        Kargo<span class="brand-dot">.</span>
                    </a>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#ana-menu"
                        aria-controls="ana-menu"
                        aria-expanded="false"
                        aria-label="Menüyü aç veya kapat"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="ana-menu">
                        <ul class="navbar-nav ms-auto gap-sm-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="index.php">Ana sayfa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#surec">Teslimat süreci</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#hakkinda">Hakkında</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
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
                            <div class="shipment-preview">
                                <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
                                    <span class="fw-semibold">Gönderi yolculuğu</span>
                                    <span class="badge rounded-pill preview-label">Örnek görünüm</span>
                                </div>
                                <div class="parcel-illustration" aria-hidden="true">
                                    <span>↑ ↑</span>
                                    <strong>K.</strong>
                                </div>
                                <p class="text-center fw-semibold mt-4 mb-2">Her adımda güncel bilgi</p>
                                <p class="text-center text-secondary small mb-0">Kabulden teslimata kadar tüm hareketler.</p>
                                <div class="preview-stages mt-4" aria-hidden="true">
                                    <span>Kabul</span>
                                    <span>Transfer</span>
                                    <span>Dağıtım</span>
                                    <span>Teslimat</span>
                                </div>
                            </div>
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

            <!-- Proje hakkında -->
            <section class="container pb-5" id="hakkinda" aria-labelledby="hakkinda-baslik">
                <div class="about-panel">
                    <div>
                        <p class="eyebrow">PROJE HAKKINDA</p>
                        <h2 id="hakkinda-baslik">Daha düzenli bir kargo süreci.</h2>
                        <p class="mb-0 text-secondary">Yönetici, kargo personeli ve müşteriyi aynı süreçte buluşturan bir web programlama projesi.</p>
                    </div>
                    <p class="development-note mb-0">
                        Geliştirme aşamasında
                        <br>
                        <span>Giriş, kayıt ve kargo takibi yakında.</span>
                    </p>
                </div>
            </section>
        </main>

        <!-- Alt bilgi -->
        <footer class="border-top py-4">
            <div class="container d-flex flex-wrap justify-content-between gap-2">
                <span class="fw-semibold">Kargo Takip ve Yönetim</span>
                <span class="text-secondary small">Web Programlama Dönem Projesi</span>
            </div>
        </footer>
    </body>
</html>
