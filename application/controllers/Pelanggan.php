
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'number', 'date', 'cookie']);
    }

    // =====================================================
    // COOKIE IDENTITAS PESANAN PELANGGAN
    // =====================================================
    private function cookie_pesanan($no_meja)
    {
        return 'romeesa_order_table_' . (int)$no_meja;
    }

    // =====================================================
    // KERANJANG DIPISAH PER MEJA
    // =====================================================
    private function cart_key($no_meja)
    {
        return 'cart_pelanggan_' . (int)$no_meja;
    }

    // =====================================================
    // HALAMAN MENU PELANGGAN
    // =====================================================
    public function meja($no_meja = 1)
    {
        $no_meja = (int)$no_meja;

        $this->session->set_userdata('active_table', $no_meja);

        $menu = $this->db
            ->where('status', 'tersedia')
            ->get('menu')
            ->result_array();

        $sambal_tersedia = $this->db
            ->where('status', 'tersedia')
            ->order_by('nama_sambal', 'ASC')
            ->get('sambal')
            ->result_array();

        $cart = $this->session->userdata($this->cart_key($no_meja));

        if (!is_array($cart)) {
            $cart = [];
        }

        $data = [
            'menu'            => $menu,
            'cart'            => $cart,
            'sambal_tersedia' => $sambal_tersedia,
            'nomor_meja'      => $no_meja
        ];

        $this->load->view('v_pelanggan', $data);
    }

    // =====================================================
    // TAMBAH MENU KE KERANJANG
    // =====================================================
    public function add_cart($id_menu, $no_meja = 1)
    {
        if ($this->input->method() !== 'post') {
            redirect('pelanggan/meja/' . (int)$no_meja);
            return;
        }

        $id_menu = (int)$id_menu;
        $no_meja = (int)$no_meja;

        $menu = $this->db
            ->where('id', $id_menu)
            ->where('status', 'tersedia')
            ->get('menu')
            ->row_array();

        if (!$menu) {
            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        $pilihan = $this->_pilihan_dari_post($menu);

        if ($pilihan['error'] !== NULL) {
            $this->session->set_flashdata('error', $pilihan['error']);

            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        $cart = $this->session->userdata($this->cart_key($no_meja));

        if (!is_array($cart)) {
            $cart = [];
        }

        $kunci = $this->_kunci_cart(
            $menu['id'],
            $pilihan['id_sambal'],
            $pilihan['level_pedas']
        );

        if (isset($cart[$kunci])) {
            $cart[$kunci]['jumlah']++;

            $cart[$kunci]['subtotal'] =
                $cart[$kunci]['jumlah'] * $cart[$kunci]['harga'];
        } else {
            $cart[$kunci] = [
                'id_menu'     => $menu['id'],
                'nama_menu'   => $menu['nama_menu'],
                'harga'       => $menu['harga'],
                'gambar'      => '',
                'jumlah'      => 1,
                'subtotal'    => $menu['harga'],
                'id_sambal'   => $pilihan['id_sambal'],
                'nama_sambal' => $pilihan['nama_sambal'],
                'level_pedas' => $pilihan['level_pedas'],
                'cart_key'    => $kunci
            ];
        }

        $this->session->set_userdata($this->cart_key($no_meja), $cart);

        redirect('pelanggan/meja/' . $no_meja);
    }

    // =====================================================
    // HAPUS MENU DARI KERANJANG
    // =====================================================
    public function remove_cart($arg1 = NULL, $no_meja = NULL)
    {
        if ($this->input->method() !== 'post') {
            $no_meja = ($no_meja !== NULL) ? (int)$no_meja : (int)$arg1;

            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        if ($no_meja === NULL) {
            $no_meja    = (int)$arg1;
            $id_menu_lama = NULL;
        } else {
            $id_menu_lama = $arg1;
            $no_meja      = (int)$no_meja;
        }

        $cart_key = $this->cart_key($no_meja);
        $cart     = $this->session->userdata($cart_key);

        if (is_array($cart)) {
            $kunci_post = (string) $this->input->post('cart_key');

            if ($kunci_post !== '' && isset($cart[$kunci_post])) {

                unset($cart[$kunci_post]);

                $this->session->set_userdata($cart_key, $cart);

            } elseif ($id_menu_lama !== NULL) {

                foreach ($cart as $kunci => $item) {
                    if (
                        (string) $kunci === (string) $id_menu_lama ||
                        (string) ($item['id_menu'] ?? '') === (string) $id_menu_lama
                    ) {
                        unset($cart[$kunci]);

                        $this->session->set_userdata($cart_key, $cart);

                        break;
                    }
                }
            }
        }

        redirect('pelanggan/meja/' . $no_meja);
    }

    // =====================================================
    // CHECKOUT PESANAN PELANGGAN
    // =====================================================
    public function checkout($no_meja = 1)
    {
        if ($this->input->method() !== 'post') {
            redirect('pelanggan/meja/' . (int)$no_meja);
            return;
        }

        $no_meja  = (int)$no_meja;
        $cart_key = $this->cart_key($no_meja);

        $cart_session = $this->session->userdata($cart_key);

        if (empty($cart_session) || !is_array($cart_session)) {
            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        $nama_pelanggan = trim((string) $this->input->post('nama_pelanggan', true));
        $metode = $this->input->post('metode_pembayaran');

        if ($nama_pelanggan === '') {
            $nama_pelanggan = 'Pelanggan';
        }

        if (!in_array($metode, ['Cash', 'QRIS'], TRUE)) {
            $metode = 'Cash';
        }

        // -------------------------------------------------
        // VALIDASI ULANG KERANJANG DARI DATABASE
        // -------------------------------------------------
        $cart  = [];
        $total = 0;

        foreach ($cart_session as $kunci_lama => $item) {
            $id_menu = isset($item['id_menu'])
                ? (int) $item['id_menu']
                : (int) $kunci_lama;
            $jumlah  = isset($item['jumlah']) ? (int) $item['jumlah'] : 0;

            if ($id_menu <= 0 || $jumlah <= 0) {
                $this->session->set_flashdata(
                    'error',
                    'Data keranjang tidak valid. Silakan perbarui keranjang.'
                );

                redirect('pelanggan/meja/' . $no_meja);
                return;
            }

            $menu = $this->db
                ->where('id', $id_menu)
                ->where('status', 'tersedia')
                ->get('menu')
                ->row_array();

            if (!$menu) {
                $this->session->set_flashdata(
                    'error',
                    'Salah satu menu di keranjang sudah tidak tersedia. Silakan perbarui keranjang.'
                );

                redirect('pelanggan/meja/' . $no_meja);
                return;
            }

            $pilihan = $this->_pilihan_dari_item($menu, $item);

            if ($pilihan['error'] !== NULL) {
                $this->session->set_flashdata('error', $pilihan['error']);

                redirect('pelanggan/meja/' . $no_meja);
                return;
            }

            $harga    = (float) $menu['harga'];
            $subtotal = $harga * $jumlah;
            $kunci    = $this->_kunci_cart(
                $menu['id'],
                $pilihan['id_sambal'],
                $pilihan['level_pedas']
            );

            if (isset($cart[$kunci])) {
                $cart[$kunci]['jumlah'] += $jumlah;
                $cart[$kunci]['subtotal'] += $subtotal;
            } else {
                $cart[$kunci] = [
                    'id_menu'     => $menu['id'],
                    'nama_menu'   => $menu['nama_menu'],
                    'harga'       => $harga,
                    'jumlah'      => $jumlah,
                    'subtotal'    => $subtotal,
                    'id_sambal'   => $pilihan['id_sambal'],
                    'nama_sambal' => $pilihan['nama_sambal'],
                    'level_pedas' => $pilihan['level_pedas'],
                    'cart_key'    => $kunci
                ];
            }

            $total += $subtotal;
        }

        if ($total <= 0) {
            $this->session->set_flashdata(
                'error',
                'Total pesanan tidak valid.'
            );

            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        $no_faktur = 'TRX-' . date('Ymd') . '-' . rand(100, 999);

        $this->db->trans_begin();

        // -------------------------------------------------
        // SIMPAN TRANSAKSI
        // -------------------------------------------------
        $data_transaksi = [
            'no_faktur'           => $no_faktur,
            'tanggal'             => date('Y-m-d H:i:s'),
            'total_bayar'        => $total,
            'uang_bayar'         => 0,
            'uang_kembalian'     => 0,
            'id_kasir'           => 0,
            'nomor_meja'         => 'Meja ' . $no_meja . ' (' . $nama_pelanggan . ')',
            'jenis_pesanan'      => 'dine_in',
            'status_bayar'       => 'pending',
            'metode_pembayaran' => $metode
        ];

        $this->db->insert('transaksi', $data_transaksi);

        $id_transaksi = $this->db->insert_id();

        if (!$id_transaksi) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Pesanan gagal disimpan. Silakan coba lagi.'
            );

            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        // -------------------------------------------------
        // SIMPAN DETAIL TRANSAKSI
        // -------------------------------------------------
        foreach ($cart as $item) {
            $this->db->insert('detail_transaksi', [
                'id_transaksi' => $id_transaksi,
                'id_menu'       => $item['id_menu'],
                'id_sambal'     => $item['id_sambal'],
                'nama_sambal'   => $item['nama_sambal'],
                'level_pedas'   => $item['level_pedas'],
                'jumlah'        => $item['jumlah'],
                'subtotal'      => $item['subtotal'],
                'status'        => 'menunggu_kasir'
            ]);
        }

        // -------------------------------------------------
        // CEK TRANSAKSI
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Pesanan gagal disimpan. Silakan coba lagi.'
            );

            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        $this->db->trans_commit();

        // -------------------------------------------------
        // SIMPAN ID PESANAN DI SESSION DAN COOKIE
        // -------------------------------------------------
        $session_key = 'active_order_table_' . $no_meja;
        $cookie_name = $this->cookie_pesanan($no_meja);

        $this->session->set_userdata($session_key, $id_transaksi);

        set_cookie(
            $cookie_name,
            $id_transaksi,
            172800,
            '',
            '/',
            '',
            FALSE,
            TRUE
        );

        // -------------------------------------------------
        // KOSONGKAN KERANJANG
        // -------------------------------------------------
        $this->session->unset_userdata($cart_key);

        redirect('pelanggan/status/' . $no_meja);
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
    // STATUS PESANAN PELANGGAN
    // =====================================================
    public function status($no_meja = 1)
    {
        $no_meja = (int)$no_meja;

        $session_key = 'active_order_table_' . $no_meja;
        $cookie_name = $this->cookie_pesanan($no_meja);

        // Ambil ID pesanan dari session
        $id_transaksi = $this->session->userdata($session_key);

        // Jika session hilang, ambil dari cookie
        if (!$id_transaksi) {
            $id_transaksi = get_cookie($cookie_name, TRUE);

            if ($id_transaksi) {
                $this->session->set_userdata($session_key, $id_transaksi);
            }
        }

        // Jika session dan cookie sama-sama tidak ada
        if (!$id_transaksi) {
            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        // -------------------------------------------------
        // AMBIL DATA TRANSAKSI
        // -------------------------------------------------
        $transaksi = $this->db
            ->where('id_transaksi', (int)$id_transaksi)
            ->get('transaksi')
            ->row_array();

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            $this->session->unset_userdata($session_key);
            delete_cookie($cookie_name, '', '/', '');

            redirect('pelanggan/meja/' . $no_meja);
            return;
        }

        // -------------------------------------------------
        // AMBIL DETAIL PESANAN
        // FIX: Tidak mengambil kolom menu.gambar
        // karena kolom tersebut tidak tersedia di database.
        // -------------------------------------------------
        $detail = $this->db
            ->select('detail_transaksi.*, menu.nama_menu')
            ->from('detail_transaksi')
            ->join(
                'menu',
                'menu.id = detail_transaksi.id_menu',
                'left'
            )
            ->where(
                'detail_transaksi.id_transaksi',
                (int)$id_transaksi
            )
            ->get()
            ->result_array();

        // -------------------------------------------------
        // KIRIM DATA KE VIEW STATUS PELANGGAN
        // -------------------------------------------------
        $data = [
            'transaksi'  => $transaksi,
            'detail'     => $detail,
            'nomor_meja' => $no_meja
        ];

        $this->load->view('v_pelanggan_status', $data);
    }

    // =====================================================
    // CEK STATUS PESANAN AKTIF
    // =====================================================
    public function cek_status_aktif($no_meja = 1)
    {
        $no_meja = (int)$no_meja;

        $session_key = 'active_order_table_' . $no_meja;
        $cookie_name = $this->cookie_pesanan($no_meja);

        $id_transaksi = $this->session->userdata($session_key);

        // Jika session hilang, gunakan cookie
        if (!$id_transaksi) {
            $id_transaksi = get_cookie($cookie_name, TRUE);

            if ($id_transaksi) {
                $this->session->set_userdata($session_key, $id_transaksi);
            }
        }

        if ($id_transaksi) {
            redirect('pelanggan/status/' . $no_meja);
        } else {
            redirect('pelanggan/meja/' . $no_meja);
        }
    }

    // =====================================================
    // RESET SESI PESANAN MEJA
    // =====================================================
    public function reset_sesi_meja($no_meja = 1)
    {
        if ($this->input->method() !== 'post') {
            redirect('pelanggan/meja/' . (int)$no_meja);
            return;
        }

        $no_meja = (int)$no_meja;

        $session_key = 'active_order_table_' . $no_meja;
        $cookie_name = $this->cookie_pesanan($no_meja);

        $this->session->unset_userdata($session_key);
        delete_cookie($cookie_name, '', '/', '');

        $this->session->unset_userdata($this->cart_key($no_meja));

        redirect('pelanggan/meja/' . $no_meja);
    }
}
