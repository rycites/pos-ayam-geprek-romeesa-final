
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i>
        <?= html_escape($this->session->flashdata('success')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger mb-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-1" aria-hidden="true"></i>
        <?= html_escape($this->session->flashdata('error')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="page-head">
    <div>
        <h2>Menunggu pembayaran</h2>
        <p>
            Pesanan mandiri dari QR meja. Setelah pembayaran dikonfirmasi,
            pesanan langsung diteruskan ke dapur.
        </p>
    </div>

    <span class="badge-soft is-warn">
        <i class="fa-solid fa-rotate me-1" aria-hidden="true"></i>
        Diperbarui tiap 10 detik
    </span>
</div>

<?php if (!empty($pesanan_pending)): ?>

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Waktu Pesan</th>
                        <th>Meja &amp; Pemesan</th>
                        <th class="num text-end">Total Tagihan</th>
                        <th style="min-width: 260px;">Konfirmasi Pembayaran</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($pesanan_pending as $p): ?>

                        <?php
                        $metode = strtoupper(
                            trim($p['metode_pembayaran'] ?? '')
                        );

                        $is_cash = ($metode === 'CASH');
                        $is_qris = ($metode === 'QRIS');

                        $total = (float)($p['total_bayar'] ?? 0);

                        $waktu = !empty($p['tanggal'])
                            ? strtotime($p['tanggal'])
                            : false;
                        ?>

                        <tr>
                            <!-- NO FAKTUR -->
                            <td>
                                <span class="badge-soft is-neutral">
                                    <?= html_escape($p['no_faktur'] ?? '-'); ?>
                                </span>
                            </td>

                            <!-- WAKTU PESAN -->
                            <td class="text-muted small">
                                <i class="fa-regular fa-clock me-1" aria-hidden="true"></i>
                                <?= $waktu ? date('H:i', $waktu) . ' WIB' : '-'; ?>
                            </td>

                            <!-- MEJA DAN PEMESAN -->
                            <td class="fw-bold">
                                <?= html_escape($p['nomor_meja'] ?? '-'); ?>
                            </td>

                            <!-- TOTAL TAGIHAN -->
                            <td class="num text-end fw-bold">
                                Rp <?= number_format($total, 0, ',', '.'); ?>
                            </td>

                            <!-- KONFIRMASI -->
                            <td>
                                <?php if ($is_cash || $is_qris): ?>

                                    <form
                                        action="<?= site_url('cashier/bayar_pesanan_meja/' . (int)$p['id_transaksi']); ?>"
                                        method="POST"
                                        class="d-flex flex-column gap-2"
                                        onsubmit="return konfirmasiPembayaran(this);"
                                    >

                                        <input
                                            type="hidden"
                                            name="<?= $this->security->get_csrf_token_name(); ?>"
                                            value="<?= $this->security->get_csrf_hash(); ?>"
                                        >

                                        <!-- METODE PEMBAYARAN -->
                                        <div>
                                            <?php if ($is_qris): ?>
                                                <span class="badge-soft is-neutral">
                                                    <i class="fa-solid fa-qrcode me-1" aria-hidden="true"></i>
                                                    QRIS
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-soft is-neutral">
                                                    <i class="fa-solid fa-money-bill-wave me-1" aria-hidden="true"></i>
                                                    Cash
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- INPUT UANG CASH -->
                                        <?php if ($is_cash): ?>
                                            <input
                                                type="number"
                                                name="uang_bayar"
                                                class="form-control form-control-sm"
                                                min="<?= (int)$total; ?>"
                                                step="1"
                                                placeholder="Uang diterima"
                                                required
                                            >
                                        <?php endif; ?>

                                        <!-- TOMBOL KONFIRMASI -->
                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-sm"
                                        >
                                            <i class="fa-solid fa-check me-1" aria-hidden="true"></i>
                                            Konfirmasi pembayaran
                                        </button>

                                    </form>

                                <?php else: ?>

                                    <span class="badge-soft is-warn">
                                        <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>
                                        Metode tidak dikenali
                                    </span>

                                    <small class="text-muted d-block mt-1">
                                        Metode:
                                        <?= html_escape($p['metode_pembayaran'] ?? '-'); ?>
                                    </small>

                                <?php endif; ?>
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
            <p>
                Belum ada pesanan QR yang menunggu pembayaran.
                Pesanan baru akan muncul ketika pelanggan melakukan checkout.
            </p>
        </div>
    </div>

<?php endif; ?>

<script>
function konfirmasiPembayaran(form) {
    var tombol = form.querySelector('button[type="submit"]');
    var inputUang = form.querySelector('[name="uang_bayar"]');

    // ==========================================
    // VALIDASI CASH
    // ==========================================
    if (inputUang) {
        var uang = parseFloat(inputUang.value);
        var minimal = parseFloat(inputUang.min);

        if (
            inputUang.value === '' ||
            isNaN(uang) ||
            uang < minimal
        ) {
            alert('Nominal uang diterima tidak boleh kurang dari total tagihan.');
            inputUang.focus();
            return false;
        }

        var lanjutCash = confirm(
            'Pastikan uang tunai sudah diterima dengan benar.\n\n' +
            'Lanjut konfirmasi pembayaran?'
        );

        if (!lanjutCash) {
            return false;
        }
    }

    // ==========================================
    // KONFIRMASI QRIS
    // ==========================================
    else {
        var lanjutQris = confirm(
            'Pastikan pembayaran QRIS benar-benar sudah diterima.\n\n' +
            'Lanjut konfirmasi pembayaran?'
        );

        if (!lanjutQris) {
            return false;
        }
    }

    // ==========================================
    // CEGAH KLIK BERULANG
    // ==========================================
    if (tombol) {
        tombol.disabled = true;
        tombol.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin me-1"></i>' +
            ' Memproses pembayaran...';
    }

    return true;
}
</script>
