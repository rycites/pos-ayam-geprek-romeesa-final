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
        $this->db->select('detail_transaksi.*, menu.nama_menu, menu.foto, transaksi.no_faktur, transaksi.tanggal, transaksi.nomor_meja');
        $this->db->from('detail_transaksi');
        $this->db->join('menu', 'menu.id = detail_transaksi.id_menu');
        $this->db->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi');
        $this->db->where('detail_transaksi.status', 'pending');
        $this->db->order_by('transaksi.tanggal', 'ASC'); 
        $this->db->limit(20);
        $data['pesanan'] = $this->db->get()->result_array();

        // Integrasi Layout Sidebar Dapur
        $data['title'] = 'Dapur - Ayam Geprek Romeesa';
        $data['page_title'] = 'Daftar Antrean Pesanan Masuk';
        $data['subview'] = 'v_kitchen';
        $data['refresh'] = 10;

        $this->load->view('templates/v_sidebar_kitchen', $data);
    }

    public function selesai($id_detail)
    {
        $this->db->where('id_detail', $id_detail);
        $this->db->update('detail_transaksi', ['status' => 'selesai']);

        redirect('kitchen');
    }
}