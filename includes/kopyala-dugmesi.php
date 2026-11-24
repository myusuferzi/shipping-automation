<button class="btn btn-sm btn-outline-secondary copy-button" type="button"
    data-kargo-baglantisi="/kargo.php?id=<?= e($kargo['id']) ?>"
    aria-label="Kargo bağlantısını kopyala" hidden>
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <rect x="8" y="8" width="12" height="12" rx="2"></rect>
        <path d="M15 8V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h4"></path>
    </svg>
    <span class="copy-hint" aria-hidden="true">Kopyala</span>
    <span class="visually-hidden" role="status" data-kopyala-sonucu></span>
</button>
