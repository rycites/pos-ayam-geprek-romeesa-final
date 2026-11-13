<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dapur - Ayam Geprek Romeesa'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #ffffff;
            color: #333;
            border-right: 1px solid #dee2e6;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #ffffff;
            border-bottom: 1px solid #f1f3f5;
            font-size: 1rem;
            font-weight: 700;
            color: #212529;
        }
        #sidebar ul.components {
            padding: 15px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.9rem;
            display: block;
            color: #495057;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }
        #sidebar ul li a:hover, #sidebar ul li.active a {
            color: #dc3545;
            background: #fff5f5;
            border-left-color: #dc3545;
        }
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        #content {
            width: 100%;
            padding: 25px;
            min-height: 100vh;
        }
        .top-navbar {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border: 1px solid #eaeaea;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <!-- SIDEBAR KIRI DAPUR -->
        <nav id="sidebar" class="min-vh-100 shadow-sm">
            <div class="sidebar-header d-flex align-items-center">
                <span class="badge bg-danger p-2 rounded-circle me-2"><i class="fa-solid fa-fire-burner text-white"></i></span>
                <div>
                    <div class="fw-bold" style="font-size: 0.95rem;">Geprek Romeesa</div>
                    <small class="text-muted" style="font-size: 0.75rem;">Panel Dapur</small>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="text-muted px-3 small fw-bold mb-2 text-uppercase" style="font-size: 0.7rem;">Menu Dapur</li>
                
                <li>
                    <a href="<?= site_url('kitchen'); ?>" class="d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-list-check"></i> Pesanan Masuk</span>
                        <?php 
                            $ci =& get_instance();
                            $jml_pesanan = $ci->db->where('status', 'pending')->count_all_results('detail_transaksi');
                        ?>
                        <?php if($jml_pesanan > 0): ?>
                            <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;"><?= $jml_pesanan; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <hr class="text-secondary mx-3 my-3 opacity-25">
                
                <li>
                    <a href="<?= site_url('auth/logout'); ?>" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Keluar Sistem</a>
                </li>
            </ul>
        </nav>

        <!-- KONTEN UTAMA DI KANAN -->
        <div id="content">
            <!-- Topbar Dapur -->
            <nav class="navbar navbar-expand-lg top-navbar px-4 mb-4 py-3">
                <div class="container-fluid px-0">
                    <span class="fw-bold text-dark fs-6"><i class="fa-solid fa-fire me-2 text-danger"></i> <?= isset($page_title) ? $page_title : 'Dashboard Dapur'; ?></span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-medium">
                            <i class="fa-solid fa-user-shield me-1"></i> Role: Dapur
                        </span>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                            <i class="fa-solid fa-user text-secondary me-1"></i> <?= $this->session->userdata('nama'); ?>
                        </span>
                    </div>
                </div>
            </nav>

            <!-- KONTEN DINAMIS -->
            <div class="container-fluid px-0">
                <?php 
                    if(isset($subview)) {
                        $this->load->view($subview);
                    }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>