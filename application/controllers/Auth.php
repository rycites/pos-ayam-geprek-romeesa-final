
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'form', 'password']);
    }

    // =====================================================
    // HALAMAN LOGIN
    // =====================================================
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');

            if ($role == 'kasir') {
                redirect('cashier');
                return;
            } elseif ($role == 'owner') {
                redirect('owner');
                return;
            } elseif ($role == 'dapur') {
                redirect('kitchen');
                return;
            }
        }

        $this->load->view('v_login');
    }

    // =====================================================
    // PROSES LOGIN
    // =====================================================
    public function loginProcess()
    {
        if ($this->input->method() !== 'post') {
            redirect('auth');
            return;
        }

        $username = trim((string) $this->input->post('username', true));
        $password = (string) $this->input->post('password');

        if ($username === '' || $password === '') {
            $this->session->set_flashdata('msg', 'Username dan password wajib diisi!');
            redirect('auth');
            return;
        }

        $user = $this->db
            ->get_where('users', ['username' => $username])
            ->row_array();

        if (!$user) {
            $this->session->set_flashdata('msg', 'Username tidak ditemukan!');
            redirect('auth');
            return;
        }

        $perlu_rehash = FALSE;

        if (!romeosa_verify_password($password, $user['password'], $perlu_rehash)) {
            $this->session->set_flashdata('msg', 'Password salah!');
            redirect('auth');
            return;
        }

        // Akun lama (MD5) otomatis ditingkatkan ke hash yang lebih aman.
        if ($perlu_rehash) {
            $this->db->where('id', $user['id']);
            $this->db->update('users', [
                'password' => romeosa_hash_password($password)
            ]);
        }

        // Simpan session login karyawan
        $sesData = [
            'id'        => $user['id'],
            'nama'      => $user['nama'],
            'username'  => $user['username'],
            'role'      => $user['role'],
            'logged_in' => TRUE
        ];

        $this->session->set_userdata($sesData);
        $this->session->sess_regenerate(TRUE);

        // Arahkan sesuai role
        if ($user['role'] == 'kasir') {
            redirect('cashier');
            return;
        } elseif ($user['role'] == 'owner') {
            redirect('owner');
            return;
        } elseif ($user['role'] == 'dapur') {
            redirect('kitchen');
            return;
        } else {
            redirect('auth');
            return;
        }
    }

    // =====================================================
    // LOGOUT KARYAWAN
    // Session pelanggan tidak ikut dihapus
    // =====================================================
    public function logout()
    {
        if ($this->input->method() !== 'post') {
            redirect('auth');
            return;
        }

        $this->session->unset_userdata([
            'id',
            'nama',
            'username',
            'role',
            'logged_in'
        ]);

        redirect('auth');
    }
}
