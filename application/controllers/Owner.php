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
        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_kasir', 'left');
        $this->db->order_by('transaksi.tanggal', 'DESC');
        $data['laporan'] = $this->db->get()->result_array();

        // Menggunakan total_bayar sesuai struktur database
        $this->db->select('SUM(total_bayar) as total_pendapatan, COUNT(id_transaksi) as total_transaksi');
        $stats = $this->db->get('transaksi')->row_array();
        
        $data['total_pendapatan'] = $stats['total_pendapatan'] ? $stats['total_pendapatan'] : 0;
        $data['total_transaksi'] = $stats['total_transaksi'] ? $stats['total_transaksi'] : 0;

        $this->load->view('v_owner', $data);
    }

    // ================= FITUR LAPORAN & FILTER =================

    public function laporan()
    {
        $jenis = $this->input->get('jenis'); // harian, mingguan, bulanan
        $tanggal = $this->input->get('tanggal'); // YYYY-MM-DD
        $bulan = $this->input->get('bulan'); // YYYY-MM

        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_kasir', 'left');

        if ($jenis == 'harian' && !empty($tanggal)) {
            $this->db->where('DATE(transaksi.tanggal)', $tanggal);
        } elseif ($jenis == 'bulanan' && !empty($bulan)) {
            // PERBAIKAN: Ditambahkan tanda '=' di sini
            $this->db->where('DATE_FORMAT(transaksi.tanggal, "%Y-%m") =', $bulan);
        } elseif ($jenis == 'mingguan') {
            $this->db->where('transaksi.tanggal >=', date('Y-m-d', strtotime('-7 days')));
        }

        $this->db->order_by('transaksi.tanggal', 'DESC');
        $data['laporan'] = $this->db->get()->result_array();
        $data['jenis'] = $jenis;

        $this->load->view('v_owner_laporan', $data);
    }

    public function cetak_laporan()
    {
        $jenis = $this->input->get('jenis');
        $tanggal = $this->input->get('tanggal');
        $bulan = $this->input->get('bulan');

        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_kasir', 'left');

        if ($jenis == 'harian' && !empty($tanggal)) {
            $this->db->where('DATE(transaksi.tanggal)', $tanggal);
        } elseif ($jenis == 'bulanan' && !empty($bulan)) {
            // PERBAIKAN: Ditambahkan tanda '=' di sini juga
            $this->db->where('DATE_FORMAT(transaksi.tanggal, "%Y-%m") =', $bulan);
        } elseif ($jenis == 'mingguan') {
            $this->db->where('transaksi.tanggal >=', date('Y-m-d', strtotime('-7 days')));
        }

        $this->db->order_by('transaksi.tanggal', 'ASC');
        $data['laporan'] = $this->db->get()->result_array();
        
        $this->load->view('v_owner_laporan_print', $data);
    }

    // ================= CRUD MENU & FOTO =================

    // Tampilkan daftar menu
    public function menu()
    {
        $data['menu'] = $this->db->get('menu')->result_array();
        $this->load->view('v_owner_menu', $data);
    }

    // Tambah menu baru dengan upload foto
    public function tambah_menu()
    {
        $data = [
            'nama_menu' => $this->input->post('nama_menu'),
            'harga'     => $this->input->post('harga'),
            'kategori'  => $this->input->post('kategori')
        ];

        // Konfigurasi Upload
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // Maksimal 2MB
        $config['encrypt_name']  = TRUE; // Ubah nama file agar unik

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            $uploadData = $this->upload->data();
            $data['foto'] = $uploadData['file_name'];
        }

        $this->db->insert('menu', $data);
        $this->session->set_flashdata('success', 'Menu baru berhasil ditambahkan!');
        redirect('owner/menu');
    }

    // Edit menu dengan opsi ganti foto
    public function edit_menu($id)
    {
        $data = [
            'nama_menu' => $this->input->post('nama_menu'),
            'harga'     => $this->input->post('harga'),
            'kategori'  => $this->input->post('kategori')
        ];

        // Cek apakah ada file foto baru yang di-upload
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                // Ambil data menu lama untuk menghapus foto fisik yang lama
                $menu_lama = $this->db->get_where('menu', ['id' => $id])->row_array();
                if (!empty($menu_lama['foto']) && file_exists('./uploads/' . $menu_lama['foto'])) {
                    unlink('./uploads/' . $menu_lama['foto']);
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

    // Hapus menu beserta file fotonya
    public function hapus_menu($id)
    {
        // Ambil data menu untuk hapus file foto dari folder uploads
        $menu = $this->db->get_where('menu', ['id' => $id])->row_array();
        if (!empty($menu['foto']) && file_exists('./uploads/' . $menu['foto'])) {
            unlink('./uploads/' . $menu['foto']);
        }

        $this->db->where('id', $id);
        $this->db->delete('menu');
        $this->session->set_flashdata('success', 'Menu berhasil dihapus!');
        redirect('owner/menu');
    }
}