
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session']);
        $this->load->helper(['url', 'form']);
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
        $username = $this->input->post('username', true);
        $password = md5($this->input->post('password'));

        $user = $this->db
            ->get_where('users', ['username' => $username])
            ->row_array();

        if ($user) {

            if ($user['password'] == $password) {

                // Simpan session login karyawan
                $sesData = [
                    'id'        => $user['id'],
                    'nama'      => $user['nama'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];

                $this->session->set_userdata($sesData);

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

            } else {
                $this->session->set_flashdata('msg', 'Password salah!');
                redirect('auth');
                return;
            }

        } else {
            $this->session->set_flashdata('msg', 'Username tidak ditemukan!');
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
