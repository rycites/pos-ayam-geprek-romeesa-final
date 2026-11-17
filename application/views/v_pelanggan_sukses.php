<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center p-4 card shadow-sm border-0 rounded-4" style="max-width: 420px; width: 100%;">
        <div class="mb-3 text-success fs-1">✅</div>
        <h3 class="fw-bold mb-2">Pesanan Berhasil Dikirim!</h3>
        <p class="text-muted small mb-4">Pesanan untuk <b>Meja <?= $no_meja; ?></b> (No. Faktur: <b><?= $no_faktur; ?></b>) sudah masuk sistem. Silakan tunggu sebentar, makanan akan segera disiapkan.</p>
        
        <!-- Tombol Cek Status Pesanan (Fitur Baru) -->
        <?php if(isset($id_transaksi)): ?>
            <a href="<?= site_url('pelanggan/status/' . $id_transaksi); ?>" class="btn btn-warning text-dark w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-2">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> Cek Status Pesanan Saya
            </a>
            
            <a href="<?= site_url('cashier/struk/' . $id_transaksi); ?>" class="btn btn-dark w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-2" target="_blank">
                <i class="fa-solid fa-receipt me-2"></i> Lihat Struk Bukti Pesanan
            </a>
        <?php endif; ?>

        <a href="<?= site_url('pelanggan/meja/' . $no_meja); ?>" class="btn btn-outline-danger w-100 rounded-pill py-2.5 fw-semibold">
            <i class="fa-solid fa-plus me-1"></i> Pesan Menu Lain
        </a>
    </div>
</body>
</html>