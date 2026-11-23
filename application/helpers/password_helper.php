<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('romeosa_hash_password')) {
    function romeosa_hash_password($password)
    {
        return password_hash((string) $password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('romeosa_verify_password')) {
    /**
     * Verifikasi password baru (bcrypt/argon) sekaligus password lama (MD5).
     * Password lama yang masih valid akan ditandai untuk di-hash ulang.
     */
    function romeosa_verify_password($password, $hash, &$perlu_rehash = NULL)
    {
        $perlu_rehash = FALSE;

        $password = (string) $password;
        $hash     = (string) $hash;

        if ($password === '' || $hash === '') {
            return FALSE;
        }

        $info = password_get_info($hash);

        if (!empty($info['algo'])) {
            $cocok = password_verify($password, $hash);

            if ($cocok && password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $perlu_rehash = TRUE;
            }

            return $cocok;
        }

        // Kompatibilitas akun lama yang masih memakai MD5.
        if (preg_match('/^[a-f0-9]{32}$/i', $hash)
            && hash_equals(strtolower($hash), md5($password))) {
            $perlu_rehash = TRUE;
            return TRUE;
        }

        return FALSE;
    }
}
