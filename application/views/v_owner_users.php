<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lama        = $this->session->flashdata('old_input');
$id_saya     = (int) $this->session->userdata('id');
$label_role  = ['owner' => 'Owner', 'kasir' => 'Kasir', 'dapur' => 'Dapur'];
$kelas_role  = ['owner' => 'is-brand', 'kasir' => 'is-ok', 'dapur' => 'is-warn'];
?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger mb-3" role="alert">
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('error')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="page-head">
    <div>
        <h2><?= is_array($users) ? count($users) : 0; ?> akun terdaftar</h2>
        <p>Hak akses menentukan halaman yang bisa dibuka tiap orang setelah masuk.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <i class="fa-solid fa-user-plus me-1" aria-hidden="true"></i> Tambah user
    </button>
</div>

<div class="card overflow-hidden">
    <?php if (!empty($users)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Hak akses</th>
                        <th class="text-end" style="width: 210px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $u): ?>
                        <tr>
                            <td class="text-muted num"><?= $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar" style="width:32px;height:32px;font-size:.75rem;">
                                        <?= html_escape(strtoupper(mb_substr((string) $u['nama'], 0, 1))); ?>
                                    </span>
                                    <span class="fw-bold"><?= html_escape($u['nama']); ?></span>
                                    <?php if ((int) $u['id'] === $id_saya): ?>
                                        <span class="badge-soft is-neutral">Akun Anda</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><code><?= html_escape($u['username']); ?></code></td>
                            <td>
                                <span class="badge-soft <?= $kelas_role[$u['role']] ?? 'is-neutral'; ?>">
                                    <?= $label_role[$u['role']] ?? html_escape($u['role']); ?>
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditUser<?= $u['id']; ?>">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i> Ubah
                                </button>

                                <?php if ((int) $u['id'] === $id_saya): ?>
                                    <button type="button" class="btn btn-outline-danger btn-sm" disabled title="Akun yang sedang dipakai tidak bisa dihapus">
                                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Hapus
                                    </button>
                                <?php else: ?>
                                    <a href="<?= site_url('owner/hapus_user/' . $u['id']); ?>" class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Hapus akun ini?');">
                                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-users-slash" aria-hidden="true"></i>
            <h4>Belum ada user</h4>
            <p>Tambahkan akun untuk kasir, dapur, atau owner.</p>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($users)): ?>
    <?php foreach ($users as $u): ?>
        <div class="modal fade" id="modalEditUser<?= $u['id']; ?>" tabindex="-1" aria-labelledby="modalEditUserLabel<?= $u['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= site_url('owner/edit_user/' . $u['id']); ?>" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditUserLabel<?= $u['id']; ?>">Ubah <?= html_escape($u['nama']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="nama_<?= $u['id']; ?>">Nama</label>
                                <input type="text" id="nama_<?= $u['id']; ?>" name="nama" class="form-control" value="<?= html_escape($u['nama']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="username_<?= $u['id']; ?>">Username</label>
                                <input type="text" id="username_<?= $u['id']; ?>" name="username"
                                       class="form-control <?= $this->session->flashdata('error_username_' . $u['id']) ? 'is-invalid' : ''; ?>"
                                       value="<?= html_escape($u['username']); ?>" required>
                                <?php if ($this->session->flashdata('error_username_' . $u['id'])): ?>
                                    <div class="invalid-feedback">
                                        <?= html_escape($this->session->flashdata('error_username_' . $u['id'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password_<?= $u['id']; ?>">Password baru</label>
                                <input type="password" id="password_<?= $u['id']; ?>" name="password" class="form-control" autocomplete="new-password" placeholder="Kosongkan bila tidak diubah">
                            </div>

                            <div class="mb-0">
                                <label class="form-label" for="role_<?= $u['id']; ?>">Hak akses</label>
                                <select id="role_<?= $u['id']; ?>" name="role" class="form-select" required>
                                    <?php foreach ($label_role as $nilai => $label): ?>
                                        <option value="<?= $nilai; ?>" <?= $u['role'] === $nilai ? 'selected' : ''; ?>><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
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

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('owner/tambah_user'); ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahUserLabel">Tambah pengguna baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="nama_baru">Nama</label>
                        <input type="text" id="nama_baru" name="nama" class="form-control" required placeholder="Contoh: Siti Karyawan"
                               value="<?= html_escape(isset($lama['nama']) ? $lama['nama'] : ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="username_baru">Username</label>
                        <input type="text" id="username_baru" name="username" autocomplete="off"
                               class="form-control <?= $this->session->flashdata('error_username') ? 'is-invalid' : ''; ?>"
                               required placeholder="Contoh: kasir2"
                               value="<?= html_escape(isset($lama['username']) ? $lama['username'] : ''); ?>">
                        <?php if ($this->session->flashdata('error_username')): ?>
                            <div class="invalid-feedback"><?= html_escape($this->session->flashdata('error_username')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_baru">Password</label>
                        <input type="password" id="password_baru" name="password" class="form-control" required autocomplete="new-password">
                    </div>

                    <div class="mb-0">
                        <label class="form-label" for="role_baru">Hak akses</label>
                        <select id="role_baru" name="role" class="form-select" required>
                            <option value="kasir">Kasir</option>
                            <option value="dapur">Dapur</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan user</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($this->session->flashdata('modal_target')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('<?= $this->session->flashdata('modal_target'); ?>');
            if (el) new bootstrap.Modal(el).show();
        });
    </script>
<?php endif; ?>
