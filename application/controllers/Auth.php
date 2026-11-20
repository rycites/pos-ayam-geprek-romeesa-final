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

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            if ($role == 'kasir') {
                redirect('cashier');
            } elseif ($role == 'owner') {
                redirect('owner');
            } elseif ($role == 'dapur') {
                redirect('kitchen');
            }
        }
        $this->load->view('v_login');
    }

    public function loginProcess()
    {
        $username = $this->input->post('username', true);
        $password = md5($this->input->post('password'));

        $user = $this->db->get_where('users', ['username' => $username])->row_array();

        if ($user) {
            if ($user['password'] == $password) {
                $sesData = [
                    'id'        => $user['id'],
                    'nama'      => $user['nama'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($sesData);

                // Arahkan berdasarkan role dengan site_url yang bersih
                if ($user['role'] == 'kasir') {
                    redirect('cashier');
                } elseif ($user['role'] == 'owner') {
                    redirect('owner');
                } elseif ($user['role'] == 'dapur') {
                    redirect('kitchen');
                } else {
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('msg', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('msg', 'Username tidak ditemukan!');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}