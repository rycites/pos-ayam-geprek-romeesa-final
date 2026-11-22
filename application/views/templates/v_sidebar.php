<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$ci      =& get_instance();
$segmen  = $ci->uri->segment(2);

// Menentukan menu yang sedang aktif berdasarkan segment URL ke-2
if ($segmen === 'menu') {
    $aktif = 'menu';
} elseif ($segmen === 'users') {
    $aktif = 'users';
} elseif ($segmen === 'meja') {
    $aktif = 'meja';
} else {
    $aktif = 'laporan';
}

$nama     = (string) $ci->session->userdata('nama');
$username = (string) $ci->session->userdata('username');
$inisial  = strtoupper(mb_substr($nama !== '' ? $nama : $username, 0, 1));

$hari_ini = $ci->db
    ->select('COUNT(*) AS jumlah, COALESCE(SUM(total_bayar), 0) AS total')
    ->where('status_bayar', 'Lunas')
    ->where('DATE(tanggal) = CURDATE()', NULL, FALSE)
    ->get('transaksi')
    ->row();

$nama_bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$tanggal_ini = date('d') . ' ' . $nama_bulan[(int) date('n')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($page_title) ? html_escape($page_title) : 'Panel Owner'; ?> - Ayam Geprek Romeesa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>
<body>
<div class="app">
    <aside class="app-sidebar" id="appSidebar" aria-label="Navigasi utama">
        <div class="brand">
            <span class="brand-mark"><i class="fa-solid fa-utensils" aria-hidden="true"></i></span>
            <span class="brand-text">
                <strong>Geprek Romeesa</strong>
                <small>Panel Owner</small>
            </span>
            <button type="button" class="sidebar-close" data-sidebar-toggle aria-label="Tutup navigasi">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="nav-group">
            <p class="nav-group-title">Menu Utama</p>

            <a class="nav-item<?= $aktif === 'laporan' ? ' is-active' : '' ?>" href="<?= site_url('owner'); ?>"<?= $aktif === 'laporan' ? ' aria-current="page"' : '' ?>>
                <i class="fa-solid fa-chart-pie" aria-hidden="true"></i> <span>Laporan Penjualan</span>
            </a>

            <a class="nav-item<?= $aktif === 'menu' ? ' is-active' : '' ?>" href="<?= site_url('owner/menu'); ?>"<?= $aktif === 'menu' ? ' aria-current="page"' : '' ?>>
                <i class="fa-solid fa-burger" aria-hidden="true"></i> <span>Kelola Menu</span>
            </a>

            <!-- Menu QR Code Meja yang baru ditambahkan -->
            <a class="nav-item<?= $aktif === 'meja' ? ' is-active' : '' ?>" href="<?= site_url('owner/meja'); ?>"<?= $aktif === 'meja' ? ' aria-current="page"' : '' ?>>
                <i class="fa-solid fa-qrcode" aria-hidden="true"></i> <span>QR Code Meja</span>
            </a>

            <a class="nav-item<?= $aktif === 'users' ? ' is-active' : '' ?>" href="<?= site_url('owner/users'); ?>"<?= $aktif === 'users' ? ' aria-current="page"' : '' ?>>
                <i class="fa-solid fa-users-gear" aria-hidden="true"></i> <span>Manajemen User</span>
            </a>
        </nav>

        <div class="sidebar-foot">
            <div class="sidebar-note">
                <span class="note-label">Pendapatan hari ini</span>
                <div class="note-value">Rp <?= number_format($hari_ini->total, 0, ',', '.'); ?></div>
                <div class="note-sub"><?= $hari_ini->jumlah; ?> transaksi lunas</div>
            </div>
        </div>
    </aside>

    <div class="app-main">
        <header class="app-topbar">
            <button type="button" class="icon-btn sidebar-toggle" data-sidebar-toggle aria-controls="appSidebar" aria-expanded="false" aria-label="Buka navigasi">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>

            <div class="topbar-title">
                <h1><?= isset($page_title) ? html_escape($page_title) : 'Panel Owner'; ?></h1>
                <p>Ringkasan operasional restoran</p>
            </div>

            <div class="topbar-actions">
                <span class="badge-soft is-neutral d-none d-lg-inline-flex">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= $tanggal_ini; ?>
                </span>

                <div class="dropdown">
                    <button class="user-chip" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar"><?= html_escape($inisial); ?></span>
                        <span class="user-text">
                            <span class="user-name"><?= html_escape($nama); ?></span>
                            <span class="user-role">Owner</span>
                        </span>
                        <i class="fa-solid fa-chevron-down" style="font-size:.68rem" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted">Masuk sebagai <strong><?= html_escape($username); ?></strong></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= site_url('auth/logout'); ?>">
                                <i class="fa-solid fa-right-from-bracket me-2" aria-hidden="true"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="app-content">
            <?php if (isset($subview)) { $this->load->view($subview); } ?>
        </main>
    </div>
</div>

<div class="app-backdrop" data-sidebar-backdrop hidden></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>