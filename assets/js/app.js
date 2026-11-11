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
