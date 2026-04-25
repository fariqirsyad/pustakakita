<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Hitung Total Koleksi (Gunakan total baris atau sum stok)
        // Jika ingin menghitung jumlah judul buku, gunakan countAllResults()
        $total_buku = $db->table('buku')->countAllResults(); 

        // 2. Hitung Buku yang sedang dipinjam
        $total_dipinjam = $db->table('peminjaman')
                            ->where('status', 'dipinjam')
                            ->countAllResults();

        // 3. Hitung Siswa Aktif
        $total_siswa = $db->table('users')
                          ->where('role', 'user') 
                          ->where('status', 'aktif')
                          ->countAllResults();

        // 4. Ambil Peminjaman Aktif (Semua yang sedang dipinjam agar tabel tidak kosong)
        $aktivitas = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul, users.nama, users.kelas') 
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id_user = peminjaman.id_user')
            ->where('peminjaman.status', 'dipinjam')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC') // Urutkan dari yang terbaru dipinjam
            ->get()->getResultArray();

        // 5. Kirim data ke view 
        // PENTING: Nama Key di sini harus sama persis dengan variabel di View
        $data = [
            'title'          => 'Dashboard PustakaKita',
            'total_buku'     => $total_buku,     // Sesuaikan dengan View (pakai underscore)
            'total_dipinjam' => $total_dipinjam, // Sesuaikan dengan View
            'total_siswa'    => $total_siswa,    // Sesuaikan dengan View
            'aktivitas'      => $aktivitas       // Sesuaikan dengan View
        ];

        return view('dashboard/index', $data); // Pastikan path view benar
    }
}