
<div class="container-fluid px-4 py-3">

    <!-- HEADER -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Pengaturan Tampilan Login</h2>
        <p class="text-muted mb-0">
            Atur foto background yang tampil pada halaman login Geprek Romeesa.
        </p>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa-solid fa-circle-check me-2"></i>
            <?= html_escape($this->session->flashdata('success')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <?= html_escape($this->session->flashdata('error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- KOLOM PREVIEW -->
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100"
                style="border-radius:18px; overflow:hidden;">

                <div class="card-header bg-white border-0 px-4 pt-4">
                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-image me-2 text-primary"></i>
                        Preview Background
                    </h5>

                    <p class="text-muted small mb-0">
                        Tampilan foto yang akan digunakan pada halaman login.
                    </p>
                </div>

                <div class="card-body p-4">

                    <!-- PREVIEW -->
                    <div id="loginPreview"
                        style="
                            position:relative;
                            width:100%;
                            min-height:390px;
                            border-radius:16px;
                            overflow:hidden;
                            background:linear-gradient(135deg,#211744,#382765);
                            display:flex;
                            flex-direction:column;
                            justify-content:space-between;
                            padding:28px;
                            color:white;
                        ">

                        <?php if (!empty($foto_login_url)): ?>

                            <img id="previewImage"
                                src="<?= html_escape($foto_login_url); ?>"
                                alt="Background Login"
                                style="
                                    position:absolute;
                                    inset:0;
                                    width:100%;
                                    height:100%;
                                    object-fit:cover;
                                ">

                        <?php else: ?>

                            <img id="previewImage"
                                src=""
                                alt="Preview Background Login"
                                style="
                                    position:absolute;
                                    inset:0;
                                    width:100%;
                                    height:100%;
                                    object-fit:cover;
                                    display:none;
                                ">

                        <?php endif; ?>

                        <!-- OVERLAY -->
                        <div style="
                            position:absolute;
                            inset:0;
                            background:linear-gradient(
                                90deg,
                                rgba(26,18,55,.92),
                                rgba(26,18,55,.55)
                            );
                        "></div>

                        <!-- KONTEN PREVIEW -->
                        <div style="position:relative;z-index:2;">

                            <div class="d-flex align-items-center gap-3 mb-4">

                                <div style="
                                    width:48px;
                                    height:48px;
                                    border-radius:14px;
                                    background:#f5d84c;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    color:#211744;
                                ">
                                    <i class="fa-solid fa-utensils fa-lg"></i>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-0 text-white">
                                        Geprek Romeesa
                                    </h5>

                                    <small style="color:#c9bde9;">
                                        Sistem Kasir &amp; Pesanan
                                    </small>
                                </div>

                            </div>

                        </div>

                        <div style="position:relative;z-index:2;max-width:420px;">

                            <h3 class="fw-bold text-white mb-3"
                                style="font-size:clamp(20px,3vw,30px);">
                                Satu sistem untuk kasir, dapur, dan pemilik.
                            </h3>

                            <p style="color:#e0d8f5;">
                                Setiap peran masuk dengan halaman kerjanya sendiri,
                                jadi tidak ada data yang tercampur.
                            </p>

                            <div class="small d-flex flex-column gap-2 mt-3">

                                <div>
                                    <i class="fa-solid fa-chart-pie me-2"
                                        style="color:#f5d84c;"></i>
                                    Owner memantau penjualan, menu, dan akun staf
                                </div>

                                <div>
                                    <i class="fa-solid fa-cash-register me-2"
                                        style="color:#f5d84c;"></i>
                                    Kasir memproses pesanan meja dan pesanan QR
                                </div>

                                <div>
                                    <i class="fa-solid fa-fire-burner me-2"
                                        style="color:#f5d84c;"></i>
                                    Dapur melihat antrean yang harus dimasak
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="alert alert-light border mt-3 mb-0 small">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>
                        Preview ini menggambarkan bagian kiri halaman login.
                        Form login di sebelah kanan tidak diubah.
                    </div>

                </div>
            </div>

        </div>


        <!-- KOLOM PENGATURAN -->
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm mb-4"
                style="border-radius:18px;">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-camera me-2 text-primary"></i>
                        Ganti Foto Login
                    </h5>

                    <p class="text-muted small mb-4">
                        Upload foto warung atau foto makanan untuk background
                        halaman login.
                    </p>

                    <!-- FORM UPLOAD -->
                    <form action="<?= site_url('owner/upload_foto_login'); ?>"
                        method="post"
                        enctype="multipart/form-data">

                        <?php if ($this->config->item('csrf_protection')): ?>
                            <input type="hidden"
                                name="<?= $this->security->get_csrf_token_name(); ?>"
                                value="<?= $this->security->get_csrf_hash(); ?>">
                        <?php endif; ?>

                        <div class="mb-3">

                            <label for="foto_login" class="form-label fw-semibold">
                                Pilih Foto
                            </label>

                            <input type="file"
                                name="foto_login"
                                id="foto_login"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp"
                                required>

                            <small class="text-muted d-block mt-2">
                                Format JPG, JPEG, PNG, atau WEBP.
                                Maksimal 5 MB.
                            </small>

                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 py-2"
                            style="border-radius:10px;">

                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                            Upload &amp; Simpan Foto

                        </button>

                    </form>

                    <hr class="my-4">

                    <!-- RESET FOTO -->
                    <h6 class="fw-bold mb-2">
                        Kembalikan ke Tampilan Default
                    </h6>

                    <p class="text-muted small">
                        Menghapus foto background yang aktif dan menggunakan
                        tampilan default login Romeesa.
                    </p>

                    <form action="<?= site_url('owner/hapus_foto_login'); ?>"
                        method="post"
                        onsubmit="return confirm('Yakin ingin menghapus foto background login dan mengembalikan tampilan default?');">

                        <?php if ($this->config->item('csrf_protection')): ?>
                            <input type="hidden"
                                name="<?= $this->security->get_csrf_token_name(); ?>"
                                value="<?= $this->security->get_csrf_hash(); ?>">
                        <?php endif; ?>

                        <button type="submit"
                            class="btn btn-outline-danger w-100 py-2"
                            style="border-radius:10px;">

                            <i class="fa-solid fa-rotate-left me-2"></i>
                            Reset Foto Login

                        </button>

                    </form>

                </div>
            </div>


            <!-- INFORMASI -->
            <div class="card border-0 shadow-sm"
                style="border-radius:18px;">

                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">
                        <i class="fa-solid fa-lightbulb me-2 text-warning"></i>
                        Tips Foto Background
                    </h6>

                    <ul class="text-muted small mb-0 ps-3">

                        <li class="mb-2">
                            Gunakan foto warung atau makanan dengan kualitas yang jelas.
                        </li>

                        <li class="mb-2">
                            Gunakan foto landscape agar sesuai dengan panel login.
                        </li>

                        <li class="mb-2">
                            Hindari foto yang terlalu ramai agar tulisan tetap mudah dibaca.
                        </li>

                        <li>
                            Overlay gelap akan membantu menjaga keterbacaan teks.
                        </li>

                    </ul>

                </div>
            </div>

        </div>

    </div>

</div>


<!-- PREVIEW FOTO SEBELUM UPLOAD -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputFoto = document.getElementById('foto_login');
    const previewImage = document.getElementById('previewImage');

    if (!inputFoto || !previewImage) {
        return;
    }

    inputFoto.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert('File yang dipilih harus berupa gambar.');
            this.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran foto maksimal 5 MB.');
            this.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            previewImage.src = event.target.result;
            previewImage.style.display = 'block';
        };

        reader.readAsDataURL(file);

    });

});
</script>
