<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashier extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'number', 'date']);

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'kasir') {
            $this->session->set_flashdata('msg', 'Silakan login terlebih dahulu!');
            redirect('auth');
        }
    }

    public function index()
    {
        $data['menu'] = $this->db->get_where('menu', ['status' => 'tersedia'])->result_array();
        $this->load->view('v_cashier', $data);
    }

    public function add_cart($id_menu)
    {
        $menu = $this->db->get_where('menu', ['id' => $id_menu])->row_array();

        if ($menu) {
            $cart = $this->session->userdata('cart') ?: [];

            if (isset($cart[$id_menu])) {
                $cart[$id_menu]['qty'] += 1;
                $cart[$id_menu]['subtotal'] = $cart[$id_menu]['qty'] * $cart[$id_menu]['harga'];
            } else {
                $cart[$id_menu] = [
                    'id'        => $menu['id'],
                    'nama_menu' => $menu['nama_menu'],
                    'harga'     => $menu['harga'],
                    'qty'       => 1,
                    'subtotal'  => $menu['harga']
                ];
            }

            $this->session->set_userdata('cart', $cart);
        }

        redirect('cashier');
    }

    public function remove_cart($id_menu)
    {
        $cart = $this->session->userdata('cart');
        if (isset($cart[$id_menu])) {
            unset($cart[$id_menu]);
            $this->session->set_userdata('cart', $cart);
        }
        redirect('cashier');
    }

    // Fungsi Proses Pembayaran / Checkout
    public function checkout()
    {
        $cart = $this->session->userdata('cart');
        $uang_bayar = $this->input->post('uang_bayar');
        
        // Hitung total belanja dari keranjang
        $total_bayar = 0;
        foreach($cart as $item) {
            $total_bayar += $item['subtotal'];
        }

        // Validasi uang bayar tidak boleh kurang dari total belanja
        if ($uang_bayar < $total_bayar) {
            $this->session->set_flashdata('error', 'Uang pembeli kurang!');
            redirect('cashier');
        }

        $uang_kembalian = $uang_bayar - $total_bayar;
        $no_faktur = 'INV-' . time();

        // Data utama transaksi
        $data_transaksi = [
            'no_faktur'      => $no_faktur,
            'tanggal'        => date('Y-m-d H:i:s'),
            'total_bayar'    => $total_bayar,
            'uang_bayar'     => $uang_bayar,
            'uang_kembalian' => $uang_kembalian,
            'id_kasir'       => $this->session->userdata('id')
        ];

        // Simpan ke tabel transaksi
        $this->db->insert('transaksi', $data_transaksi);
        $id_transaksi = $this->db->insert_id();

        // Simpan detail transaksi dari keranjang
        foreach($cart as $item) {
            $data_detail = [
                'id_transaksi' => $id_transaksi,
                'id_menu'      => $item['id'],
                'jumlah'       => $item['qty'],
                'subtotal'     => $item['subtotal']
            ];
            $this->db->insert('detail_transaksi', $data_detail);
        }

        // Kosongkan keranjang setelah sukses
        $this->session->unset_userdata('cart');
        
        // Set pesan sukses beserta info kembalian
        $this->session->set_flashdata('success', "Transaksi Berhasil! No Faktur: $no_faktur | Kembalian: Rp " . number_format($uang_kembalian, 0, ',', '.'));
        
        redirect('cashier');
    }
}