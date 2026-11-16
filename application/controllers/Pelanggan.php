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
        // Simpan nomor meja aktif di session perangkat
        $this->session->set_userdata('active_table', $no_meja);

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

        // 1. Simpan ke tabel transaksi
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

        // SIMPAN SESSION AKTIF PERANGKAT (Agar bisa diakses kembali walau tekan back)
        $this->session->set_userdata('active_order_table_' . $no_meja, $id_transaksi);

        // 2. Tentukan status detail pesanan berdasarkan metode pembayaran
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
        $data['no_meja']            = $no_meja;
        $data['no_faktur']          = $no_faktur;
        $data['nama_pemesan']       = $nama_pemesan;
        $data['grand_total']        = $grand_total;
        $data['metode_pembayaran']  = $metode_pembayaran;
        $data['id_transaksi']       = $id_transaksi;

        $this->load->view('v_pelanggan_sukses', $data);
    }

    // Halaman Cek Status Pesanan Berdasarkan ID Transaksi
    public function status($id_transaksi)
    {
        $data['transaksi'] = $this->db->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row_array();
        
        if (!$data['transaksi']) {
            show_404();
        }

        // Ambil detail pesanan untuk melihat status di dapur
        $data['detail_transaksi'] = $this->db->select('detail_transaksi.*, menu.nama_menu')
                                           ->from('detail_transaksi')
                                           ->join('menu', 'menu.id = detail_transaksi.id_menu')
                                           ->where('detail_transaksi.id_transaksi', $id_transaksi)
                                           ->get()
                                           ->result_array();

        // CEK OTOMATIS: Apakah semua item di dapur sudah berstatus 'selesai'?
        $semua_selesai = true;
        if (!empty($data['detail_transaksi'])) {
            foreach ($data['detail_transaksi'] as $dt) {
                if ($dt['status'] != 'selesai') {
                    $semua_selesai = false;
                    break;
                }
            }
        } else {
            $semua_selesai = false;
        }

        // Jika dapur sudah menuntaskan semua menu, bersihkan session perangkat secara otomatis
        if ($semua_selesai) {
            preg_match('/\d+/', $data['transaksi']['nomor_meja'], $match);
            $no_meja = isset($match[0]) ? $match[0] : null;
            if ($no_meja) {
                $this->session->unset_userdata('active_order_table_' . $no_meja);
            }
        }

        $this->load->view('v_pelanggan_status', $data);
    }

    // Fungsi Pintasan untuk Cek Status Pesanan Aktif di Perangkat Ini
    public function cek_status_aktif($no_meja)
    {
        $id_transaksi = $this->session->userdata('active_order_table_' . $no_meja);

        if (!$id_transaksi) {
            $this->session->set_flashdata('error', 'Belum ada riwayat pesanan aktif dari perangkat ini untuk Meja ' . $no_meja);
            redirect('pelanggan/meja/' . $no_meja);
        }

        redirect('pelanggan/status/' . $id_transaksi);
    }

    // Fungsi untuk membersihkan riwayat perangkat secara manual jika diperlukan
    public function reset_sesi_meja($no_meja)
    {
        $this->session->unset_userdata('active_order_table_' . $no_meja);
        redirect('pelanggan/meja/' . $no_meja);
    }
}