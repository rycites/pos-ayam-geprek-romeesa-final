<div class="container-fluid px-4 py-3">
    <!-- Notifikasi Umum (Success) -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tombol Tambah User -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Daftar Pengguna Sistem</h5>
            <p class="text-muted small mb-0">Kelola akses akun untuk Kasir, Dapur, dan Owner restoran.</p>
        </div>
        <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa-solid fa-user-plus me-2"></i> Tambah User Baru
        </button>
    </div>

    <!-- Tabel User -->
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Nama Lengkap</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">Role / Hak Akses</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): ?>
                            <?php $no = 1; foreach($users as $u): ?>
                                <tr>
                                    <td class="fw-bold text-muted"><?= $no++; ?></td>
                                    <td class="fw-bold text-dark"><?= $u['nama']; ?></td>
                                    <td><code><?= $u['username']; ?></code></td>
                                    <td>
                                        <?php if($u['role'] == 'owner'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1.5 rounded-pill fw-bold">Owner</span>
                                        <?php elseif($u['role'] == 'kasir'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill fw-bold">Kasir</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-1.5 rounded-pill fw-bold">Dapur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser<?= $u['id']; ?>">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <a href="<?= site_url('owner/hapus_user/' . $u['id']); ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold" onclick="return confirm('Yakin ingin menghapus user ini?');">
                                            <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                        </a>
                                    </td>
                                </tr>

                                <!-- MODAL EDIT USER -->
                                <div class="modal fade" id="modalEditUser<?= $u['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="fw-bold text-dark">Edit Pengguna</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?= site_url('owner/edit_user/' . $u['id']); ?>" method="POST">
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-secondary">Nama Lengkap:</label>
                                                        <input type="text" name="nama" class="form-control rounded-3" value="<?= $u['nama']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-secondary">Username:</label>
                                                        <input type="text" name="username" class="form-control rounded-3 <?= $this->session->flashdata('error_username_' . $u['id']) ? 'is-invalid' : ''; ?>" value="<?= $u['username']; ?>" required>
                                                        <?php if($this->session->flashdata('error_username_' . $u['id'])): ?>
                                                            <div class="invalid-feedback fw-semibold">
                                                                <i class="fa-solid fa-circle-exclamation me-1"></i> <?= $this->session->flashdata('error_username_' . $u['id']); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-secondary">Password Baru (Kosongkan jika tidak ingin diubah):</label>
                                                        <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-secondary">Role / Hak Akses:</label>
                                                        <select name="role" class="form-select rounded-3" required>
                                                            <option value="owner" <?= $u['role'] == 'owner' ? 'selected' : ''; ?>>Owner</option>
                                                            <option value="kasir" <?= $u['role'] == 'kasir' ? 'selected' : ''; ?>>Kasir</option>
                                                            <option value="dapur" <?= $u['role'] == 'dapur' ? 'selected' : ''; ?>>Dapur</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash fa-3x opacity-25 mb-3 d-block"></i>
                                    <p class="mb-0 fw-medium text-secondary">Belum ada user terdaftar.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold text-dark">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('owner/tambah_user'); ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Lengkap:</label>
                        <input type="text" name="nama" class="form-control rounded-3" value="<?= isset($this->session->flashdata('old_input')['nama']) ? $this->session->flashdata('old_input')['nama'] : ''; ?>" placeholder="Contoh: Siti Karyawan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Username:</label>
                        <input type="text" name="username" class="form-control rounded-3 <?= $this->session->flashdata('error_username') ? 'is-invalid' : ''; ?>" value="<?= isset($this->session->flashdata('old_input')['username']) ? $this->session->flashdata('old_input')['username'] : ''; ?>" placeholder="Contoh: kasir2" required>
                        <?php if($this->session->flashdata('error_username')): ?>
                            <div class="invalid-feedback fw-semibold">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> <?= $this->session->flashdata('error_username'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Password:</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Role / Hak Akses:</label>
                        <select name="role" class="form-select rounded-3" required>
                            <option value="kasir">Kasir</option>
                            <option value="dapur">Dapur</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT AGAR MODAL OTOMATIS TERBUKA KEMBALI KETIKA ADA ERROR -->
<?php if($this->session->flashdata('modal_target')): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modalId = "<?= $this->session->flashdata('modal_target'); ?>";
        var myModal = new bootstrap.Modal(document.getElementById(modalId));
        myModal.show();
    });
</script>
<?php endif; ?>