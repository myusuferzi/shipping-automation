// Şifre gösterme: buton hangi alanı kontrol edeceğini data-sifre-goster'den alır.
const sifreButonlari = document.querySelectorAll('[data-sifre-goster]');

sifreButonlari.forEach(function (buton) {
    const alan = document.getElementById(buton.dataset.sifreGoster);

    if (!alan) {
        return;
    }

    // JavaScript çalışmıyorsa işlevsiz bir buton gösterilmez.
    buton.hidden = false;

    buton.addEventListener('click', function () {
        if (alan.type === 'password') {
            alan.type = 'text';
            buton.textContent = 'Gizle';
        } else {
            alan.type = 'password';
            buton.textContent = 'Göster';
        }
    });
});

// Şifre tekrarı olan formlarda tarayıcının kendi doğrulama mesajını kullanırız.
const sifreFormlari = document.querySelectorAll('[data-sifre-kontrolu]');

sifreFormlari.forEach(function (form) {
    const sifre = form.querySelector('[name="sifre"]');
    const tekrar = form.querySelector('[name="sifre_tekrar"]');

    function sifreleriKontrolEt() {
        if (sifre.value !== tekrar.value) {
            tekrar.setCustomValidity('Şifreler birbiriyle eşleşmiyor.');
        } else {
            tekrar.setCustomValidity('');
        }
    }

    sifre.addEventListener('input', sifreleriKontrolEt);
    tekrar.addEventListener('input', sifreleriKontrolEt);

    form.addEventListener('submit', function (olay) {
        sifreleriKontrolEt();

        if (!form.checkValidity()) {
            olay.preventDefault();
            form.reportValidity();
        }
    });
});

// Onay iptal edilirse form gönderilmez; kabul edilirse normal POST devam eder.
const onayFormlari = document.querySelectorAll('[data-onay]');

onayFormlari.forEach(function (form) {
    form.addEventListener('submit', function (olay) {
        if (!window.confirm(form.dataset.onay)) {
            olay.preventDefault();
        }
    });
});

// Kargo bağlantısını mevcut sitenin adresiyle birlikte kopyala.
const kopyalaButonlari = document.querySelectorAll('[data-kargo-baglantisi]');

kopyalaButonlari.forEach(function (buton) {
    const ipucu = buton.querySelector('.copy-hint');
    const sonuc = buton.querySelector('[data-kopyala-sonucu]');
    let zamanlayici;
    buton.hidden = false;

    buton.addEventListener('click', async function () {
        const baglanti = new URL(buton.dataset.kargoBaglantisi, window.location.origin).href;
        clearTimeout(zamanlayici);

        try {
            await navigator.clipboard.writeText(baglanti);
            ipucu.textContent = 'Kopyalandı';
            sonuc.textContent = 'Kargo bağlantısı kopyalandı.';
        } catch {
            // Pano erişimi yoksa bağlantı elle kopyalanabilir.
            window.prompt('Bağlantıyı kopyalayın:', baglanti);
            ipucu.textContent = 'Kopyala';
            sonuc.textContent = 'Otomatik kopyalama yapılamadı.';
        }

        zamanlayici = setTimeout(function () {
            ipucu.textContent = 'Kopyala';
            sonuc.textContent = '';
        }, 2000);
    });
});
