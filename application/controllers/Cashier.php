<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashier extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'number', 'date']);

        // Izinkan method 'struk' diakses tanpa harus login sebagai kasir (untuk pelanggan)
        $method = $this->router->fetch_method();
        if ($method != 'struk') {
            if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'kasir') {
                $this->session->set_flashdata('msg', 'Silakan login terlebih dahulu!');
                redirect('auth');
            }
        }
    }

    public function index()
    {
        $data['menu'] = $this->db->get_where('menu', ['status' => 'tersedia'])->result_array();
        
        $data['title']      = 'Kasir - Ayam Geprek Romeesa';
        $data['page_title'] = 'Point of Sales (POS) Kasir';
        $data['subview']    = 'v_cashier';
        
        $this->load->view('templates/v_sidebar_kasir', $data);
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

    public function checkout()
    {
        $cart = $this->session->userdata('cart');
        $uang_bayar = $this->input->post('uang_bayar');
        $nama_pemesan = $this->input->post('nama_pemesan');
        $jenis_pesanan = $this->input->post('jenis_pesanan');
        $nomor_meja = $this->input->post('nomor_meja');
        $metode_pembayaran = $this->input->post('metode_pembayaran') ?: 'Cash';
        
        if (empty($cart) || empty($nama_pemesan)) {
            $this->session->set_flashdata('error', 'Keranjang kosong atau nama pemesan belum diisi!');
            redirect('cashier');
        }

        $total_bayar = 0;
        foreach($cart as $item) {
            $total_bayar += $item['subtotal'];
        }

        if ($metode_pembayaran == 'Cash') {
            if ($uang_bayar < $total_bayar) {
                $this->session->set_flashdata('error', 'Uang pembeli kurang!');
                redirect('cashier');
            }
            $uang_kembalian = $uang_bayar - $total_bayar;
        } else {
            $uang_bayar = $total_bayar;
            $uang_kembalian = 0;
        }

        $no_faktur = 'INV-' . time();

        if ($jenis_pesanan == 'Dine-in') {
            $keterangan_tempat = 'Meja ' . $nomor_meja . ' (' . $nama_pemesan . ')';
        } else {
            $keterangan_tempat = 'Takeaway (' . $nama_pemesan . ')';
        }

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

        $this->db->insert('transaksi', $data_transaksi);
        $id_transaksi = $this->db->insert_id();

        foreach($cart as $item) {
            $data_detail = [
                'id_transaksi' => $id_transaksi,
                'id_menu'      => $item['id'],
                'jumlah'       => $item['qty'],
                'subtotal'     => $item['subtotal'],
                'status'       => 'pending'
            ];
            $this->db->insert('detail_transaksi', $data_detail);
        }

        $this->session->unset_userdata('cart');
        $this->session->set_flashdata('success', "Transaksi Berhasil! No Faktur: $no_faktur | Kembalian: Rp " . number_format($uang_kembalian, 0, ',', '.'));
        
        redirect('cashier/struk/' . $id_transaksi);
    }

    public function antrean_meja()
    {
        $data['pesanan_pending'] = $this->db->where('id_kasir', 0)
                                            ->group_start()
                                                ->where('LOWER(status_bayar)', 'pending')
                                                ->or_where('status_bayar', 'Pending')
                                            ->group_end()
                                            ->order_by('tanggal', 'DESC')
                                            ->get('transaksi')
                                            ->result_array();
                                            
        $data['title']      = 'Antrean Meja QR - Ayam Geprek Romeesa';
        $data['page_title'] = 'Konfirmasi Antrean Pesanan Meja';
        $data['subview']    = 'v_cashier_antrean';
        $data['refresh']    = 10;

        $this->load->view('templates/v_sidebar_kasir', $data);
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

            $update_data = [
                'status_bayar'      => 'Lunas',
                'metode_pembayaran' => $metode_pembayaran,
                'uang_bayar'        => ($metode_pembayaran == 'Cash') ? $uang_bayar : $transaksi['total_bayar'],
                'uang_kembalian'    => $uang_kembalian,
                'id_kasir'          => $this->session->userdata('id')
            ];

            $this->db->where('id_transaksi', $id_transaksi);
            $this->db->update('transaksi', $update_data);

            $this->db->where('id_transaksi', $id_transaksi);
            $this->db->update('detail_transaksi', ['status' => 'pending']);

            // AMANKAN SESI PELANGGAN DI MEJA
            preg_match('/\d+/', $transaksi['nomor_meja'], $match);
            $no_meja_angka = isset($match[0]) ? $match[0] : 1;
            $this->session->set_userdata('active_order_meja_' . $no_meja_angka, $id_transaksi);

            $this->session->set_flashdata('success', 'Pembayaran pesanan meja berhasil dikonfirmasi! Pesanan diteruskan ke dapur.');
            
            redirect('cashier/antrean_meja');
        }

        redirect('cashier/antrean_meja');
    }

    public function struk($id_transaksi)
    {
        $data['transaksi'] = $this->db->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row_array();
        
        if (!$data['transaksi']) {
            show_404();
        }
        
        $data['detail_transaksi'] = $this->db->select('detail_transaksi.*, menu.nama_menu, menu.harga')
                                           ->from('detail_transaksi')
                                           ->join('menu', 'menu.id = detail_transaksi.id_menu')
                                           ->where('detail_transaksi.id_transaksi', $id_transaksi)
                                           ->get()
                                           ->result_array();

        if ($data['transaksi']['id_kasir'] > 0) {
            $kasir = $this->db->get_where('users', ['id' => $data['transaksi']['id_kasir']])->row_array();
            $data['nama_kasir'] = $kasir ? $kasir['nama'] : 'Kasir';
        }

        $this->load->view('v_struk', $data);
    }

    public function laporan()
    {
        $tanggal_mulai   = $this->input->get('tanggal_mulai') ?: date('Y-m-01');
        $tanggal_selesai = $this->input->get('tanggal_selesai') ?: date('Y-m-d');
        $metode          = $this->input->get('metode', true);

        $this->db->where('DATE(tanggal) >=', $tanggal_mulai);
        $this->db->where('DATE(tanggal) <=', $tanggal_selesai);
        
        if (!empty($metode)) {
            $this->db->where('metode_pembayaran', $metode);
        }

        $this->db->group_start();
            $this->db->where('LOWER(status_bayar)', 'lunas');
        $this->db->group_end();
        $this->db->order_by('tanggal', 'DESC');
        $data['transaksi'] = $this->db->get('transaksi')->result_array();

        $total_omset = 0;
        foreach($data['transaksi'] as $t) {
            $total_omset += $t['total_bayar'];
        }

        $data['total_omset']    = $total_omset;
        $data['tanggal_mulai']  = $tanggal_mulai;
        $data['tanggal_selesai']= $tanggal_selesai;
        $data['metode_aktif']   = $metode;

        $data['title']      = 'Laporan Pendapatan - Ayam Geprek Romeesa';
        $data['page_title'] = 'Laporan Rekap Penjualan';
        $data['subview']    = 'v_laporan';

        $this->load->view('templates/v_sidebar_kasir', $data);
    }
}