<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="text-center p-4 card shadow-sm border-0" style="max-width: 400px; width: 100%;">
        <div class="mb-3 text-success fs-1">✅</div>
        <h3 class="fw-bold mb-2">Pesanan Berhasil Dikirim!</h3>
        <p class="text-muted">Pesanan untuk <b>Meja <?= $no_meja; ?></b> sudah masuk ke dapur. Silakan tunggu sebentar, makanan akan segera disajikan.</p>
        <a href="<?= site_url('pelanggan/meja/' . $no_meja); ?>" class="btn btn-danger w-100 mt-3">Pesan Menu Lain</a>
    </div>
</body>
</html>