<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$daftar_sambal = isset($sambal) && is_array($sambal) ? $sambal : [];
?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-warning mb-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('error')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="page-head">
    <div>
        <h2><?= count($daftar_sambal); ?> sambal tersedia</h2>
        <p>Jenis sambal yang dapat dipilih pelanggan pada menu makanan.</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="<?= site_url('owner/menu'); ?>" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Kembali ke menu
        </a>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSambal">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Tambah sambal
        </button>
    </div>
</div>

<div class="card overflow-hidden">
    <?php if (!empty($daftar_sambal)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Nama sambal</th>
                        <th class="text-end" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; foreach ($daftar_sambal as $s): ?>
                        <tr>
                            <td class="text-muted num"><?= $no++; ?></td>

                            <td class="fw-bold">
                                <?= html_escape($s['nama_sambal']); ?>
                            </td>

                            <td class="text-end text-nowrap" style="width: 210px;">
                                <button
                                    type="button"
                                    class="btn btn-light btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditSambal<?= (int) $s['id']; ?>"
                                >
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i> Ubah
                                </button>

                                <form
                                    action="<?= site_url('owner/hapus_sambal/' . (int) $s['id']); ?>"
                                    method="POST"
                                    class="d-inline"
                                >
                                    <input
                                        type="hidden"
                                        name="<?= $this->security->get_csrf_token_name(); ?>"
                                        value="<?= $this->security->get_csrf_hash(); ?>"
                                    >
                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Hapus sambal ini dari daftar?');"
                                    >
                                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="px-3 py-2 border-top">
            <p class="text-muted small mb-0">
                Sambal yang dihapus tidak lagi muncul di pilihan pelanggan. Riwayat pesanan lama tetap menyimpan nama sambal.
            </p>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-pepper-hot" aria-hidden="true"></i>
            <h4>Belum ada sambal</h4>
            <p>Tambahkan sambal agar menu makanan bisa menawarkan pilihan sambal.</p>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($daftar_sambal)): ?>
    <?php foreach ($daftar_sambal as $s): ?>
        <div
            class="modal fade"
            id="modalEditSambal<?= (int) $s['id']; ?>"
            tabindex="-1"
            aria-labelledby="modalEditSambalLabel<?= (int) $s['id']; ?>"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form
                        action="<?= site_url('owner/edit_sambal/' . (int) $s['id']); ?>"
                        method="POST"
                    >
                        <input
                            type="hidden"
                            name="<?= $this->security->get_csrf_token_name(); ?>"
                            value="<?= $this->security->get_csrf_hash(); ?>"
                        >

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditSambalLabel<?= (int) $s['id']; ?>">Ubah sambal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-0">
                                <label class="form-label" for="nama_sambal_<?= (int) $s['id']; ?>">Nama sambal</label>
                                <input
                                    type="text"
                                    id="nama_sambal_<?= (int) $s['id']; ?>"
                                    name="nama_sambal"
                                    class="form-control"
                                    value="<?= html_escape($s['nama_sambal']); ?>"
                                    maxlength="100"
                                    required
                                >
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

<div class="modal fade" id="modalTambahSambal" tabindex="-1" aria-labelledby="modalTambahSambalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="<?= site_url('owner/tambah_sambal'); ?>" method="POST">
                <input
                    type="hidden"
                    name="<?= $this->security->get_csrf_token_name(); ?>"
                    value="<?= $this->security->get_csrf_hash(); ?>"
                >

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahSambalLabel">Tambah sambal baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label" for="nama_sambal_baru">Nama sambal</label>
                        <input
                            type="text"
                            id="nama_sambal_baru"
                            name="nama_sambal"
                            class="form-control"
                            maxlength="100"
                            required
                            placeholder="Contoh: Sambal Ijo"
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah sambal</button>
                </div>
            </form>

        </div>
    </div>
</div>
