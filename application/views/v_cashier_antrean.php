<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger mb-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('error')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="page-head">
    <div>
        <h2>Menunggu pembayaran</h2>
        <p>Pesanan mandiri dari QR meja. Setelah dikonfirmasi, pesanan langsung masuk ke dapur.</p>
    </div>
    <span class="badge-soft is-warn">
        <i class="fa-solid fa-rotate" aria-hidden="true"></i> Diperbarui tiap 10 detik
    </span>
</div>

<?php if (!empty($pesanan_pending)): ?>
    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No faktur</th>
                        <th>Waktu pesan</th>
                        <th>Meja &amp; pemesan</th>
                        <th class="num text-end">Total tagihan</th>
                        <th style="min-width: 300px;">Konfirmasi pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan_pending as $p): ?>
                        <tr>
                            <td><span class="badge-soft is-neutral"><?= html_escape($p['no_faktur']); ?></span></td>

                            <td class="text-muted small">
                                <i class="fa-regular fa-clock me-1" aria-hidden="true"></i><?= date('H:i', strtotime($p['tanggal'])); ?> WIB
                            </td>

                            <td class="fw-bold"><?= html_escape($p['nomor_meja']); ?></td>

                            <td class="num text-end fw-bold">Rp <?= number_format($p['total_bayar'], 0, ',', '.'); ?></td>

                            <td>
                                <form action="<?= site_url('cashier/bayar_pesanan_meja/' . $p['id_transaksi']); ?>" method="POST" class="d-flex flex-column gap-2">
                                    <div class="d-flex gap-2">
                                        <select name="metode_pembayaran" class="form-select form-select-sm"
                                                data-uang="input_uang_<?= $p['id_transaksi']; ?>"
                                                aria-label="Metode pembayaran untuk <?= html_escape($p['no_faktur']); ?>">
                                            <option value="Cash">Tunai</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                        <input type="number" name="uang_bayar" id="input_uang_<?= $p['id_transaksi']; ?>"
                                               class="form-control form-control-sm" min="0" placeholder="Uang bayar" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-check me-1" aria-hidden="true"></i> Konfirmasi pembayaran
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-mug-hot" aria-hidden="true"></i>
            <h4>Tidak ada antrean meja</h4>
            <p>Belum ada pesanan QR yang menunggu pembayaran. Halaman ini menyegar sendiri setiap 10 detik.</p>
        </div>
    </div>
<?php endif; ?>

<script>
    (function () {
        var pilihan = document.querySelectorAll('select[data-uang]');
        Array.prototype.forEach.call(pilihan, function (pilih) {
            var input = document.getElementById(pilih.getAttribute('data-uang'));
            if (!input) return;

            function sinkron() {
                var tunai = pilih.value === 'Cash';
                input.hidden = !tunai;
                input.required = tunai;
                if (!tunai) input.value = '';
            }

            pilih.addEventListener('change', sinkron);
            sinkron();
        });
    })();
</script>
