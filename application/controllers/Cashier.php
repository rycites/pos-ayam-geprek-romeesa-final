<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashier extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'number', 'date']);

        // Izinkan struk diakses tanpa login kasir
        $method = $this->router->fetch_method();

        if ($method != 'struk') {
            if (
                !$this->session->userdata('logged_in') ||
                $this->session->userdata('role') != 'kasir'
            ) {
                $this->session->set_flashdata(
                    'msg',
                    'Silakan login terlebih dahulu!'
                );

                redirect('auth');
            }
        }
    }

    // =====================================================
    // HALAMAN POS KASIR
    // =====================================================
    public function index()
    {
        $data['menu'] = $this->db
            ->get_where('menu', ['status' => 'tersedia'])
            ->result_array();

        $data['title']      = 'Kasir - Ayam Geprek Romeesa';
        $data['page_title'] = 'Point of Sales (POS) Kasir';
        $data['subview']    = 'v_cashier';

        $this->load->view('templates/v_sidebar_kasir', $data);
    }

    // =====================================================
    // TAMBAH MENU KE KERANJANG POS
    // =====================================================
    public function add_cart($id_menu)
    {
        $menu = $this->db->get_where('menu', [
            'id'     => $id_menu,
            'status' => 'tersedia'
        ])->row_array();

        if (!$menu) {
            $this->session->set_flashdata(
                'error',
                'Menu tidak ditemukan atau sedang tidak tersedia!'
            );

            redirect('cashier');
            return;
        }

        $cart = $this->session->userdata('cart') ?: [];

        if (isset($cart[$id_menu])) {

            $cart[$id_menu]['qty']++;

            $cart[$id_menu]['subtotal'] =
                $cart[$id_menu]['qty'] * $cart[$id_menu]['harga'];

        } else {

            $cart[$id_menu] = [
                'id'       => $menu['id'],
                'nama_menu' => $menu['nama_menu'],
                'harga'     => $menu['harga'],
                'qty'      => 1,
                'subtotal'  => $menu['harga']
            ];
        }

        $this->session->set_userdata('cart', $cart);

        redirect('cashier');
    }

    // =====================================================
    // HAPUS MENU DARI KERANJANG POS
    // =====================================================
    public function remove_cart($id_menu)
    {
        $cart = $this->session->userdata('cart') ?: [];

        if (isset($cart[$id_menu])) {
            unset($cart[$id_menu]);

            $this->session->set_userdata('cart', $cart);
        }

        redirect('cashier');
    }

    // =====================================================
    // CHECKOUT POS KASIR
    // =====================================================
    public function checkout()
    {
        $cart = $this->session->userdata('cart');

        $uang_bayar        = $this->input->post('uang_bayar');
        $nama_pemesan      = trim($this->input->post('nama_pemesan') ?? '');
        $jenis_pesanan     = $this->input->post('jenis_pesanan');
        $nomor_meja        = $this->input->post('nomor_meja');
        $metode_pembayaran = $this->input->post('metode_pembayaran') ?: 'Cash';

        if (empty($cart) || empty($nama_pemesan)) {
            $this->session->set_flashdata(
                'error',
                'Keranjang kosong atau nama pemesan belum diisi!'
            );

            redirect('cashier');
            return;
        }

        // =================================================
        // HITUNG TOTAL
        // =================================================
        $total_bayar = 0;

        foreach ($cart as $item) {
            if (
                !isset($item['harga']) ||
                !isset($item['qty']) ||
                !isset($item['subtotal'])
            ) {
                $this->session->set_flashdata(
                    'error',
                    'Data keranjang tidak valid!'
                );

                redirect('cashier');
                return;
            }

            $total_bayar += (float)$item['subtotal'];
        }

        if ($total_bayar <= 0) {
            $this->session->set_flashdata(
                'error',
                'Total transaksi tidak valid!'
            );

            redirect('cashier');
            return;
        }

        // =================================================
        // VALIDASI METODE PEMBAYARAN
        // =================================================
        if (
            strcasecmp($metode_pembayaran, 'Cash') == 0
        ) {

            $uang_bayar = (float)$uang_bayar;

            if ($uang_bayar < $total_bayar) {
                $this->session->set_flashdata(
                    'error',
                    'Uang pembeli kurang!'
                );

                redirect('cashier');
                return;
            }

            $uang_kembalian = $uang_bayar - $total_bayar;

        } elseif (
            strcasecmp($metode_pembayaran, 'QRIS') == 0
        ) {

            $uang_bayar = $total_bayar;
            $uang_kembalian = 0;

        } else {

            $this->session->set_flashdata(
                'error',
                'Metode pembayaran tidak valid!'
            );

            redirect('cashier');
            return;
        }

        // =================================================
        // KETERANGAN PESANAN
        // =================================================
        if ($jenis_pesanan == 'Dine-in') {

            $keterangan_tempat =
                'Meja ' . $nomor_meja . ' (' . $nama_pemesan . ')';

        } else {

            $keterangan_tempat =
                'Takeaway (' . $nama_pemesan . ')';
        }

        $no_faktur = 'INV-' . date('YmdHis') . '-' . mt_rand(100, 999);

        // =================================================
        // SIMPAN TRANSAKSI POS
        // =================================================
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

        $this->db->trans_begin();

        $this->db->insert('transaksi', $data_transaksi);

        $id_transaksi = $this->db->insert_id();

        if (!$id_transaksi) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Transaksi gagal disimpan!'
            );

            redirect('cashier');
            return;
        }

        // =================================================
        // SIMPAN DETAIL TRANSAKSI
        // =================================================
        foreach ($cart as $item) {

            $data_detail = [
                'id_transaksi' => $id_transaksi,
                'id_menu'       => $item['id'],
                'jumlah'        => $item['qty'],
                'subtotal'      => $item['subtotal'],
                'status'        => 'pending'
            ];

            $this->db->insert('detail_transaksi', $data_detail);
        }

        // =================================================
        // CEK TRANSAKSI DATABASE
        // =================================================
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Transaksi gagal disimpan ke database!'
            );

            redirect('cashier');
            return;
        }

        $this->db->trans_commit();

        // =================================================
        // KOSONGKAN KERANJANG
        // =================================================
        $this->session->unset_userdata('cart');

        $this->session->set_flashdata(
            'success',
            "Transaksi Berhasil! No Faktur: $no_faktur | Kembalian: Rp " .
            number_format($uang_kembalian, 0, ',', '.')
        );

        redirect('cashier/struk/' . $id_transaksi);
    }

    // =====================================================
    // ANTREAN PESANAN QR MEJA
    // =====================================================
    public function antrean_meja()
    {
        $this->db->where('id_kasir', 0);
        $this->db->where('LOWER(status_bayar)', 'pending');
        $this->db->order_by('tanggal', 'ASC');

        $data['pesanan_pending'] = $this->db
            ->get('transaksi')
            ->result_array();

        $data['title']      = 'Antrean Meja QR - Ayam Geprek Romeesa';
        $data['page_title'] = 'Konfirmasi Antrean Pesanan Meja';
        $data['subview']    = 'v_cashier_antrean';
        $data['refresh']    = 10;

        $this->load->view('templates/v_sidebar_kasir', $data);
    }

    // =====================================================
    // KONFIRMASI PEMBAYARAN PESANAN QR MEJA
    // =====================================================
    public function bayar_pesanan_meja($id_transaksi)
    {
        // =================================================
        // AMBIL TRANSAKSI
        // =================================================
        $transaksi = $this->db->get_where('transaksi', [
            'id_transaksi' => $id_transaksi
        ])->row_array();

        if (!$transaksi) {
            $this->session->set_flashdata(
                'error',
                'Transaksi tidak ditemukan!'
            );

            redirect('cashier/antrean_meja');
            return;
        }

        // =================================================
        // CEK STATUS PEMBAYARAN
        // =================================================
        if (
            strtolower(trim($transaksi['status_bayar'])) == 'lunas'
        ) {
            $this->session->set_flashdata(
                'error',
                'Transaksi ini sudah dikonfirmasi lunas!'
            );

            redirect('cashier/antrean_meja');
            return;
        }

        // =================================================
        // PASTIKAN PESANAN QR MEJA
        // =================================================
        if ((int)$transaksi['id_kasir'] !== 0) {
            $this->session->set_flashdata(
                'error',
                'Transaksi ini bukan antrean pesanan meja!'
            );

            redirect('cashier/antrean_meja');
            return;
        }

        $metode_pembayaran = trim(
            $transaksi['metode_pembayaran']
        );

        $total_bayar = (float)$transaksi['total_bayar'];

        $uang_bayar = $this->input->post('uang_bayar');

        // =================================================
        // PEMBAYARAN CASH
        // =================================================
        if (
            strcasecmp($metode_pembayaran, 'Cash') == 0
        ) {

            $uang_bayar = (float)$uang_bayar;

            if (
                $uang_bayar <= 0 ||
                $uang_bayar < $total_bayar
            ) {
                $this->session->set_flashdata(
                    'error',
                    'Uang bayar kurang atau belum dimasukkan!'
                );

                redirect('cashier/antrean_meja');
                return;
            }

            $uang_kembalian = $uang_bayar - $total_bayar;

        // =================================================
        // PEMBAYARAN QRIS
        // =================================================
        } elseif (
            strcasecmp($metode_pembayaran, 'QRIS') == 0
        ) {

            $uang_bayar = $total_bayar;
            $uang_kembalian = 0;

        } else {

            $this->session->set_flashdata(
                'error',
                'Metode pembayaran transaksi tidak valid!'
            );

            redirect('cashier/antrean_meja');
            return;
        }

        // =================================================
        // MULAI TRANSAKSI DATABASE
        // =================================================
        $this->db->trans_begin();

        // Pastikan hanya transaksi pending yang dapat dikonfirmasi
        $this->db->where('id_transaksi', $id_transaksi);
        $this->db->where('id_kasir', 0);
        $this->db->where('LOWER(status_bayar)', 'pending');

        $this->db->update('transaksi', [
            'status_bayar'    => 'Lunas',
            'uang_bayar'      => $uang_bayar,
            'uang_kembalian'  => $uang_kembalian,
            'id_kasir'        => $this->session->userdata('id')
        ]);

        // Jika tidak ada transaksi yang berhasil diupdate,
        // berarti status sudah berubah atau transaksi tidak valid.
        if ($this->db->affected_rows() <= 0) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Pesanan sudah diproses atau statusnya telah berubah!'
            );

            redirect('cashier/antrean_meja');
            return;
        }

        // =================================================
        // TERUSKAN PESANAN KE DAPUR
        // =================================================
        $this->db->where('id_transaksi', $id_transaksi);

        $this->db->update('detail_transaksi', [
            'status' => 'pending'
        ]);

        // =================================================
        // CEK TRANSAKSI
        // =================================================
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Konfirmasi pembayaran gagal disimpan!'
            );

            redirect('cashier/antrean_meja');
            return;
        }

        $this->db->trans_commit();

        // =================================================
        // SIMPAN SESSION PESANAN AKTIF MEJA
        // =================================================
        preg_match(
            '/\d+/',
            $transaksi['nomor_meja'],
            $match
        );

        $no_meja_angka = isset($match[0]) ? $match[0] : 1;

        $this->session->set_userdata(
            'active_order_table_' . $no_meja_angka,
            $id_transaksi
        );

        // =================================================
        // NOTIFIKASI BERHASIL
        // =================================================
        $this->session->set_flashdata(
            'success',
            'Pembayaran pesanan meja berhasil dikonfirmasi! Pesanan diteruskan ke dapur.'
        );

        redirect('cashier/antrean_meja');
    }

    // =====================================================
    // STRUK TRANSAKSI
    // =====================================================
    public function struk($id_transaksi)
    {
        $data['transaksi'] = $this->db->get_where('transaksi', [
            'id_transaksi' => $id_transaksi
        ])->row_array();

        if (!$data['transaksi']) {
            show_404();
            return;
        }

        $data['detail_transaksi'] = $this->db
            ->select('detail_transaksi.*, menu.nama_menu, menu.harga')
            ->from('detail_transaksi')
            ->join('menu', 'menu.id = detail_transaksi.id_menu')
            ->where('detail_transaksi.id_transaksi', $id_transaksi)
            ->get()
            ->result_array();

        if ((int)$data['transaksi']['id_kasir'] > 0) {

            $kasir = $this->db->get_where('users', [
                'id' => $data['transaksi']['id_kasir']
            ])->row_array();

            $data['nama_kasir'] = $kasir
                ? $kasir['nama']
                : 'Kasir';
        }

        $this->load->view('v_struk', $data);
    }

    // =====================================================
    // LAPORAN PENJUALAN
    // =====================================================
    public function laporan()
    {
        $tanggal_mulai = $this->input->get('tanggal_mulai')
            ?: date('Y-m-01');

        $tanggal_selesai = $this->input->get('tanggal_selesai')
            ?: date('Y-m-d');

        $metode = $this->input->get('metode', true);

        $this->db->where('DATE(tanggal) >=', $tanggal_mulai);
        $this->db->where('DATE(tanggal) <=', $tanggal_selesai);

        if (!empty($metode)) {
            $this->db->where('metode_pembayaran', $metode);
        }

        $this->db->where('LOWER(status_bayar)', 'lunas');
        $this->db->order_by('tanggal', 'DESC');

        $data['transaksi'] = $this->db
            ->get('transaksi')
            ->result_array();

        // =================================================
        // HITUNG TOTAL OMSET
        // =================================================
        $total_omset = 0;

        foreach ($data['transaksi'] as $t) {
            $total_omset += (float)$t['total_bayar'];
        }

        $data['total_omset']     = $total_omset;
        $data['tanggal_mulai']   = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        $data['metode_aktif']    = $metode;

        $data['title']      = 'Laporan Pendapatan - Ayam Geprek Romeesa';
        $data['page_title'] = 'Laporan Rekap Penjualan';
        $data['subview']    = 'v_laporan';

        $this->load->view('templates/v_sidebar_kasir', $data);
    }
}