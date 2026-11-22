<div class="container-fluid px-4 py-3">
    <!-- Header Bagian Atas & Tombol Cetak yang Diselaraskan dengan Warna Sidebar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Manajemen QR Code Meja</h2>
            <p class="text-muted mb-0">Kelola dan cetak QR Code untuk setiap meja restoran.</p>
        </div>
        
        <!-- Tombol dicocokkan dengan warna latar sidebar -->
        <button onclick="window.print()" class="btn text-white px-4 shadow-sm no-print" style="background-color: #1a1638; border-color: #1a1638;">
            <i class="fa-solid fa-print me-2"></i> Cetak Semua QR Code
        </button>
    </div>

    <!-- Flash Message (jika ada) -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Grid Kartu Meja & QR Code -->
    <div class="row">
        <?php foreach ($meja as $m) : ?>
            <?php 
                // URL tujuan disesuaikan dengan fungsi 'meja' di controller Pelanggan
                $url_tujuan = base_url('pelanggan/meja/' . $m->id_meja);
                // Menggunakan API QR Code publik yang praktis
                $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($url_tujuan);
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card card-meja border-0 shadow-sm h-100 text-center p-3">
                    <div class="card-body d-flex flex-column align-items-center">
                        <h4 class="fw-bold text-dark mb-2"><?= html_escape($m->nomor_meja); ?></h4>
                        <p class="small text-muted mb-3">Kapasitas: <?= html_escape($m->kapasitas); ?> Orang</p>
                        
                        <div class="bg-light p-3 rounded mb-3 border">
                            <img src="<?= $qr_api; ?>" alt="QR Code <?= html_escape($m->nomor_meja); ?>" class="img-fluid" style="width: 150px; height: 150px;">
                        </div>

                        <span class="badge bg-success mb-3 px-3 py-2"><?= html_escape($m->status_meja); ?></span>
                        <small class="text-muted text-break" style="font-size: 0.75rem;"><?= $url_tujuan; ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Styling Khusus untuk Cetak (Print) -->
<style>
@media print {
    /* Sembunyikan sidebar, topbar, dan tombol cetak saat diprint */
    .app-sidebar, .app-topbar, .no-print, header, footer {
        display: none !important;
    }
    
    /* Atur agar layout memenuhi halaman kertas cetak */
    .app-main {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    body {
        background-color: white !important;
        color: black !important;
    }

    .card-meja {
        border: 1px solid #ddd !important;
        page-break-inside: avoid;
        box-shadow: none !important;
    }
}
</style>