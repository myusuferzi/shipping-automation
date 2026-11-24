// Sayfa çizilmeden önce kayıtlı temayı uygula; tercih yoksa sistem temasını kullan.
let tema = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

try {
    const kayitliTema = localStorage.getItem('kargo-tema');

    if (kayitliTema === 'dark' || kayitliTema === 'light') {
        tema = kayitliTema;
    }
} catch {
    // Tarayıcı depolamaya izin vermiyorsa tema bu sayfada yine değiştirilebilir.
}

document.documentElement.dataset.bsTheme = tema;

document.addEventListener('DOMContentLoaded', function () {
    const buton = document.querySelector('[data-tema-degistir]');

    if (!buton) {
        return;
    }

    buton.hidden = false;
    buton.setAttribute('aria-checked', tema === 'dark' ? 'true' : 'false');

    buton.addEventListener('click', function () {
        tema = tema === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.bsTheme = tema;
        buton.setAttribute('aria-checked', tema === 'dark' ? 'true' : 'false');

        try {
            localStorage.setItem('kargo-tema', tema);
        } catch {
            // Seçim kaydedilemese de mevcut sayfada uygulanır.
        }
    });
});
