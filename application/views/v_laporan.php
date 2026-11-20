<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$transaksi = isset($transaksi) ? $transaksi : [];
$role      = $this->session->userdata('role');
$is_owner  = ($role === 'owner');

// Ringkasan dihitung dari data yang sedang tampil
$jumlah      = count($transaksi);
$grand_total = 0;
$jml_cash    = 0;
$jml_qris    = 0;
foreach ($transaksi as $t) {
    $grand_total += $t['total_bayar'];
    if (strtolower(isset($t['metode_pembayaran']) ? $t['metode_pembayaran'] : '') === 'qris') {
        $jml_qris++;
    } else {
        $jml_cash++;
    }
}
$rata_rata = $jumlah > 0 ? $grand_total / $jumlah : 0;

$qs = $this->input->server('QUERY_STRING');
$url_cetak = site_url('owner/cetak_laporan') . ($qs ? '?' . $qs : '');

$nama_bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$periode = date('d', strtotime($tanggal_mulai)) . ' ' . $nama_bulan[(int) date('n', strtotime($tanggal_mulai))] . ' ' . date('Y', strtotime($tanggal_mulai))
         . ' sampai ' .
           date('d', strtotime($tanggal_selesai)) . ' ' . $nama_bulan[(int) date('n', strtotime($tanggal_selesai))] . ' ' . date('Y', strtotime($tanggal_selesai));
?>
<div class="page-head">
    <p class="text-muted mb-0">
        <i class="fa-regular fa-calendar me-1" aria-hidden="true"></i>
        Periode <?= $periode; ?>
    </p>
</div>

<div class="stat-grid mb-4">
    <div class="stat-card">
        <span class="stat-icon"><i class="fa-solid fa-sack-dollar" aria-hidden="true"></i></span>
        <div>
            <span class="stat-label">Total pendapatan</span>
            <p class="stat-value money">Rp <?= number_format($grand_total, 0, ',', '.'); ?></p>
            <p class="stat-sub"><?= $jumlah; ?> transaksi lunas</p>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon is-ok"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>
        <div>
            <span class="stat-label">Rata-rata</span>
            <p class="stat-value money">Rp <?= number_format($rata_rata, 0, ',', '.'); ?></p>
            <p class="stat-sub">per transaksi</p>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon is-accent"><i class="fa-solid fa-money-bill-wave" aria-hidden="true"></i></span>
        <div>
            <span class="stat-label">Tunai</span>
            <p class="stat-value num"><?= $jml_cash; ?></p>
            <p class="stat-sub">transaksi bayar tunai</p>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon is-brand"><i class="fa-solid fa-qrcode" aria-hidden="true"></i></span>
        <div>
            <span class="stat-label">QRIS</span>
            <p class="stat-value num"><?= $jml_qris; ?></p>
            <p class="stat-sub">transaksi bayar QRIS</p>
        </div>
    </div>
</div>

