<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$daftar_kategori = ['makanan' => 'Makanan', 'minuman' => 'Minuman', 'topping' => 'Topping'];
?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error_upload')): ?>
    <div class="alert alert-warning mb-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span>Foto gagal diunggah: <?= html_escape($this->session->flashdata('error_upload')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="page-head">
    <div>
        <h2><?= is_array($menu) ? count($menu) : 0; ?> menu terdaftar</h2>
        <p>Menu berstatus tersedia yang muncul di halaman kasir dan pesanan pelanggan.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Tambah menu
    </button>
</div>

<div class="card overflow-hidden">
    <?php if (!empty($menu)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th style="width: 72px;">Foto</th>
                        <th>Nama menu</th>
                        <th>Kategori</th>
                        <th class="num text-end">Harga</th>
                        <th class="text-end" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($menu as $m): ?>
                        <tr>
                            <td class="text-muted num"><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($m['foto']) && $m['foto'] !== 'default.jpg'): ?>
                                    <img src="<?= base_url('uploads/' . $m['foto']); ?>" alt="<?= html_escape($m['nama_menu']); ?>" class="menu-thumb">
                                <?php else: ?>
                                    <span class="menu-thumb is-empty"><i class="fa-solid fa-image" aria-hidden="true"></i></span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= html_escape($m['nama_menu']); ?></td>
                            <td>
                                <span class="badge-soft is-neutral"><?= $daftar_kategori[$m['kategori']] ?? ucfirst($m['kategori']); ?></span>
                            </td>
                            <td class="num text-end fw-bold">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></td>
                            <td class="text-end text-nowrap" style="width: 210px;">
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $m['id']; ?>">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i> Ubah
                                </button>
                                <a href="<?= site_url('owner/hapus_menu/' . $m['id']); ?>" class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Hapus menu ini beserta fotonya?');">
                                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-burger" aria-hidden="true"></i>
            <h4>Belum ada menu</h4>
            <p>Tambahkan menu pertama agar kasir dan pelanggan bisa memesan.</p>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($menu)): ?>
    <?php foreach ($menu as $m): ?>
        <div class="modal fade" id="modalEdit<?= $m['id']; ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $m['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= site_url('owner/edit_menu/' . $m['id']); ?>" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditLabel<?= $m['id']; ?>">Ubah menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="nama_menu_<?= $m['id']; ?>">Nama menu</label>
                                <input type="text" id="nama_menu_<?= $m['id']; ?>" name="nama_menu" class="form-control" value="<?= html_escape($m['nama_menu']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="kategori_<?= $m['id']; ?>">Kategori</label>
                                <select id="kategori_<?= $m['id']; ?>" name="kategori" class="form-select" required>
                                    <?php foreach ($daftar_kategori as $nilai => $label): ?>
                                        <option value="<?= $nilai; ?>" <?= $m['kategori'] === $nilai ? 'selected' : ''; ?>><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="harga_<?= $m['id']; ?>">Harga (Rp)</label>
                                <input type="number" id="harga_<?= $m['id']; ?>" name="harga" class="form-control" value="<?= (int) $m['harga']; ?>" min="0" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="foto_<?= $m['id']; ?>">Ganti foto (opsional)</label>
                                <input type="file" id="foto_<?= $m['id']; ?>" name="foto" class="form-control" accept="image/png, image/jpeg">
                                <p class="text-muted small mb-0 mt-1">JPG atau PNG, maksimal 2 MB. Biarkan kosong bila tidak ingin mengganti.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('owner/tambah_menu'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah menu baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="nama_menu_baru">Nama menu</label>
                        <input type="text" id="nama_menu_baru" name="nama_menu" class="form-control" required placeholder="Contoh: Ayam Geprek Mozzarella">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kategori_baru">Kategori</label>
                        <select id="kategori_baru" name="kategori" class="form-select" required>
                            <?php foreach ($daftar_kategori as $nilai => $label): ?>
                                <option value="<?= $nilai; ?>"><?= $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="harga_baru">Harga (Rp)</label>
                        <input type="number" id="harga_baru" name="harga" class="form-control" required min="0" placeholder="Contoh: 18000">
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="foto_baru">Foto menu</label>
                        <input type="file" id="foto_baru" name="foto" class="form-control" accept="image/png, image/jpeg">
                        <p class="text-muted small mb-0 mt-1">JPG atau PNG, maksimal 2 MB.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah menu</button>
                </div>
            </form>
        </div>
    </div>
</div>
