<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url']);

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'dapur') {
            $this->session->set_flashdata('msg', 'Akses ditolak! Khusus Dapur.');
            redirect('auth');
        }
    }

    public function index()
    {
        // Ambil pesanan yang statusnya masih pending
        $this->db->select('detail_transaksi.*, menu.nama_menu, transaksi.no_faktur, transaksi.tanggal');
        $this->db->from('detail_transaksi');
        $this->db->join('menu', 'menu.id = detail_transaksi.id_menu');
        $this->db->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi');
        $this->db->where('detail_transaksi.status', 'pending');
        $this->db->order_by('transaksi.tanggal', 'ASC'); // Urutkan dari pesanan tertua (First In, First Out)
        $this->db->limit(20);
        $data['pesanan'] = $this->db->get()->result_array();

        $this->load->view('v_kitchen', $data);
    }

    public function selesai($id_detail)
    {
        // Ubah status pesanan menjadi selesai berdasarkan id_detail
        $this->db->where('id_detail', $id_detail);
        $this->db->update('detail_transaksi', ['status' => 'selesai']);

        redirect('kitchen');
    }
}