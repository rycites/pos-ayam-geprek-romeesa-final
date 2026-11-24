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

        $data['sambal_tersedia'] = $this->db
            ->where('status', 'tersedia')
            ->order_by('nama_sambal', 'ASC')
            ->get('sambal')
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
        if ($this->input->method() !== 'post') {
            redirect('cashier');
            return;
        }

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

        $pilihan = $this->_pilihan_dari_post($menu);

        if ($pilihan['error'] !== NULL) {
            $this->session->set_flashdata('error', $pilihan['error']);

            redirect('cashier');
            return;
        }

        $cart  = $this->session->userdata('cart') ?: [];
        $kunci = $this->_kunci_cart(
            $menu['id'],
            $pilihan['id_sambal'],
            $pilihan['level_pedas']
        );

        if (isset($cart[$kunci])) {

            $cart[$kunci]['qty']++;

            $cart[$kunci]['subtotal'] =
                $cart[$kunci]['qty'] * $cart[$kunci]['harga'];

        } else {

            $cart[$kunci] = [
                'id'          => $menu['id'],
                'nama_menu'   => $menu['nama_menu'],
                'harga'       => $menu['harga'],
                'qty'         => 1,
                'subtotal'    => $menu['harga'],
                'id_sambal'   => $pilihan['id_sambal'],
                'nama_sambal' => $pilihan['nama_sambal'],
                'level_pedas' => $pilihan['level_pedas'],
                'cart_key'    => $kunci
            ];
        }

        $this->session->set_userdata('cart', $cart);

        redirect('cashier');
    }

    // =====================================================
    // HAPUS MENU DARI KERANJANG POS
    // =====================================================
    public function remove_cart($id_menu = NULL)
    {
        if ($this->input->method() !== 'post') {
            redirect('cashier');
            return;
        }

        $cart     = $this->session->userdata('cart') ?: [];
        $cart_key = (string) $this->input->post('cart_key');

        if ($cart_key !== '' && isset($cart[$cart_key])) {

            unset($cart[$cart_key]);

            $this->session->set_userdata('cart', $cart);

        } elseif ($id_menu !== NULL) {

            foreach ($cart as $kunci => $item) {
                if (
                    (string) $kunci === (string) $id_menu ||
                    (string) ($item['id'] ?? '') === (string) $id_menu
                ) {
                    unset($cart[$kunci]);

                    $this->session->set_userdata('cart', $cart);

                    break;
                }
            }
        }

        redirect('cashier');
    }

    // =====================================================
    // CHECKOUT POS KASIR
    // =====================================================
    public function checkout()
    {
        if ($this->input->method() !== 'post') {
            redirect('cashier');
            return;
        }

        $cart_session = $this->session->userdata('cart');

        $uang_bayar        = $this->input->post('uang_bayar');
        $nama_pemesan      = trim((string) $this->input->post('nama_pemesan', true));
        $jenis_pesanan     = $this->input->post('jenis_pesanan');
        $nomor_meja        = trim((string) $this->input->post('nomor_meja', true));
        $metode_pembayaran = $this->input->post('metode_pembayaran') ?: 'Cash';

        if (empty($cart_session) || !is_array($cart_session) || $nama_pemesan === '') {
            $this->session->set_flashdata(
                'error',
                'Keranjang kosong atau nama pemesan belum diisi!'
            );

            redirect('cashier');
            return;
        }

        if (!in_array($jenis_pesanan, ['Dine-in', 'Takeaway'], TRUE)) {
            $this->session->set_flashdata(
                'error',
                'Jenis pesanan tidak valid!'
            );

            redirect('cashier');
            return;
        }

        if ($jenis_pesanan === 'Dine-in') {
            if (
                $nomor_meja === '' ||
                !ctype_digit($nomor_meja) ||
                (int) $nomor_meja < 1
            ) {
                $this->session->set_flashdata(
                    'error',
                    'Nomor meja wajib diisi dengan benar untuk pesanan Dine-in!'
                );

                redirect('cashier');
                return;
            }
        }

        // =================================================
        // VALIDASI ULANG KERANJANG DARI DATABASE
        // =================================================
        $cart        = [];
        $total_bayar = 0;

        foreach ($cart_session as $kunci_lama => $item) {
            $id_menu = isset($item['id'])
                ? (int) $item['id']
                : (int) $kunci_lama;
            $qty     = isset($item['qty']) ? (int) $item['qty'] : 0;

            if ($id_menu <= 0 || $qty <= 0) {
                $this->session->set_flashdata(
                    'error',
                    'Data keranjang tidak valid!'
                );

                redirect('cashier');
                return;
            }

            $menu = $this->db->get_where('menu', [
                'id'     => $id_menu,
                'status' => 'tersedia'
            ])->row_array();

            if (!$menu) {
                $this->session->set_flashdata(
                    'error',
                    'Salah satu menu di keranjang sudah tidak tersedia. Silakan perbarui keranjang.'
                );

                redirect('cashier');
                return;
            }

            $pilihan = $this->_pilihan_dari_item($menu, $item);

            if ($pilihan['error'] !== NULL) {
                $this->session->set_flashdata('error', $pilihan['error']);

                redirect('cashier');
                return;
            }

            $harga    = (float) $menu['harga'];
            $subtotal = $harga * $qty;
            $kunci    = $this->_kunci_cart(
                $menu['id'],
                $pilihan['id_sambal'],
                $pilihan['level_pedas']
            );

            if (isset($cart[$kunci])) {
                $cart[$kunci]['qty'] += $qty;
                $cart[$kunci]['subtotal'] += $subtotal;
            } else {
                $cart[$kunci] = [
                    'id'          => $menu['id'],
                    'nama_menu'   => $menu['nama_menu'],
                    'harga'       => $harga,
                    'qty'         => $qty,
                    'subtotal'    => $subtotal,
                    'id_sambal'   => $pilihan['id_sambal'],
                    'nama_sambal' => $pilihan['nama_sambal'],
                    'level_pedas' => $pilihan['level_pedas'],
                    'cart_key'    => $kunci
                ];
            }

            $total_bayar += $subtotal;
        }

        if ($total_bayar <= 0) {
            $this->session->set_flashdata(
                'error',
                'Total transaksi tidak valid!'
            );

            redirect('cashier');
            return;
        }

        $this->session->set_userdata('cart', $cart);

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
            'status_bayar'      => 'lunas'
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
                'id_sambal'     => $item['id_sambal'],
                'nama_sambal'   => $item['nama_sambal'],
                'level_pedas'   => $item['level_pedas'],
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

        $data['title']         = 'Antrean Meja QR - Ayam Geprek Romeesa';
        $data['page_title']    = 'Konfirmasi Antrean Pesanan Meja';
        $data['subview']       = 'v_cashier_antrean';
        $data['refresh_detik'] = 3;

        $this->load->view('templates/v_sidebar_kasir', $data);
    }

    // =====================================================
    // JUMLAH ANTREAN MEJA UNTUK BADGE SIDEBAR
    // =====================================================
    public function jumlah_antrean()
    {
        $jumlah = $this->db
            ->where('id_kasir', 0)
            ->where('LOWER(status_bayar)', 'pending')
            ->count_all_results('transaksi');

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'jumlah' => (int) $jumlah
            ]));
    }

    // =====================================================
    // KONFIRMASI PEMBAYARAN PESANAN QR MEJA
    // =====================================================
    public function bayar_pesanan_meja($id_transaksi)
    {
        if ($this->input->method() !== 'post') {
            redirect('cashier/antrean_meja');
            return;
        }

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
            'status_bayar'    => 'lunas',
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
        $id_transaksi = (int) $id_transaksi;

        // =================================================
        // BATASI AKSES STRUK
        // Kasir/owner yang login, atau pelanggan yang
        // transaksinya tersimpan di session meja.
        // =================================================
        $boleh_akses = FALSE;

        if (
            $this->session->userdata('logged_in') &&
            in_array($this->session->userdata('role'), ['kasir', 'owner'], TRUE)
        ) {
            $boleh_akses = TRUE;
        }

        if (!$boleh_akses) {
            foreach ($this->session->all_userdata() as $key => $value) {
                if (
                    strpos($key, 'active_order_table_') === 0 &&
                    (int) $value === $id_transaksi
                ) {
                    $boleh_akses = TRUE;
                    break;
                }
            }
        }

        if (!$boleh_akses) {
            show_404();
            return;
        }

        $data['transaksi'] = $this->db->get_where('transaksi', [
            'id_transaksi' => $id_transaksi
        ])->row_array();

        if (!$data['transaksi']) {
            show_404();
            return;
        }

        $detail = $this->db
            ->select('detail_transaksi.*, menu.nama_menu, menu.harga')
            ->from('detail_transaksi')
            ->join('menu', 'menu.id = detail_transaksi.id_menu', 'left')
            ->where('detail_transaksi.id_transaksi', $id_transaksi)
            ->get()
            ->result_array();

        // Menu yang sudah dihapus tetap ditampilkan semaksimal mungkin
        foreach ($detail as $i => $item) {
            $jumlah   = (int) ($item['jumlah'] ?? 0);
            $subtotal = (float) ($item['subtotal'] ?? 0);

            if (empty($item['nama_menu'])) {
                $detail[$i]['nama_menu'] = 'Menu #' . (int) ($item['id_menu'] ?? 0);
            }

            if (
                !isset($item['harga']) ||
                $item['harga'] === NULL ||
                (float) $item['harga'] <= 0
            ) {
                $detail[$i]['harga'] = $jumlah > 0 ? $subtotal / $jumlah : 0;
            }
        }

        $data['detail_transaksi'] = $detail;

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

    private function _kunci_cart($id_menu, $id_sambal, $level_pedas)
    {
        return (int) $id_menu . ':' . (int) $id_sambal . ':' . (int) $level_pedas;
    }

    private function _pilihan_dari_post($menu)
    {
        $hasil = [
            'id_sambal'   => NULL,
            'nama_sambal' => NULL,
            'level_pedas' => NULL,
            'error'       => NULL
        ];

        if ((int) ($menu['pakai_sambal'] ?? 0) === 1) {
            $id_sambal = (int) $this->input->post('id_sambal');

            if ($id_sambal <= 0) {
                $hasil['error'] = 'Pilih sambal terlebih dahulu sebelum menambahkan menu.';

                return $hasil;
            }

            $sambal = $this->db->get_where('sambal', [
                'id'     => $id_sambal,
                'status' => 'tersedia'
            ])->row_array();

            if (!$sambal) {
                $hasil['error'] = 'Sambal yang dipilih sudah tidak tersedia.';

                return $hasil;
            }

            $hasil['id_sambal']   = (int) $sambal['id'];
            $hasil['nama_sambal'] = $sambal['nama_sambal'];
        }

        if ((int) ($menu['pakai_level'] ?? 0) === 1) {
            $level = (int) $this->input->post('level_pedas');

            if ($level < 1 || $level > 6) {
                $hasil['error'] = 'Pilih level pedas 1 sampai 6 sebelum menambahkan menu.';

                return $hasil;
            }

            $hasil['level_pedas'] = $level;
        }

        return $hasil;
    }

    private function _pilihan_dari_item($menu, $item)
    {
        $hasil = [
            'id_sambal'   => NULL,
            'nama_sambal' => NULL,
            'level_pedas' => NULL,
            'error'       => NULL
        ];

        $id_sambal = isset($item['id_sambal']) ? (int) $item['id_sambal'] : 0;

        if ((int) ($menu['pakai_sambal'] ?? 0) === 1) {
            if ($id_sambal <= 0) {
                $hasil['error'] = 'Salah satu menu sekarang membutuhkan pilihan sambal. Hapus lalu tambahkan ulang menu tersebut.';

                return $hasil;
            }

            $sambal = $this->db->get_where('sambal', [
                'id'     => $id_sambal,
                'status' => 'tersedia'
            ])->row_array();

            if (!$sambal) {
                $hasil['error'] = 'Sambal pada salah satu menu sudah tidak tersedia. Hapus lalu tambahkan ulang menu tersebut.';

                return $hasil;
            }

            $hasil['id_sambal']   = (int) $sambal['id'];
            $hasil['nama_sambal'] = $sambal['nama_sambal'];
        } elseif ($id_sambal > 0) {
            $hasil['error'] = 'Pilihan sambal pada salah satu menu sudah tidak digunakan. Hapus lalu tambahkan ulang menu tersebut.';

            return $hasil;
        }

        $level = isset($item['level_pedas']) ? (int) $item['level_pedas'] : 0;

        if ((int) ($menu['pakai_level'] ?? 0) === 1) {
            if ($level < 1 || $level > 6) {
                $hasil['error'] = 'Salah satu menu sekarang membutuhkan level pedas 1 sampai 6. Hapus lalu tambahkan ulang menu tersebut.';

                return $hasil;
            }

            $hasil['level_pedas'] = $level;
        } elseif ($level > 0) {
            $hasil['error'] = 'Pilihan level pedas pada salah satu menu sudah tidak digunakan. Hapus lalu tambahkan ulang menu tersebut.';

            return $hasil;
        }

        return $hasil;
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