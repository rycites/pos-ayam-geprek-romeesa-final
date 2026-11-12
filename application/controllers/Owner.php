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

    // Dashboard Laporan Penjualan
    public function index()
    {
        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_kasir', 'left');
        $this->db->order_by('transaksi.tanggal', 'DESC');
        $data['laporan'] = $this->db->get()->result_array();

        $this->db->select('SUM(total_bayar) as total_pendapatan, COUNT(id_transaksi) as total_transaksi');
        $stats = $this->db->get('transaksi')->row_array();
        
        $data['total_pendapatan'] = $stats['total_pendapatan'] ? $stats['total_pendapatan'] : 0;
        $data['total_transaksi'] = $stats['total_transaksi'] ? $stats['total_transaksi'] : 0;

        $this->load->view('v_owner', $data);
    }

    // ================= CRUD MENU =================

    // Tampilkan daftar menu
    public function menu()
    {
        $data['menu'] = $this->db->get('menu')->result_array();
        $this->load->view('v_owner_menu', $data);
    }

    // Tambah menu baru
    public function tambah_menu()
    {
        $data = [
            'nama_menu' => $this->input->post('nama_menu'),
            'harga'     => $this->input->post('harga'),
            'kategori'  => $this->input->post('kategori')
        ];
        $this->db->insert('menu', $data);
        $this->session->set_flashdata('success', 'Menu baru berhasil ditambahkan!');
        redirect('owner/menu');
    }

    // Edit menu
    public function edit_menu($id)
    {
        $data = [
            'nama_menu' => $this->input->post('nama_menu'),
            'harga'     => $this->input->post('harga'),
            'kategori'  => $this->input->post('kategori')
        ];
        $this->db->where('id', $id);
        $this->db->update('menu', $data);
        $this->session->set_flashdata('success', 'Menu berhasil diubah!');
        redirect('owner/menu');
    }

    // Hapus menu
    public function hapus_menu($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('menu');
        $this->session->set_flashdata('success', 'Menu berhasil dihapus!');
        redirect('owner/menu');
    }
}