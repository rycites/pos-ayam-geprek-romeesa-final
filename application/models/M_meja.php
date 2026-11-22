<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_meja extends CI_Model {

    // Fungsi untuk mengambil semua data meja
    public function get_data_meja() {
        return $this->db->get('tbl_meja')->result();
    }

    // Fungsi untuk mengambil data berdasarkan ID meja tertentu
    public function get_meja_by_id($id_meja) {
        return $this->db->get_where('tbl_meja', array('id_meja' => $id_meja))->row();
    }

}