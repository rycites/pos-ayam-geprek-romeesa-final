<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'form']);
    }

    // Halaman utama menu untuk pelanggan berdasarkan nomor meja
    public function meja($no_meja = 1)
    {
        $data['no_meja'] = $no_meja;
        $data['menu'] = $this->db->get_where('menu', ['status' => 'tersedia'])->result_array();
        $this->load->view('v_pelanggan', $data);
    }

    // Tambah ke keranjang session khusus pelanggan
    public function add_cart($id_menu, $no_meja)
    {
        $menu = $this->db->get_where('menu', ['id' => $id_menu])->row_array();
        
        if ($menu) {
            $cart = $this->session->userdata('cart_pelanggan') ?: [];
            
            // Cek apakah item sudah ada di keranjang
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
            
            $this->session->set_userdata('cart_pelanggan', $cart);
        }
        
        redirect('pelanggan/meja/' . $no_meja);
    }

    // Hapus item dari keranjang pelanggan
    public function remove_cart($id_menu, $no_meja)
    {
        $cart = $this->session->userdata('cart_pelanggan');
        if (isset($cart[$id_menu])) {
            unset($cart[$id_menu]);
            $this->session->set_userdata('cart_pelanggan', $cart);
        }
        redirect('pelanggan/meja/' . $no_meja);
    }

    // Proses Checkout Pesanan Mandiri oleh Pelanggan
    public function checkout($no_meja)
    {
        $cart = $this->session->userdata('cart_pelanggan');
        $nama_pemesan = $this->input->post('nama_pemesan');
        
        if (empty($cart) || empty($nama_pemesan)) {
            $this->session->set_flashdata('error', 'Keranjang masih kosong atau nama pemesan belum diisi!');
            redirect('pelanggan/meja/' . $no_meja);
        }

        // Hitung total belanja
        $total_bayar = 0;
        foreach ($cart as $item) {
            $total_bayar += $item['subtotal'];
        }

        // Buat nomor faktur unik untuk pelanggan QR
        $no_faktur = 'QR-' . date('Ymd') . '-' . rand(100, 999);

        // Data transaksi utama (Status awal: Pending, menunggu pembayaran ke kasir)
        $data_transaksi = [
            'no_faktur'         => $no_faktur,
            'tanggal'           => date('Y-m-d H:i:s'),
            'id_kasir'          => 0, // 0 menandakan pesanan mandiri pelanggan
            'total_bayar'       => $total_bayar,
            'uang_bayar'        => 0, 
            'uang_kembalian'    => 0,
            'nomor_meja'        => 'Meja ' . $no_meja . ' (' . $nama_pemesan . ')',
            'jenis_pesanan'     => 'Dine-in',
            'metode_pembayaran' => 'Pending',
            'status_bayar'      => 'Pending' // Wajib lunas di kasir sebelum ke dapur
        ];

        $this->db->insert('transaksi', $data_transaksi);
        $id_transaksi = $this->db->insert_id();

        // Masukkan detail pesanan
        foreach ($cart as $item) {
            $data_detail = [
                'id_transaksi'   => $id_transaksi,
                'id_menu'        => $item['id'],
                'jumlah'         => $item['qty'],
                'subtotal'       => $item['subtotal'],
                'status'         => 'pending'
            ];
            $this->db->insert('detail_transaksi', $data_detail);
        }

        // Kosongkan keranjang pelanggan
        $this->session->unset_userdata('cart_pelanggan');
        
        $this->session->set_flashdata('success', 'Pesanan berhasil dikirim! Silakan segera lakukan pembayaran ke Kasir.');

        redirect('pelanggan/sukses/' . $no_meja);
    }

    public function sukses($no_meja)
    {
        $data['no_meja'] = $no_meja;
        $this->load->view('v_pelanggan_sukses', $data);
    }
}