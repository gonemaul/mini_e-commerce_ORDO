<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan selama proses autentikasi untuk berbagai
    | pesan yang perlu kami tampilkan kepada pengguna. Anda bebas untuk
    | mengubah baris bahasa ini sesuai dengan kebutuhan aplikasi Anda.
    |
    */

    'failed' => 'Kredensial ini tidak cocok dengan catatan kami.',
    'email' => 'Email yang diberikan tidak benar.',
    'password' => 'Kata sandi yang diberikan tidak benar.',
    'throttle' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.',

    'inactive' => 'Akun belum aktif, Silakan verifikasi email Anda.',
    'profile' => 'gambar_profil',
    'logout' => 'Anda telah berhasil logout!',
    'delete_account' => [
        'title' => 'Hapus Akun',
        'success' => 'Akun Anda telah berhasil dihapus!',
        'failed' => 'Anda tidak dapat menghapus akun ini..',
        'info' => 'Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
                Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan',
        'confirm' => 'Apa anda yakin menghapus akun ini?..'
    ],
    'logout_other' => 'Berhasil keluar dari device lain',

    // Tampilan Web
    'login_title' => 'Masuk',
    'register_title' => 'Daftar',
    'remember_title' => 'Ingat Saya',
    'forgot_password_title' => 'Lupa Kata Sandi',
    'login_to_register' => "Belum punya Akun?",
    'register_to_login' => 'Sudah punya Akun?',
    'register_info' => 'Dengan membuat akun, Anda menyetujui',

    // profile
    'sesi_title' => 'Sesi Browser',
    'this_sesi' => 'Device ini',
    'btn_lod' => 'Keluar dari device lain',

    // Lupa kata sandi
    'info_text' => 'Silakan masukkan email Anda untuk mereset kata sandi',
    'btn_request' => 'Minta Tautan',
    'new_pw' => 'Silakan masukkan kata sandi baru!..',
    'update_pw' => 'Perbarui Kata Sandi',
    'info_verify' => "Jika Anda belum menerima email, silakan tekan tombol di bawah ini",
    'btn_resend' => 'Kirim Ulang Tautan',
    'verify_invalid' => 'Verifikasi Anda tidak valid',
    'verify_expired' => 'Tautan verifikasi kadaluarsa.',
    'verify_success' => 'Email berhasil diverifikasi. Akun Anda telah diaktifkan.',
    'token_reset_pw' => 'Silakan masukkan token untuk mereset kata sandi...',

    // API
    'register_success' => 'Anda telah berhasil mendaftar, Silakan cek kotak masuk Anda untuk email verifikasi.',
    'login_success' => 'Login berhasil.',
    'access_token' => 'Token Akses',
    'token_type' => 'Jenis Token',
    'email_verified' => 'Email sudah diverifikasi.',
    'verify_link_success' => 'Tautan verifikasi berhasil dikirim, Silakan cek kotak masuk Anda untuk email verifikasi.',
    'reset_password_link' => 'Tautan reset kata sandi telah dikirim ke email Anda',
    'reset_password_link_failed' => 'Gagal mengirim tautan reset kata sandi',
    'reset_password_success' => 'Reset kata sandi Anda berhasil',
    'reset_password_failed' => 'Gagal mereset kata sandi',
    'update_profile_email_success' => 'Profil Anda telah berhasil diubah, Silakan cek kotak masuk Anda untuk verifikasi email sebelum login kembali.',
    'update_profile_success' => 'Profil Anda telah berhasil diubah',
    'update_profile_failed' => 'Gagal mengubah profil',
    'update_password_failed' => 'Kata sandi baru sama dengan kata sandi lama',
    'update_password_success' => 'Kata sandi Anda telah berhasil diperbarui',
];