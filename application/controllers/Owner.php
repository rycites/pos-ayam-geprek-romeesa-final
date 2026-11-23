
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Owner extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'password']);

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'owner') {
            $this->session->set_flashdata('msg', 'Akses ditolak! Khusus Owner.');
            redirect('auth');
            return;
        }
    }

    // ================= DASHBOARD =================

    public function index()
    {
        $this->laporan();
    }


    // ================= FITUR LAPORAN =================

    private function _terapkan_filter_laporan(
        $jenis,
        $tanggal,
        $bulan,
        $metode,
        $tanggal_mulai,
        $tanggal_selesai
    ) {
        if ($jenis == 'harian' && !empty($tanggal)) {
            $this->db->where('DATE(transaksi.tanggal)', $tanggal);
        } elseif ($jenis == 'mingguan') {
            $this->db->where(
                'transaksi.tanggal >=',
                date('Y-m-d 00:00:00', strtotime('-7 days'))
            );
        } elseif ($jenis == 'bulanan' && !empty($bulan)) {
            $this->db->like('transaksi.tanggal', $bulan, 'after');
        } else {
            $this->db->where('DATE(transaksi.tanggal) >=', $tanggal_mulai);
            $this->db->where('DATE(transaksi.tanggal) <=', $tanggal_selesai);
        }

        if (!empty($metode)) {
            $this->db->where('transaksi.metode_pembayaran', $metode);
        }
    }

    public function laporan()
    {
        $jenis   = $this->input->get('jenis', true);
        $tanggal = $this->input->get('tanggal', true);
        $bulan   = $this->input->get('bulan', true);
        $metode  = $this->input->get('metode', true);

        $tanggal_mulai   = $this->input->get('tanggal_mulai') ?: date('Y-m-01');
        $tanggal_selesai = $this->input->get('tanggal_selesai') ?: date('Y-m-d');

        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_kasir', 'left');
        $this->_terapkan_filter_laporan(
            $jenis,
            $tanggal,
            $bulan,
            $metode,
            $tanggal_mulai,
            $tanggal_selesai
        );
        $this->db->where("LOWER(transaksi.status_bayar) = 'lunas'");
        $this->db->order_by('transaksi.tanggal', 'DESC');

        $result = $this->db->get()->result_array();

        $data['transaksi'] = $result;
        $data['laporan']   = $result;

        $total_omset = 0;
        foreach ($result as $t) {
            $total_omset += $t['total_bayar'];
        }

        $data['total_omset']      = $total_omset;
        $data['total_pendapatan'] = $total_omset;
        $data['tanggal_mulai']     = $tanggal_mulai;
        $data['tanggal_selesai']   = $tanggal_selesai;

        $data['title']      = 'Laporan Keuangan - Ayam Geprek Romeesa';
        $data['page_title'] = 'Laporan Keuangan & Transaksi';
        $data['subview']    = 'v_laporan';

        $this->load->view('templates/v_sidebar', $data);
    }


    public function cetak_laporan()
    {
        $jenis   = $this->input->get('jenis', true);
        $tanggal = $this->input->get('tanggal', true);
        $bulan   = $this->input->get('bulan', true);
        $metode  = $this->input->get('metode', true);

        $tanggal_mulai   = $this->input->get('tanggal_mulai') ?: date('Y-m-01');
        $tanggal_selesai = $this->input->get('tanggal_selesai') ?: date('Y-m-d');

        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_kasir', 'left');
        $this->_terapkan_filter_laporan(
            $jenis,
            $tanggal,
            $bulan,
            $metode,
            $tanggal_mulai,
            $tanggal_selesai
        );
        $this->db->where("LOWER(transaksi.status_bayar) = 'lunas'");
        $this->db->order_by('transaksi.tanggal', 'ASC');

        $data['laporan'] = $this->db->get()->result_array();

        $this->load->view('v_owner_laporan_print', $data);
    }


    // ================= QR CODE MEJA =================

    public function meja()
    {
        $this->load->model('M_meja');

        $data['title']      = 'Manajemen QR Code Meja - Ayam Geprek Romeesa';
        $data['page_title'] = 'Manajemen QR Code Meja Pelanggan';
        $data['meja']       = $this->M_meja->get_data_meja();
        $data['subview']    = 'v_owner_meja';

        $this->load->view('templates/v_sidebar', $data);
    }


    // ================= PENGATURAN FOTO LOGIN =================

    public function pengaturan_login()
    {
        $folder = FCPATH . 'uploads/foto_halaman_login/';
        $file_penanda = $folder . 'active.txt';

        $foto_aktif = '';

        if (file_exists($file_penanda)) {
            $nama_file = basename(trim(file_get_contents($file_penanda)));

            if (!empty($nama_file) && file_exists($folder . $nama_file)) {
                $foto_aktif = $nama_file;
            }
        }

        $data['foto_login'] = $foto_aktif;

        if (!empty($foto_aktif)) {
            $data['foto_login_url'] = base_url(
                'uploads/foto_halaman_login/' . $foto_aktif
            ) . '?v=' . filemtime($folder . $foto_aktif);
        } else {
            $data['foto_login_url'] = '';
        }

        $data['title']      = 'Pengaturan Tampilan Login - Ayam Geprek Romeesa';
        $data['page_title'] = 'Pengaturan Tampilan Login';
        $data['subview']    = 'v_owner_pengaturan_login';

        $this->load->view('templates/v_sidebar', $data);
    }


    // ================= UPLOAD FOTO LOGIN =================

    public function upload_foto_login()
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/pengaturan_login');
            return;
        }

        if (empty($_FILES['foto_login']['name'])) {
            $this->session->set_flashdata('error', 'Silakan pilih foto terlebih dahulu.');
            redirect('owner/pengaturan_login');
            return;
        }

        $folder = FCPATH . 'uploads/foto_halaman_login/';

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        if (!is_writable($folder)) {
            $this->session->set_flashdata(
                'error',
                'Folder foto login tidak dapat ditulis.'
            );
            redirect('owner/pengaturan_login');
            return;
        }

        $config = [
            'upload_path'   => $folder,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size'     => 5120,
            'encrypt_name'  => true,
        ];

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto_login')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors('', '')
            );
            redirect('owner/pengaturan_login');
            return;
        }

        $upload_data = $this->upload->data();
        $nama_file_baru = $upload_data['file_name'];

        // Validasi file benar-benar berupa gambar
        if (!getimagesize($upload_data['full_path'])) {
            @unlink($upload_data['full_path']);

            $this->session->set_flashdata(
                'error',
                'File yang diunggah bukan gambar yang valid.'
            );
            redirect('owner/pengaturan_login');
            return;
        }

        $file_penanda = $folder . 'active.txt';
        $foto_lama = '';

        if (file_exists($file_penanda)) {
            $foto_lama = basename(trim(file_get_contents($file_penanda)));
        }

        // Simpan nama foto aktif
        $hasil_simpan = file_put_contents(
            $file_penanda,
            basename($nama_file_baru),
            LOCK_EX
        );

        if ($hasil_simpan === false) {
            @unlink($upload_data['full_path']);

            $this->session->set_flashdata(
                'error',
                'Gagal menyimpan pengaturan foto login.'
            );
            redirect('owner/pengaturan_login');
            return;
        }

        // Hapus foto lama jika ada
        if (
            !empty($foto_lama) &&
            $foto_lama !== $nama_file_baru &&
            is_file($folder . $foto_lama)
        ) {
            @unlink($folder . $foto_lama);
        }

        $this->session->set_flashdata(
            'success',
            'Foto halaman login berhasil diperbarui.'
        );

        redirect('owner/pengaturan_login');
    }


    // ================= HAPUS / RESET FOTO LOGIN =================

    public function hapus_foto_login()
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/pengaturan_login');
            return;
        }

        $folder = FCPATH . 'uploads/foto_halaman_login/';
        $file_penanda = $folder . 'active.txt';

        if (file_exists($file_penanda)) {
            $foto_aktif = basename(trim(file_get_contents($file_penanda)));

            if (!empty($foto_aktif) && is_file($folder . $foto_aktif)) {
                @unlink($folder . $foto_aktif);
            }

            @unlink($file_penanda);
        }

        $this->session->set_flashdata(
            'success',
            'Foto login berhasil direset ke tampilan default.'
        );

        redirect('owner/pengaturan_login');
    }


    // ================= CRUD MENU =================

    public function menu()
    {
        $data['menu'] = $this->db
            ->where('status', 'tersedia')
            ->get('menu')
            ->result_array();

        $data['title']      = 'Kelola Menu - Ayam Geprek Romeesa';
        $data['page_title'] = 'Manajemen Menu Restoran';
        $data['subview']    = 'v_owner_menu';

        $this->load->view('templates/v_sidebar', $data);
    }


    public function tambah_menu()
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/menu');
            return;
        }

        $data = $this->_data_menu_dari_post();

        if ($data === NULL) {
            $this->session->set_flashdata(
                'error_upload',
                'Data menu tidak valid. Periksa nama menu, kategori, dan harga.'
            );
            redirect('owner/menu');
            return;
        }

        $data['status'] = 'tersedia';
        $data['foto']   = NULL;

        if (!empty($_FILES['foto']['name'])) {
            $hasil_upload = $this->_upload_foto_menu();

            if ($hasil_upload['error'] !== NULL) {
                $this->session->set_flashdata('error_upload', $hasil_upload['error']);
                redirect('owner/menu');
                return;
            }

            $data['foto'] = $hasil_upload['foto'];
        }

        if (!$this->db->insert('menu', $data)) {
            if (!empty($data['foto'])) {
                @unlink(FCPATH . 'uploads/foto_menu/' . $data['foto']);
            }

            $this->session->set_flashdata(
                'error_upload',
                'Menu gagal disimpan ke database.'
            );
            redirect('owner/menu');
            return;
        }

        $this->session->set_flashdata(
            'success',
            'Menu baru beserta fotonya berhasil ditambahkan!'
        );

        redirect('owner/menu');
    }


    public function edit_menu($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/menu');
            return;
        }

        $menu_lama = $this->db->get_where('menu', ['id' => $id])->row_array();

        if (empty($menu_lama)) {
            $this->session->set_flashdata(
                'error_upload',
                'Menu yang ingin diubah tidak ditemukan.'
            );
            redirect('owner/menu');
            return;
        }

        $data = $this->_data_menu_dari_post();

        if ($data === NULL) {
            $this->session->set_flashdata(
                'error_upload',
                'Data menu tidak valid. Periksa nama menu, kategori, dan harga.'
            );
            redirect('owner/menu');
            return;
        }

        $foto_baru = NULL;

        if (!empty($_FILES['foto']['name'])) {
            $hasil_upload = $this->_upload_foto_menu();

            if ($hasil_upload['error'] !== NULL) {
                $this->session->set_flashdata('error_upload', $hasil_upload['error']);
                redirect('owner/menu');
                return;
            }

            $foto_baru = $hasil_upload['foto'];
            $data['foto'] = $foto_baru;
        }

        $this->db->where('id', $id);
        $berhasil = $this->db->update('menu', $data);

        if (!$berhasil) {
            if ($foto_baru !== NULL) {
                @unlink(FCPATH . 'uploads/foto_menu/' . $foto_baru);
            }

            $this->session->set_flashdata(
                'error_upload',
                'Menu gagal diperbarui di database.'
            );
            redirect('owner/menu');
            return;
        }

        if ($foto_baru !== NULL) {
            $foto_lama = !empty($menu_lama['foto'])
                ? basename($menu_lama['foto'])
                : '';

            if (
                $foto_lama !== '' &&
                $foto_lama !== 'default.jpg' &&
                $foto_lama !== $foto_baru
            ) {
                $dipakai_menu_lain = $this->db
                    ->where('foto', $foto_lama)
                    ->where('id !=', $id)
                    ->count_all_results('menu');

                $path_foto_lama = FCPATH . 'uploads/foto_menu/' . $foto_lama;

                if ($dipakai_menu_lain === 0 && is_file($path_foto_lama)) {
                    @unlink($path_foto_lama);
                }
            }
        }

        $this->session->set_flashdata(
            'success',
            'Menu berhasil diubah!'
        );

        redirect('owner/menu');
    }


    public function hapus_menu($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/menu');
            return;
        }

        $menu = $this->db->get_where('menu', ['id' => $id])->row_array();

        if (empty($menu)) {
            $this->session->set_flashdata(
                'error_upload',
                'Menu yang ingin dihapus tidak ditemukan.'
            );
            redirect('owner/menu');
            return;
        }

        $this->db->where('id', $id);
        $this->db->update('menu', ['status' => 'habis']);

        $this->session->set_flashdata(
            'success',
            'Menu berhasil dihapus dari daftar.'
        );

        redirect('owner/menu');
    }


    private function _data_menu_dari_post()
    {
        $nama_menu = trim((string) $this->input->post('nama_menu', true));
        $harga     = $this->input->post('harga', true);
        $kategori  = $this->input->post('kategori', true);

        $kategori_valid = ['makanan', 'minuman', 'topping'];

        if (
            $nama_menu === '' ||
            !is_numeric($harga) ||
            (float) $harga < 0 ||
            !in_array($kategori, $kategori_valid, TRUE)
        ) {
            return NULL;
        }

        return [
            'nama_menu' => $nama_menu,
            'harga'     => (int) $harga,
            'kategori'  => $kategori
        ];
    }


    private function _upload_foto_menu()
    {
        $upload_path = FCPATH . 'uploads/foto_menu/';

        if (!is_dir($upload_path)) {
            @mkdir($upload_path, 0755, TRUE);
        }

        if (!is_dir($upload_path) || !is_writable($upload_path)) {
            return [
                'foto'  => NULL,
                'error' => 'Folder foto menu tidak dapat ditulis.'
            ];
        }

        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE
        ];

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto')) {
            return [
                'foto'  => NULL,
                'error' => $this->upload->display_errors('', '')
            ];
        }

        $upload_data = $this->upload->data();

        if (!@getimagesize($upload_data['full_path'])) {
            @unlink($upload_data['full_path']);

            return [
                'foto'  => NULL,
                'error' => 'File yang diunggah bukan gambar yang valid.'
            ];
        }

        return [
            'foto'  => $upload_data['file_name'],
            'error' => NULL
        ];
    }


    // ================= MANAJEMEN USER =================

    public function users()
    {
        $data['users'] = $this->db->get('users')->result_array();

        $data['title']      = 'Manajemen User - Ayam Geprek Romeesa';
        $data['page_title'] = 'Manajemen Pengguna Sistem';
        $data['subview']    = 'v_owner_users';

        $this->load->view('templates/v_sidebar', $data);
    }


    public function tambah_user()
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/users');
            return;
        }

        $nama     = trim((string) $this->input->post('nama', true));
        $username = trim((string) $this->input->post('username', true));
        $password = (string) $this->input->post('password');
        $role     = $this->input->post('role');

        $role_valid = ['owner', 'kasir', 'dapur'];

        if (
            $nama === '' ||
            !preg_match('/^\S{3,50}$/', $username) ||
            strlen($password) < 6 ||
            !in_array($role, $role_valid, TRUE)
        ) {
            $this->session->set_flashdata(
                'error',
                'Data user tidak valid. Nama wajib diisi, username 3-50 karakter tanpa spasi, password minimal 6 karakter.'
            );

            $this->session->set_flashdata('old_input', $this->input->post());
            $this->session->set_flashdata('modal_target', 'modalTambahUser');

            redirect('owner/users');
            return;
        }

        $cek_username = $this->db->get_where(
            'users',
            ['username' => $username]
        )->row_array();

        if ($cek_username) {
            $this->session->set_flashdata(
                'error_username',
                'Username "' . $username . '" sudah digunakan.'
            );

            $this->session->set_flashdata('old_input', $this->input->post());
            $this->session->set_flashdata('modal_target', 'modalTambahUser');

            redirect('owner/users');
            return;
        }

        $data = [
            'nama'     => $nama,
            'username' => $username,
            'password' => romeosa_hash_password($password),
            'role'     => $role
        ];

        if (!$this->db->insert('users', $data)) {
            $this->session->set_flashdata(
                'error',
                'User baru gagal disimpan ke database.'
            );
            redirect('owner/users');
            return;
        }

        $this->session->set_flashdata(
            'success',
            'User baru berhasil ditambahkan!'
        );

        redirect('owner/users');
    }


    public function edit_user($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/users');
            return;
        }

        $user_lama = $this->db->get_where('users', ['id' => $id])->row_array();

        if (empty($user_lama)) {
            $this->session->set_flashdata(
                'error',
                'User yang ingin diubah tidak ditemukan.'
            );
            redirect('owner/users');
            return;
        }

        $nama     = trim((string) $this->input->post('nama', true));
        $username = trim((string) $this->input->post('username', true));
        $password = (string) $this->input->post('password');
        $role     = $this->input->post('role');

        $role_valid = ['owner', 'kasir', 'dapur'];

        if (
            $nama === '' ||
            !preg_match('/^\S{3,50}$/', $username) ||
            ($password !== '' && strlen($password) < 6) ||
            !in_array($role, $role_valid, TRUE)
        ) {
            $this->session->set_flashdata(
                'error',
                'Data user tidak valid. Nama wajib diisi, username 3-50 karakter tanpa spasi, password baru minimal 6 karakter.'
            );

            $this->session->set_flashdata('modal_target', 'modalEditUser' . $id);

            redirect('owner/users');
            return;
        }

        $cek_username = $this->db
            ->where('username', $username)
            ->where('id !=', $id)
            ->get('users')
            ->row_array();

        if ($cek_username) {
            $this->session->set_flashdata(
                'error_username_' . $id,
                'Username "' . $username . '" sudah digunakan akun lain.'
            );

            $this->session->set_flashdata(
                'modal_target',
                'modalEditUser' . $id
            );

            redirect('owner/users');
            return;
        }

        $data = [
            'nama'     => $nama,
            'username' => $username,
            'role'     => $role
        ];

        if ($password !== '') {
            $data['password'] = romeosa_hash_password($password);
        }

        $this->db->where('id', $id);
        $berhasil = $this->db->update('users', $data);

        if (!$berhasil) {
            $this->session->set_flashdata(
                'error',
                'Data user gagal diperbarui di database.'
            );
            redirect('owner/users');
            return;
        }

        $this->session->set_flashdata(
            'success',
            'Data user berhasil diperbarui!'
        );

        redirect('owner/users');
    }


    public function hapus_user($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('owner/users');
            return;
        }

        if ($id == $this->session->userdata('id')) {
            $this->session->set_flashdata(
                'error',
                'Anda tidak dapat menghapus akun yang sedang digunakan saat ini!'
            );

            redirect('owner/users');
            return;
        }

        $user = $this->db->get_where('users', ['id' => $id])->row_array();

        if (empty($user)) {
            $this->session->set_flashdata(
                'error',
                'User yang ingin dihapus tidak ditemukan.'
            );
            redirect('owner/users');
            return;
        }

        $this->db->where('id', $id);
        $this->db->delete('users');

        $this->session->set_flashdata(
            'success',
            'User berhasil dihapus dari sistem!'
        );

        redirect('owner/users');
    }

}
