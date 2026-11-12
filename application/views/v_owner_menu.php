<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Menu - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= site_url('owner'); ?>">Owner Dashboard - Ayam Geprek Romeesa</a>
            <div class="d-flex">
                <a href="<?= site_url('owner'); ?>" class="btn btn-outline-light btn-sm me-2">Kembali ke Laporan</a>
                <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Kelola Daftar Menu</h3>
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Menu Baru</button>
        </div>

        <div class="card shadow-sm border-0 p-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($menu)): ?>
                            <?php $no = 1; foreach($menu as $m): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="fw-bold"><?= $m['nama_menu']; ?></td>
                                    <td><span class="badge bg-secondary"><?= ucfirst($m['kategori']); ?></span></td>
                                    <td class="text-success fw-bold">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $m['id']; ?>">Edit</button>
                                        <a href="<?= site_url('owner/hapus_menu/' . $m['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="modalEdit<?= $m['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= site_url('owner/edit_menu/' . $m['id']); ?>" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Menu</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Menu</label>
                                                        <input type="text" name="nama_menu" class="form-control" value="<?= $m['nama_menu']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kategori</label>
                                                        <input type="text" name="kategori" class="form-control" value="<?= $m['kategori']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Harga (Rp)</label>
                                                        <input type="number" name="harga" class="form-control" value="<?= $m['harga']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data menu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Menu -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= site_url('owner/tambah_menu'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Menu Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Menu</label>
                            <input type="text" name="nama_menu" class="form-control" required placeholder="Contoh: Ayam Geprek Mozzarella">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" required placeholder="Contoh: makanan / minuman">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" required placeholder="Contoh: 18000">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm">Tambah Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>