<!-- Filter: tiap panel memakai parameter yang dibaca pengendalinya masing-masing -->
<div class="card mb-4">
    <div class="panel-body">
        <form method="GET" action="<?= site_url($this->uri->uri_string()); ?>" class="row g-3 align-items-end">
            <?php if ($is_owner): ?>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label" for="jenis">Kategori waktu</label>
                    <select name="jenis" id="jenis" class="form-select" onchange="toggleFilter()">
                        <option value="">Rentang tanggal</option>
                        <option value="harian" <?= ($this->input->get('jenis') == 'harian') ? 'selected' : ''; ?>>Harian</option>
                        <option value="mingguan" <?= ($this->input->get('jenis') == 'mingguan') ? 'selected' : ''; ?>>Mingguan (7 hari terakhir)</option>
                        <option value="bulanan" <?= ($this->input->get('jenis') == 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6" id="div-tanggal" hidden>
                    <label class="form-label" for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?= html_escape((string) $this->input->get('tanggal')); ?>" class="form-control">
                </div>

                <div class="col-lg-2 col-md-6" id="div-bulan" hidden>
                    <label class="form-label" for="bulan">Bulan</label>
                    <input type="month" id="bulan" name="bulan" value="<?= html_escape((string) $this->input->get('bulan')); ?>" class="form-control">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label" for="metode">Metode pembayaran</label>
                    <select name="metode" id="metode" class="form-select">
                        <option value="">Semua metode</option>
                        <option value="QRIS" <?= ($this->input->get('metode') == 'QRIS') ? 'selected' : ''; ?>>QRIS</option>
                        <option value="Cash" <?= ($this->input->get('metode') == 'Cash') ? 'selected' : ''; ?>>Tunai</option>
                    </select>
                </div>
            <?php else: ?>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label" for="tanggal_mulai">Dari tanggal</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= html_escape($tanggal_mulai); ?>" class="form-control">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label" for="tanggal_selesai">Sampai tanggal</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= html_escape($tanggal_selesai); ?>" class="form-control">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label" for="metode">Metode pembayaran</label>
                    <select name="metode" id="metode" class="form-select">
                        <option value="">Semua metode</option>
                        <option value="QRIS" <?= ($this->input->get('metode') == 'QRIS') ? 'selected' : ''; ?>>QRIS</option>
                        <option value="Cash" <?= ($this->input->get('metode') == 'Cash') ? 'selected' : ''; ?>>Tunai</option>
                    </select>
                </div>
            <?php endif; ?>

            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-filter me-1" aria-hidden="true"></i> Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="panel-head d-flex align-items-center justify-content-between">
        <h3>Daftar transaksi</h3>
        <?php if ($is_owner && !empty($transaksi)): ?>
            <a href="<?= $url_cetak; ?>" target="_blank" rel="noopener" class="btn btn-light btn-sm">
                <i class="fa-solid fa-print me-1" aria-hidden="true"></i> Cetak
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($transaksi)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>No. faktur</th>
                        <th>Tanggal &amp; waktu</th>
                        <th>Metode</th>
                        <th>Kasir / sumber</th>
                        <th class="num text-end">Total</th>
                        <th class="num text-end">Dibayar</th>
                        <th class="num text-end">Kembalian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($transaksi as $t): ?>
                        <tr>
                            <td class="text-muted num"><?= $no++; ?></td>
                            <td><span class="badge-soft is-neutral"><?= html_escape($t['no_faktur']); ?></span></td>
                            <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($t['tanggal'])); ?></td>
                            <td>
                                <?php if (strtolower(isset($t['metode_pembayaran']) ? $t['metode_pembayaran'] : '') === 'qris'): ?>
                                    <span class="badge-soft is-brand"><i class="fa-solid fa-qrcode" aria-hidden="true"></i> QRIS</span>
                                <?php else: ?>
                                    <span class="badge-soft is-warn"><i class="fa-solid fa-money-bill" aria-hidden="true"></i> Tunai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    if (!empty($t['nama_kasir'])) {
                                        echo html_escape($t['nama_kasir']);
                                    } elseif ((int) $t['id_kasir'] === 0) {
                                        echo '<span class="text-muted">Mandiri QR</span>';
                                    } else {
                                        echo '<span class="text-muted">Kasir #' . (int) $t['id_kasir'] . '</span>';
                                    }
                                ?>
                            </td>
                            <td class="num text-end fw-bold">Rp <?= number_format($t['total_bayar'], 0, ',', '.'); ?></td>
                            <td class="num text-end">Rp <?= number_format($t['uang_bayar'], 0, ',', '.'); ?></td>
                            <td class="num text-end">Rp <?= number_format($t['uang_kembalian'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="panel-foot d-flex align-items-center justify-content-between">
            <span class="fw-bold">Total pendapatan</span>
            <span class="fw-extrabold fs-5 money">Rp <?= number_format($grand_total, 0, ',', '.'); ?></span>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
            <h4>Tidak ada transaksi</h4>
            <p>Belum ada transaksi lunas pada rentang dan metode yang dipilih. Coba lebarkan rentang tanggalnya.</p>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_owner): ?>
    <script>
        (function () {
            var jenis = document.getElementById('jenis');
            var boxTanggal = document.getElementById('div-tanggal');
            var boxBulan = document.getElementById('div-bulan');

            function toggleFilter() {
                boxTanggal.hidden = jenis.value !== 'harian';
                boxBulan.hidden = jenis.value !== 'bulanan';
            }

            jenis.addEventListener('change', toggleFilter);
            toggleFilter();
        })();
    </script>
<?php endif; ?>
