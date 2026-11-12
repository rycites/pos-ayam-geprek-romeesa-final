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

    // Fungsi Proses Pembayaran / Checkout oleh Kasir (untuk Takeaway atau Dine-in Manual)
    public function checkout()
    {
        $cart = $this->session->userdata('cart');
        $uang_bayar = $this->input->post('uang_bayar');
        $nama_pemesan = $this->input->post('nama_pemesan'); // Nama Pemesan
        $jenis_pesanan = $this->input->post('jenis_pesanan'); // Dine-in atau Takeaway
        $nomor_meja = $this->input->post('nomor_meja');       // Diisi jika Dine-in
        $metode_pembayaran = $this->input->post('metode_pembayaran') ?: 'Cash';
        
        if (empty($cart) || empty($nama_pemesan)) {
            $this->session->set_flashdata('error', 'Keranjang kosong atau nama pemesan belum diisi!');
            redirect('cashier');
        }

        // Hitung total belanja dari keranjang
        $total_bayar = 0;
        foreach($cart as $item) {
            $total_bayar += $item['subtotal'];
        }

        // Validasi jika pembayaran Cash
        if ($metode_pembayaran == 'Cash') {
            if ($uang_bayar < $total_bayar) {
                $this->session->set_flashdata('error', 'Uang pembeli kurang!');
                redirect('cashier');
            }
            $uang_kembalian = $uang_bayar - $total_bayar;
        } else {
            // Jika QRIS / Debit, uang bayar dianggap pas
            $uang_bayar = $total_bayar;
            $uang_kembalian = 0;
        }

        $no_faktur = 'INV-' . time();

        // Format keterangan tempat & nama pemesan agar jelas di dapur
        if ($jenis_pesanan == 'Dine-in') {
            $keterangan_tempat = 'Meja ' . $nomor_meja . ' (' . $nama_pemesan . ')';
        } else {
            $keterangan_tempat = 'Takeaway (' . $nama_pemesan . ')';
        }

        // Data utama transaksi kasir (Langsung Lunas)
        $data_transaksi = [
            'no_faktur'         => $no_faktur,
            'tanggal'           => date('Y-m-d H:i:s'),
            'total_bayar'       => $total_bayar,
            'uang_bayar'        => $uang_bayar,
            'uang_kembalian'    => $uang_kembalian,
            'id_kasir'          => $this->session->userdata('id'),
            'jenis_pesanan'     => $jenis_pesanan,
            'nomor_meja'        => $keterangan_tempat,
            'metode_pembayaran' => $metode_pembayaran,
            'status_bayar'      => 'Lunas'
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
                'subtotal'     => $item['subtotal'],
                'status'       => 'pending' // Masuk ke dapur kasir/offline
            ];
            $this->db->insert('detail_transaksi', $data_detail);
        }

        // Kosongkan keranjang setelah sukses
        $this->session->unset_userdata('cart');
        
        $this->session->set_flashdata('success', "Transaksi Berhasil! No Faktur: $no_faktur | Kembalian: Rp " . number_format($uang_kembalian, 0, ',', '.'));
        
        redirect('cashier');
    }

    // ---------------------------------------------------------
    // FITUR: Mengelola Antrean Pesanan QR Meja yang Belum Bayar
    // ---------------------------------------------------------
    
    public function antrean_meja()
    {
        // Ambil transaksi dari pelanggan QR (id_kasir = 0) yang status bayarnya masih Pending
        $data['pesanan_pending'] = $this->db->where('id_kasir', 0)
                                            ->where('status_bayar', 'Pending')
                                            ->order_by('tanggal', 'DESC')
                                            ->get('transaksi')
                                            ->result_array();
                                            
        $this->load->view('v_cashier_antrean', $data);
    }

    public function bayar_pesanan_meja($id_transaksi)
    {
        $metode_pembayaran = $this->input->post('metode_pembayaran') ?: 'Cash';
        $uang_bayar = $this->input->post('uang_bayar');

        $transaksi = $this->db->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row_array();

        if ($transaksi) {
            if ($metode_pembayaran == 'Cash' && $uang_bayar < $transaksi['total_bayar']) {
                $this->session->set_flashdata('error', 'Uang bayar kurang!');
                redirect('cashier/antrean_meja');
            }

            $uang_kembalian = ($metode_pembayaran == 'Cash') ? ($uang_bayar - $transaksi['total_bayar']) : 0;

            // Update status transaksi menjadi Lunas dan dicatat oleh kasir yang bertugas
            $update_data = [
                'status_bayar'      => 'Lunas',
                'metode_pembayaran' => $metode_pembayaran,
                'uang_bayar'        => ($metode_pembayaran == 'Cash') ? $uang_bayar : $transaksi['total_bayar'],
                'uang_kembalian'    => $uang_kembalian,
                'id_kasir'          => $this->session->userdata('id')
            ];

            $this->db->where('id_transaksi', $id_transaksi);
            $this->db->update('transaksi', $update_data);

            $this->session->set_flashdata('success', 'Pembayaran pesanan meja berhasil dikonfirmasi! Pesanan diteruskan ke dapur.');
        }

        redirect('cashier/antrean_meja');
    }
}