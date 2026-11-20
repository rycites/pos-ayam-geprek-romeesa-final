<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-head">
    <div>
        <h2><?= count($pesanan); ?> item pesanan menunggu</h2>
    </div>
    <span class="badge-soft is-ok">
        <span class="dot" aria-hidden="true"></span> Dapur aktif, diperbarui tiap 10 detik
    </span>
</div>

<?php if (!empty($pesanan)): ?>
    <div class="ticket-grid">
        <?php foreach ($pesanan as $p): ?>
            <?php $jumlah = isset($p['jumlah']) ? $p['jumlah'] : (isset($p['qty']) ? $p['qty'] : 1); ?>
            <article class="ticket">
                <div class="ticket-head">
                    <span class="ticket-num">#<?= isset($p['no_faktur']) ? html_escape($p['no_faktur']) : '-'; ?></span>
                    <span class="badge-soft is-warn">
                        <i class="fa-solid fa-chair" aria-hidden="true"></i>
                        <?= isset($p['nomor_meja']) ? html_escape($p['nomor_meja']) : '-'; ?>
                    </span>
                </div>

                <div class="ticket-body">
                    <div class="ticket-item">
                        <?php if (!empty($p['foto'])): ?>
                            <img class="ticket-thumb" src="<?= base_url('uploads/' . $p['foto']); ?>" alt="<?= html_escape($p['nama_menu']); ?>">
                        <?php else: ?>
                            <span class="ticket-thumb is-empty"><i class="fa-solid fa-utensils" aria-hidden="true"></i></span>
                        <?php endif; ?>

                        <div class="me-auto">
                            <div class="fw-bold"><?= isset($p['nama_menu']) ? html_escape($p['nama_menu']) : 'Menu tidak ditemukan'; ?></div>
                            <div class="text-muted small">
                                <i class="fa-regular fa-clock me-1" aria-hidden="true"></i>
                                <?= isset($p['tanggal']) ? date('H:i', strtotime($p['tanggal'])) : '-'; ?> WIB
                            </div>
                        </div>

                        <span class="qty-badge">x<?= $jumlah; ?></span>
                    </div>
                </div>

                <div class="ticket-foot">
                    <a href="<?= site_url('kitchen/selesai/' . (isset($p['id_detail']) ? $p['id_detail'] : '')); ?>" class="btn btn-primary w-100">
                        <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i> Tandai selesai
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-mug-hot" aria-hidden="true"></i>
            <h4>Dapur sedang kosong</h4>
            <p>Semua pesanan sudah selesai. Pesanan baru akan muncul di sini tanpa perlu menyegar manual.</p>
        </div>
    </div>
<?php endif; ?>
