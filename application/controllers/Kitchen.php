<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url']);

        // =================================================
        // CEK AKSES KHUS DAPUR
        // =================================================
        if (
            !$this->session->userdata('logged_in') ||
            $this->session->userdata('role') != 'dapur'
        ) {
            $this->session->set_flashdata(
                'msg',
                'Akses ditolak! Khusus Dapur.'
            );

            redirect('auth');
        }
    }

    // =====================================================
    // HALAMAN ANTREAN DAPUR
    // =====================================================
    public function index()
    {
        $this->db->select('
            detail_transaksi.*,
            menu.nama_menu,
            menu.foto,
            transaksi.no_faktur,
            transaksi.tanggal,
            transaksi.nomor_meja,
            transaksi.jenis_pesanan,
            transaksi.status_bayar
        ');

        $this->db->from('detail_transaksi');

        $this->db->join(
            'menu',
            'menu.id = detail_transaksi.id_menu',
            'left'
        );

        $this->db->join(
            'transaksi',
            'transaksi.id_transaksi = detail_transaksi.id_transaksi'
        );

        // Tampilkan pesanan yang menunggu dan sedang dimasak
        $this->db->where_in(
            'detail_transaksi.status',
            ['pending', 'proses', 'diproses']
        );

        // Pesanan belum lunas tidak boleh masuk dapur
        $this->db->where('LOWER(transaksi.status_bayar) =', 'lunas');

        $this->db->order_by('transaksi.tanggal', 'ASC');
        $this->db->limit(50);

        $data['pesanan'] = $this->db
            ->get()
            ->result_array();

        // =================================================
        // DATA LAYOUT
        // =================================================
        $data['title']      = 'Dapur - Ayam Geprek Romeesa';
        $data['page_title'] = 'Daftar Antrean Pesanan Dapur';
        $data['subview']    = 'v_kitchen';
        $data['refresh']    = 10;

        $this->load->view(
            'templates/v_sidebar_kitchen',
            $data
        );
    }

    // =====================================================
    // MULAI MEMASAK PESANAN
    // pending -> proses
    // =====================================================
    public function mulai($id_detail)
    {
        if ($this->input->method() !== 'post') {
            redirect('kitchen');
            return;
        }

        $detail = $this->db->get_where('detail_transaksi', [
            'id_detail' => $id_detail
        ])->row_array();

        if (!$detail) {
            $this->session->set_flashdata(
                'error',
                'Detail pesanan tidak ditemukan!'
            );

            redirect('kitchen');
            return;
        }

        if (!$this->_pesanan_sudah_lunas($detail['id_transaksi'])) {
            $this->session->set_flashdata(
                'error',
                'Pesanan belum lunas sehingga belum bisa diproses dapur!'
            );

            redirect('kitchen');
            return;
        }

        // Pastikan pesanan masih menunggu
        if ($detail['status'] != 'pending') {
            $this->session->set_flashdata(
                'error',
                'Pesanan ini sudah diproses atau statusnya berubah!'
            );

            redirect('kitchen');
            return;
        }

        // Update status menjadi sedang dimasak
        $this->db->where('id_detail', $id_detail);
        $this->db->where('status', 'pending');

        $this->db->update('detail_transaksi', [
            'status' => 'proses'
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata(
                'success',
                'Pesanan berhasil dimulai dan sedang dimasak!'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Status pesanan gagal diperbarui!'
            );
        }

        redirect('kitchen');
    }

    // =====================================================
    // SELESAIKAN PESANAN
    // proses -> selesai
    // =====================================================
    public function selesai($id_detail)
    {
        if ($this->input->method() !== 'post') {
            redirect('kitchen');
            return;
        }

        $detail = $this->db->get_where('detail_transaksi', [
            'id_detail' => $id_detail
        ])->row_array();

        if (!$detail) {
            $this->session->set_flashdata(
                'error',
                'Detail pesanan tidak ditemukan!'
            );

            redirect('kitchen');
            return;
        }

        if (!$this->_pesanan_sudah_lunas($detail['id_transaksi'])) {
            $this->session->set_flashdata(
                'error',
                'Pesanan belum lunas sehingga belum bisa diselesaikan dapur!'
            );

            redirect('kitchen');
            return;
        }

        // Pesanan harus sudah masuk proses memasak
        if (
            $detail['status'] != 'proses' &&
            $detail['status'] != 'diproses'
        ) {
            $this->session->set_flashdata(
                'error',
                'Pesanan harus mulai dimasak terlebih dahulu!'
            );

            redirect('kitchen');
            return;
        }

        // Update status menjadi selesai
        $this->db->where('id_detail', $id_detail);
        $this->db->where_in('status', ['proses', 'diproses']);

        $this->db->update('detail_transaksi', [
            'status' => 'selesai'
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata(
                'success',
                'Pesanan selesai dimasak dan siap disajikan!'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Status pesanan gagal diperbarui!'
            );
        }

        redirect('kitchen');
    }

    // =====================================================
    // CEK STATUS PEMBAYARAN PESANAN
    // =====================================================
    private function _pesanan_sudah_lunas($id_transaksi)
    {
        $transaksi = $this->db->get_where('transaksi', [
            'id_transaksi' => $id_transaksi
        ])->row_array();

        return $transaksi &&
            strtolower(trim($transaksi['status_bayar'])) === 'lunas';
    }
}