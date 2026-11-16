<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk & Status Pesanan - #<?= isset($transaksi['no_faktur']) ? $transaksi['no_faktur'] : ''; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Meta Refresh otomatis setiap 10 detik agar status ter-update tanpa reload manual -->
    <meta http-equiv="refresh" content="10">
</head>
<body class="bg-light py-4">

    <div class="container" style="max-width: 480px;">
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-3">
            <div class="text-center mb-3">
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-receipt me-1"></i> Struk & Status Meja
                </span>
                <h4 class="fw-bold mb-1">PESANAN AKTIF</h4>
                <p class="text-muted small mb-0">No. Faktur: <b><?= isset($transaksi['no_faktur']) ? $transaksi['no_faktur'] : ''; ?></b></p>
                <p class="text-danger small fw-semibold"><?= isset($transaksi['nomor_meja']) ? $transaksi['nomor_meja'] : ''; ?></p>
            </div>

            <hr class="text-muted">

            <!-- Indikator Status Pembayaran -->
            <div class="mb-4">
                <label class="text-muted small fw-bold d-block mb-1">STATUS PEMBAYARAN:</label>
                <?php if(isset($transaksi['status_bayar']) && strtolower($transaksi['status_bayar']) == 'lunas'): ?>
                    <div class="alert alert-success py-2 px-3 mb-0 small fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-circle-check fs-5 me-2"></i> Lunas (Pembayaran Berhasil)
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning py-2 px-3 mb-0 small fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-clock fs-5 me-2"></i> Menunggu Konfirmasi / Pembayaran di Kasir
                    </div>
                <?php endif; ?>
            </div>

            <!-- Daftar Menu & Status Dapur -->
            <div class="mb-3">
                <label class="text-muted small fw-bold d-block mb-2">PROSES DAPUR:</label>
                <div class="list-group list-group-flush small">
                    <?php if(!empty($detail_transaksi)): ?>
                        <?php foreach($detail_transaksi as $item): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <span class="fw-bold"><?= $item['nama_menu']; ?></span>
                                    <span class="text-muted ms-1">(x<?= $item['jumlah']; ?>)</span>
                                </div>
                                <div>
                                    <?php if($item['status'] == 'pending' || $item['status'] == 'menunggu_kasir'): ?>
                                        <span class="badge bg-secondary">Antrean / Menunggu</span>
                                    <?php elseif($item['status'] == 'proses'): ?>
                                        <span class="badge bg-warning text-dark">Sedang Dimasak 🍳</span>
                                    <?php elseif($item['status'] == 'selesai'): ?>
                                        <span class="badge bg-success">Siap Disajikan ✅</span>
                                    <?php else: ?>
                                        <span class="badge bg-info"><?= ucfirst($item['status']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alert alert-info small text-center mb-4 py-2">
                <i class="fa-solid fa-circle-info me-1"></i> Halaman ini memperbarui status secara otomatis setiap 10 detik.
            </div>

            <!-- Tombol Lihat Struk Bukti Pesanan -->
            <?php 
                $id_trx = isset($transaksi['id_transaksi']) ? $transaksi['id_transaksi'] : (isset($transaksi['id']) ? $transaksi['id'] : '');
            ?>
            <?php if(!empty($id_trx)): ?>
                <a href="<?= site_url('cashier/struk/' . $id_trx); ?>" class="btn btn-dark w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-2" target="_blank">
                    <i class="fa-solid fa-receipt me-2"></i> Lihat Struk Bukti Pesanan
                </a>
            <?php endif; ?>

            <!-- Tombol Navigasi Dinamis (Hanya munculkan tombol reset/pesanan baru jika dapur sudah benar-benar selesai semua) -->
            <?php 
                // Ekstrak nomor meja dari string "Meja X (...)"
                preg_match('/\d+/', isset($transaksi['nomor_meja']) ? $transaksi['nomor_meja'] : '1', $match);
                $no_m = isset($match[0]) ? $match[0] : 1;

                // Cek apakah SEMUA item sudah berstatus 'selesai'
                $is_semua_selesai = true;
                if(!empty($detail_transaksi)) {
                    foreach($detail_transaksi as $dt) {
                        if($dt['status'] != 'selesai') {
                            $is_semua_selesai = false;
                            break;
                        }
                    }
                } else {
                    $is_semua_selesai = false;
                }
            ?>

            <?php if($is_semua_selesai): ?>
                <!-- Jika Dapur Sudah Selesai Sepenuhnya -->
                <div class="d-grid gap-2">
                    <a href="<?= site_url('pelanggan/reset_sesi_meja/' . $no_m); ?>" class="btn btn-danger rounded-pill py-2.5 fw-bold shadow-sm">
                        <i class="fa-solid fa-utensils me-2"></i> Selesai & Buat Pesanan Baru
                    </a>
                    <a href="<?= site_url('pelanggan/meja/' . $no_m); ?>" class="btn btn-outline-dark rounded-pill py-2 fw-semibold">
                        <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Menu
                    </a>
                </div>
            <?php else: ?>
                <!-- Jika Dapur Belum Selesai (Sesi & Struk Tetap Aman Bisa Diakses Kapan Saja) -->
                <a href="<?= site_url('pelanggan/meja/' . $no_m); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-semibold w-100 py-2.5">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Menu Utama
                </a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>