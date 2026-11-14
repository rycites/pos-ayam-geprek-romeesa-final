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

    // Halaman Utama Menu Pelanggan berdasarkan Nomor Meja
    public function meja($no_meja)
    {
        $data['no_meja'] = $no_meja;
        // Ambil semua menu yang aktif/tersedia
        $data['menu'] = $this->db->get('menu')->result_array();
        
        $this->load->view('v_pelanggan', $data);
    }

    // Tambah item ke keranjang belanja session
    public function add_cart($id_menu, $no_meja)
    {
        $menu = $this->db->get_where('menu', ['id' => $id_menu])->row_array();

        if ($menu) {
            $cart = $this->session->userdata('cart_pelanggan') ?? [];

            // Jika menu sudah ada di keranjang, tambahkan quantity-nya
            if (isset($cart[$id_menu])) {
                $cart[$id_menu]['qty'] += 1;
                $cart[$id_menu]['subtotal'] = $cart[$id_menu]['qty'] * $cart[$id_menu]['harga'];
            } else {
                // Jika belum ada, masukkan item baru
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

    // Hapus item dari keranjang
    public function remove_cart($id_menu, $no_meja)
    {
        $cart = $this->session->userdata('cart_pelanggan');
        if (isset($cart[$id_menu])) {
            unset($cart[$id_menu]);
            $this->session->set_userdata('cart_pelanggan', $cart);
        }

        redirect('pelanggan/meja/' . $no_meja);
    }

    // Proses Checkout Pesanan dari Pelanggan
    public function checkout($no_meja)
    {
        $cart = $this->session->userdata('cart_pelanggan');

        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang belanja masih kosong!');
            redirect('pelanggan/meja/' . $no_meja);
        }

        $nama_pemesan      = $this->input->post('nama_pemesan', true);
        $metode_pembayaran = $this->input->post('metode_pembayaran', true);

        // Hitung grand total
        $grand_total = 0;
        foreach ($cart as $item) {
            $grand_total += $item['subtotal'];
        }

        // Generate Nomor Faktur Unik
        $no_faktur = 'TRX-' . date('Ymd') . '-' . rand(100, 999);

        // Tentukan status bayar (Jika QRIS langsung lunas, jika Cash status pending bayar ke kasir)
        $status_bayar = ($metode_pembayaran == 'QRIS') ? 'lunas' : 'pending';

        // 1. Simpan ke tabel transaksi (Nomor Meja digabung dengan Nama Pemesan agar jelas di kasir)
        $data_transaksi = [
            'no_faktur'         => $no_faktur,
            'tanggal'           => date('Y-m-d H:i:s'),
            'total_bayar'       => $grand_total,
            'uang_bayar'        => ($metode_pembayaran == 'QRIS') ? $grand_total : 0,
            'uang_kembalian'    => 0,
            'id_kasir'          => 0, // 0 Berarti pesanan mandiri oleh pelanggan
            'nomor_meja'        => 'Meja ' . $no_meja . ' (' . ($nama_pemesan ? $nama_pemesan : 'Tanpa Nama') . ')',
            'jenis_pesanan'     => 'dine_in',
            'status_bayar'      => $status_bayar,
            'metode_pembayaran' => $metode_pembayaran
        ];

        $this->db->insert('transaksi', $data_transaksi);
        $id_transaksi = $this->db->insert_id();

        // 2. Tentukan status detail pesanan berdasarkan metode pembayaran
        // QRIS langsung masuk dapur ('pending'), Cash ditahan dulu menunggu kasir ('menunggu_kasir')
        $status_detail = ($metode_pembayaran == 'QRIS') ? 'pending' : 'menunggu_kasir';

        foreach ($cart as $item) {
            $data_detail = [
                'id_transaksi' => $id_transaksi,
                'id_menu'      => $item['id'],
                'jumlah'       => $item['qty'],
                'subtotal'     => $item['subtotal'],
                'status'       => $status_detail
            ];
            $this->db->insert('detail_transaksi', $data_detail);
        }

        // 3. Kosongkan keranjang pelanggan setelah sukses
        $this->session->unset_userdata('cart_pelanggan');

        // 4. Siapkan data untuk halaman sukses
        $data['no_meja']           = $no_meja;
        $data['no_faktur']         = $no_faktur;
        $data['nama_pemesan']      = $nama_pemesan;
        $data['grand_total']       = $grand_total;
        $data['metode_pembayaran'] = $metode_pembayaran;

        $this->load->view('v_pelanggan_sukses', $data);
    }
}