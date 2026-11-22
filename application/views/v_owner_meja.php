
<div class="container-fluid px-4 py-3 owner-meja-page">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 owner-meja-header">
        <div>
            <h2 class="fw-bold mb-1">Manajemen QR Code Meja</h2>
            <p class="text-muted mb-0">
                Kelola dan cetak QR Code untuk setiap meja restoran.
            </p>
        </div>

        <button onclick="window.print()"
            class="btn text-white px-4 shadow-sm no-print owner-meja-print"
            style="background-color: #1a1638; border-color: #1a1638;">
            <i class="fa-solid fa-print me-2"></i>
            Cetak Semua QR Code
        </button>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close"
                data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- GRID QR MEJA -->
    <div class="row g-3 g-xl-4 owner-meja-grid">

        <?php foreach ($meja as $m) : ?>

            <?php
                /*
                 * URL QR mengikuti host yang sedang digunakan.
                 * localhost  -> localhost
                 * IP Laptop -> IP Laptop
                 */

                $host_aktif = isset($_SERVER['HTTP_HOST'])
                    ? $_SERVER['HTTP_HOST']
                    : 'localhost';

                $url_tujuan = 'http://' . $host_aktif
                    . '/ayam-geprek-romeesa-final/index.php/pelanggan/meja/'
                    . $m->id_meja;

                $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data="
                    . urlencode($url_tujuan);
            ?>

            <div class="col-xl-3 col-lg-4 col-md-6 mb-2 owner-meja-col">

                <div class="card card-meja border-0 shadow-sm h-100">

                    <div class="card-body d-flex flex-column align-items-center owner-meja-card-body">

                        <!-- INFORMASI MEJA -->
                        <div class="owner-meja-heading w-100 text-center">
                            <h4 class="fw-bold text-dark mb-1">
                                <?= html_escape($m->nomor_meja); ?>
                            </h4>

                            <p class="small text-muted mb-0">
                                Kapasitas <?= html_escape($m->kapasitas); ?> orang
                            </p>
                        </div>

                        <!-- QR CODE -->
                        <div class="owner-meja-qr-frame bg-light rounded-3 border">
                            <img
                                src="<?= $qr_api; ?>"
                                alt="QR Code <?= html_escape($m->nomor_meja); ?>"
                                class="img-fluid owner-meja-qr">
                        </div>

                        <!-- LINK MEJA -->
                        <div class="owner-meja-url"
            title="<?= html_escape($url_tujuan); ?>">

                            <small class="text-muted">
                                <?= html_escape($url_tujuan); ?>
                            </small>
                        </div>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>
</div>


<style>

/* =========================================
   QR MEJA OWNER - TAMPILAN WEBSITE
   ========================================= */

.owner-meja-page .owner-meja-grid {
    align-items: stretch;
}

.owner-meja-page .card-meja {
    border-radius: 18px;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
}

.owner-meja-page .card-meja:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 22px rgba(26, 22, 56, .10) !important;
}

.owner-meja-page .owner-meja-card-body {
    padding: 1.15rem 1rem 1rem;
}

.owner-meja-page .owner-meja-heading {
    min-height: 62px;
}

.owner-meja-page .owner-meja-heading h4 {
    font-size: 1.35rem;
}

.owner-meja-page .owner-meja-qr-frame {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    width: 170px;
    height: 170px;
    margin: .35rem 0 .9rem;
}

.owner-meja-page .owner-meja-qr {
    width: 150px;
    height: 150px;
    object-fit: contain;
}

.owner-meja-page .owner-meja-url {
    width: 100%;
    max-width: 245px;
    text-align: center;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.owner-meja-page .owner-meja-url small {
    font-size: .72rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}


/* =========================================
   RESPONSIVE WEBSITE
   ========================================= */

@media (max-width: 575.98px) {

    .owner-meja-page .owner-meja-card-body {
        padding: 1rem .75rem;
    }

    .owner-meja-page .owner-meja-qr-frame {
        width: 158px;
        height: 158px;
    }

    .owner-meja-page .owner-meja-qr {
        width: 138px;
        height: 138px;
    }

}


/* =========================================
   PENGATURAN KERTAS CETAK
   ========================================= */

@page {
    size: A4 portrait;
    margin: 10mm;
}


/* =========================================
   KHUSUS PRINT QR MEJA
   ========================================= */

@media print {

    /* Hilangkan elemen navigasi */
    .app-sidebar,
    .app-topbar,
    .no-print,
    header,
    footer {
        display: none !important;
    }

    /* Hilangkan margin layout website */
    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
    }

    .app-main,
    .owner-meja-page,
    .owner-meja-grid {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* GRID PRINT: 3 CARD PER BARIS */
    .owner-meja-page .owner-meja-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 7mm !important;
    }

    .owner-meja-page .owner-meja-col {
        width: auto !important;
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    /* CARD QR */
    .owner-meja-page .card-meja {
        width: 100% !important;
        height: auto !important;
        min-height: 68mm !important;
        border: 1px solid #cccccc !important;
        border-radius: 4mm !important;
        box-shadow: none !important;
        transform: none !important;
        overflow: hidden !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    /* JARAK CARD */
    .owner-meja-page .owner-meja-card-body {
        padding: 4mm 2.5mm !important;
    }

    /* NAMA MEJA */
    .owner-meja-page .owner-meja-heading {
        min-height: auto !important;
        margin-bottom: 1mm !important;
    }

    .owner-meja-page .owner-meja-heading h4 {
        font-size: 15pt !important;
        margin-bottom: 1mm !important;
    }

    .owner-meja-page .owner-meja-heading p {
        font-size: 9pt !important;
    }

    /* UKURAN QR SAAT CETAK */
    .owner-meja-page .owner-meja-qr-frame {
        width: 42mm !important;
        height: 42mm !important;
        padding: 0 !important;
        margin: 2mm auto !important;
        border: none !important;
        border-radius: 0 !important;
        background: #ffffff !important;
    }

    .owner-meja-page .owner-meja-qr {
        display: block !important;
        width: 40mm !important;
        height: 40mm !important;
        max-width: none !important;
        object-fit: contain !important;
    }

    /* URL */
    .owner-meja-page .owner-meja-url {
        width: 100% !important;
        max-width: 100% !important;
        line-height: 1.3 !important;
        text-align: center !important;
        overflow-wrap: anywhere !important;
    }

    .owner-meja-page .owner-meja-url small {
        font-size: 7pt !important;
        display: block !important;
        overflow: visible !important;
        -webkit-line-clamp: unset !important;
    }

    /* MATIKAN EFEK HOVER */
    .owner-meja-page .card-meja:hover {
        transform: none !important;
        box-shadow: none !important;
    }

}

</style>
