<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Owner extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url']);

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'owner') {
            $this->session->set_flashdata('msg', 'Akses ditolak! Khusus Owner.');
            redirect('auth');
        }
    }

    // Dashboard Laporan Penjualan (Default saat Owner login)
    public function index()
    {
        $this->laporan();
    }

    // ================= FITUR LAPORAN & FILTER =================

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

        // Filter berdasarkan kategori waktu
        if ($jenis == 'harian' && !empty($tanggal)) {
            $this->db->where('DATE(transaksi.tanggal)', $tanggal);
        } elseif ($jenis == 'mingguan') {
            $this->db->where('transaksi.tanggal >=', date('Y-m-d 00:00:00', strtotime('-7 days')));
        } elseif ($jenis == 'bulanan' && !empty($bulan)) {
            $this->db->like('transaksi.tanggal', $bulan, 'after');
        } else {
            $this->db->where('DATE(transaksi.tanggal) >=', $tanggal_mulai);
            $this->db->where('DATE(transaksi.tanggal) <=', $tanggal_selesai);
        }

        // Filter berdasarkan metode pembayaran (QRIS / Cash)
        if (!empty($metode)) {
            $this->db->where('transaksi.metode_pembayaran', $metode);
        }

        $this->db->order_by('transaksi.tanggal', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        $data['transaksi'] = $result;
        $data['laporan']   = $result;

        $total_omset = 0;
        foreach($result as $t) {
            $total_omset += $t['total_bayar'];
        }

        $data['total_omset']       = $total_omset;
        $data['total_pendapatan']  = $total_omset;
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

        if ($jenis == 'harian' && !empty($tanggal)) {
            $this->db->where('DATE(transaksi.tanggal)', $tanggal);
        } elseif ($jenis == 'mingguan') {
            $this->db->where('transaksi.tanggal >=', date('Y-m-d 00:00:00', strtotime('-7 days')));
        } elseif ($jenis == 'bulanan' && !empty($bulan)) {
            $this->db->like('transaksi.tanggal', $bulan, 'after');
        } else {
            $this->db->where('DATE(transaksi.tanggal) >=', $tanggal_mulai);
            $this->db->where('DATE(transaksi.tanggal) <=', $tanggal_selesai);
        }

        if (!empty($metode)) {
            $this->db->where('transaksi.metode_pembayaran', $metode);
        }

        $this->db->order_by('transaksi.tanggal', 'ASC');
        
        $data['laporan'] = $this->db->get()->result_array();
        
        $this->load->view('v_owner_laporan_print', $data);
    }

    // ================= CRUD MENU & FOTO =================

    public function menu()
    {
        $data['menu'] = $this->db->get('menu')->result_array();
        
        $data['title']      = 'Kelola Menu - Ayam Geprek Romeesa';
        $data['page_title'] = 'Manajemen Menu Restoran';
        $data['subview']    = 'v_owner_menu';

        $this->load->view('templates/v_sidebar', $data);
    }

    public function tambah_menu()
    {
        $upload_path = FCPATH . 'uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $data = [
            'nama_menu' => $this->input->post('nama_menu', true),
            'harga'     => $this->input->post('harga', true),
            'kategori'  => $this->input->post('kategori', true),
            'status'    => 'tersedia',
            'foto'      => 'default.jpg'
        ];

        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                $uploadData = $this->upload->data();
                $data['foto'] = $uploadData['file_name'];
            } else {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error_upload', $error);
            }
        }

        $this->db->insert('menu', $data);
        $this->session->set_flashdata('success', 'Menu baru beserta fotonya berhasil ditambahkan!');
        redirect('owner/menu');
    }

    public function edit_menu($id)
    {
        $upload_path = FCPATH . 'uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $data = [
            'nama_menu' => $this->input->post('nama_menu', true),
            'harga'     => $this->input->post('harga', true),
            'kategori'  => $this->input->post('kategori', true)
        ];

        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                $menu_lama = $this->db->get_where('menu', ['id' => $id])->row_array();
                if (!empty($menu_lama['foto']) && file_exists($upload_path . $menu_lama['foto']) && $menu_lama['foto'] != 'default.jpg') {
                    @unlink($upload_path . $menu_lama['foto']);
                }

                $uploadData = $this->upload->data();
                $data['foto'] = $uploadData['file_name'];
            }
        }

        $this->db->where('id', $id);
        $this->db->update('menu', $data);
        $this->session->set_flashdata('success', 'Menu berhasil diubah!');
        redirect('owner/menu');
    }

    public function hapus_menu($id)
    {
        $upload_path = FCPATH . 'uploads/';
        $menu = $this->db->get_where('menu', ['id' => $id])->row_array();
        if (!empty($menu['foto']) && file_exists($upload_path . $menu['foto']) && $menu['foto'] != 'default.jpg') {
            @unlink($upload_path . $menu['foto']);
        }

        $this->db->where('id', $id);
        $this->db->delete('menu');
        $this->session->set_flashdata('success', 'Menu berhasil dihapus!');
        redirect('owner/menu');
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
        $username = $this->input->post('username');

        $cek_username = $this->db->get_where('users', ['username' => $username])->row_array();
        if ($cek_username) {
            $this->session->set_flashdata('error_username', 'Username "' . $username . '" sudah digunakan.');
            $this->session->set_flashdata('old_input', $this->input->post());
            $this->session->set_flashdata('modal_target', 'modalTambahUser');
            redirect('owner/users');
        }

        $data = [
            'nama'     => $this->input->post('nama'),
            'username' => $username,
            'password' => md5($this->input->post('password')),
            'role'     => $this->input->post('role')
        ];

        $this->db->insert('users', $data);
        $this->session->set_flashdata('success', 'User baru berhasil ditambahkan!');
        redirect('owner/users');
    }

    public function edit_user($id)
    {
        $username = $this->input->post('username');

        $cek_username = $this->db->where('username', $username)->where('id !=', $id)->get('users')->row_array();
        if ($cek_username) {
            $this->session->set_flashdata('error_username_' . $id, 'Username "' . $username . '" sudah digunakan akun lain.');
            $this->session->set_flashdata('modal_target', 'modalEditUser' . $id);
            redirect('owner/users');
        }

        $data = [
            'nama'     => $this->input->post('nama'),
            'username' => $username,
            'role'     => $this->input->post('role')
        ];

        if (!empty($this->input->post('password'))) {
            $data['password'] = md5($this->input->post('password'));
        }

        $this->db->where('id', $id);
        $this->db->update('users', $data);
        $this->session->set_flashdata('success', 'Data user berhasil diperbarui!');
        redirect('owner/users');
    }

    public function hapus_user($id)
    {
        if ($id == $this->session->userdata('id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun yang sedang digunakan saat ini!');
            redirect('owner/users');
        }

        $this->db->where('id', $id);
        $this->db->delete('users');
        $this->session->set_flashdata('success', 'User berhasil dihapus dari sistem!');
        redirect('owner/users');
    }
}