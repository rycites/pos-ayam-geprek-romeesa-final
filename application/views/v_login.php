<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Ayam Geprek Romeesa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>
<body>
<div class="login-page">

    <section class="login-brand">
        <div class="brand">
            <span class="brand-mark"><i class="fa-solid fa-utensils" aria-hidden="true"></i></span>
            <span class="brand-text">
                <strong>Geprek Romeesa</strong>
                <small>Sistem Kasir &amp; Pesanan</small>
            </span>
        </div>

        <div>
            <h1>Satu sistem untuk kasir, dapur, dan pemilik.</h1>
            <p>Setiap peran masuk dengan halaman kerjanya sendiri, jadi tidak ada data yang tercampur.</p>

            <div class="login-points">
                <div><i class="fa-solid fa-chart-pie" aria-hidden="true"></i> <span>Owner memantau penjualan, menu, dan akun staf</span></div>
                <div><i class="fa-solid fa-cash-register" aria-hidden="true"></i> <span>Kasir memproses pesanan meja dan pesanan QR</span></div>
                <div><i class="fa-solid fa-fire-burner" aria-hidden="true"></i> <span>Dapur melihat antrean yang harus dimasak</span></div>
            </div>
        </div>
    </section>

    <section class="login-panel">
        <div class="login-card">
            <h2>Masuk ke sistem</h2>
            <p class="lead">Gunakan username dan password yang diberikan pemilik restoran.</p>

            <?php if ($this->session->flashdata('msg')): ?>
                <div class="alert alert-danger mb-3" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span><?= html_escape($this->session->flashdata('msg')); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('auth/loginProcess'); ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus
                           autocomplete="username" placeholder="Masukkan username">
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required
                               autocomplete="current-password" placeholder="Masukkan password">
                        <button class="btn btn-light" type="button" id="togglePassword" aria-label="Tampilkan password" aria-pressed="false">
                            <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">Masuk ke sistem</button>
            </form>

            <div class="role-hint mt-4">
                Bagian yang bisa diakses ditentukan oleh peran akun: <code>owner</code>, <code>kasir</code>, atau <code>dapur</code>.
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        var input = document.getElementById('password');
        var tombol = document.getElementById('togglePassword');
        if (!input || !tombol) return;
        tombol.addEventListener('click', function () {
            var tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            tombol.setAttribute('aria-pressed', tampil ? 'true' : 'false');
            tombol.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
            tombol.querySelector('i').className = tampil ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    })();
</script>
</body>
</html>
