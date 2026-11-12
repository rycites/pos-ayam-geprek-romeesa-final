<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-4" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-danger">Ayam Geprek Romeesa</h3>
                        <p class="text-muted">Silakan login sesuai akun Anda</p>
                    </div>

                    <?php if($this->session->flashdata('msg')): ?>
                        <div class="alert alert-danger py-2" role="alert">
                            <?= $this->session->flashdata('msg'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('auth/loginProcess'); ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>