
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    private $max_attempts = 5;
    private $lockout_time = 300;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    /**
     * Menampilkan halaman login.
     */
    public function index()
    {
        if ($this->is_logged_in()) {
            $this->redirect_by_role();
            return;
        }

        $this->load->view('v_login');
    }

    /**
     * Memproses autentikasi pengguna.
     */
    public function loginProcess()
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $username = trim(
            $this->input->post('username', true)
        );

        $password = $this->input->post('password');

        if (empty($username) || empty($password)) {
            $this->set_message(
                'Username dan password wajib diisi!'
            );

            redirect('auth');
            return;
        }

        if ($this->is_locked($username)) {
            $this->set_message(
                'Terlalu banyak percobaan login. Coba lagi nanti.'
            );

            redirect('auth');
            return;
        }

        $user = $this->db
            ->get_where('users', [
                'username' => $username
            ])
            ->row_array();

        if (!$user) {
            $this->record_failed_attempt($username);

            $this->set_message(
                'Username tidak ditemukan!'
            );

            redirect('auth');
            return;
        }

        if (!$this->verify_password(
            $password,
            $user['password']
        )) {
            $this->record_failed_attempt($username);

            $this->set_message(
                'Password salah!'
            );

            redirect('auth');
            return;
        }

        $this->reset_failed_attempts($username);

        $this->create_user_session($user);

        $this->redirect_by_role();
    }

    /**
     * Memeriksa password pengguna.
     */
    private function verify_password($input, $stored)
    {
        return hash_equals(
            (string) $stored,
            md5($input)
        );
    }

    /**
     * Membuat sesi pengguna.
     */
    private function create_user_session($user)
    {
        $this->session->sess_regenerate(true);

        $session_data = [
            'id'        => $user['id'],
            'nama'      => $user['nama'],
            'username'  => $user['username'],
            'role'      => $user['role'],
            'logged_in' => TRUE
        ];

        $this->session->set_userdata(
            $session_data
        );
    }

    /**
     * Memeriksa apakah pengguna sudah login.
     */
    public function is_logged_in()
    {
        return (bool) $this->session->userdata(
            'logged_in'
        );
    }

    /**
     * Mengambil role pengguna saat ini.
     */
    public function current_role()
    {
        return $this->session->userdata('role');
    }

    /**
     * Mengambil ID pengguna saat ini.
     */
    public function current_user_id()
    {
        return $this->session->userdata('id');
    }

    /**
     * Mengarahkan pengguna berdasarkan role.
     */
    private function redirect_by_role()
    {
        $role = $this->current_role();

        switch ($role) {
            case 'kasir':
                redirect('cashier');
                break;

            case 'owner':
                redirect('owner');
                break;

            case 'dapur':
                redirect('kitchen');
                break;

            default:
                $this->logout();
                break;
        }
    }

    /**
     * Memeriksa hak akses berdasarkan role.
     */
    public function check_role($allowed_roles = [])
    {
        if (!$this->is_logged_in()) {
            redirect('auth');
            return FALSE;
        }

        if (!in_array(
            $this->current_role(),
            $allowed_roles,
            true
        )) {
            show_error(
                'Anda tidak memiliki hak akses.',
                403
            );

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Menyimpan pesan notifikasi.
     */
    private function set_message($message)
    {
        $this->session->set_flashdata(
            'msg',
            $message
        );
    }

    /**
     * Memeriksa status pembatasan login.
     */
    private function is_locked($username)
    {
        $attempts = $this->session->userdata(
            'login_attempts_' . $username
        );

        if (!$attempts) {
            return FALSE;
        }

        if ($attempts['count'] < $this->max_attempts) {
            return FALSE;
        }

        $elapsed = time() - $attempts['time'];

        return $elapsed < $this->lockout_time;
    }

    /**
     * Mencatat percobaan login yang gagal.
     */
    private function record_failed_attempt($username)
    {
        $key = 'login_attempts_' . $username;

        $attempts = $this->session->userdata($key);

        if (!$attempts) {
            $attempts = [
                'count' => 0,
                'time'  => time()
            ];
        }

        if (
            time() - $attempts['time']
            >= $this->lockout_time
        ) {
            $attempts = [
                'count' => 0,
                'time'  => time()
            ];
        }

        $attempts['count']++;

        $this->session->set_userdata(
            $key,
            $attempts
        );
    }

    /**
     * Menghapus catatan percobaan login gagal.
     */
    private function reset_failed_attempts($username)
    {
        $this->session->unset_userdata(
            'login_attempts_' . $username
        );
    }

    /**
     * Menghapus sesi dan logout.
     */
    public function logout()
    {
        $this->session->sess_destroy();

        redirect('auth');
    }
}
