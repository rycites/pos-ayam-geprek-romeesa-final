<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ayam Geprek Romeesa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 380px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            background: #ffffff;
            overflow: hidden;
            border-top: 5px solid #dc3545;
        }
        .login-body {
            padding: 30px;
        }
        .brand-title {
            font-weight: 700;
            color: #212529;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
        }
        .form-control {
            padding: 10px 14px;
            font-size: 0.9rem;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-body">
            
            <!-- Judul & Identitas Warung -->
            <div class="text-center mb-4">
                <div class="mb-2">
                    <span class="badge bg-danger p-2 rounded-circle"><i class="fa-solid fa-utensils text-white"></i></span>
                </div>
                <h4 class="brand-title mb-1">Ayam Geprek Romeesa</h4>
                <p class="text-muted small mb-0">Silakan login untuk masuk ke sistem</p>
            </div>

            <!-- Alert Gagal Login -->
            <?php if($this->session->flashdata('msg')): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2 small mb-3" role="alert">
                    <?= $this->session->flashdata('msg'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form action="<?= site_url('auth/loginProcess'); ?>" method="POST">
                
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autocomplete="off" placeholder="Masukkan username">
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                </div>

                <button type="submit" class="btn btn-danger w-100 shadow-sm">
                    Masuk ke Sistem
                </button>
            </form>

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>