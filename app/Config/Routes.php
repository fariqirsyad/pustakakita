<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Variabel Filter
$authFilter = ['filter' => 'auth'];

// Variabel Role
$admin     = ['filter' => 'role:admin'];
$user     = ['filter' => 'role:user'];
$allRole   = ['filter' => 'role:admin, user'];

// Login
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// Halaman utama
$routes->get('/', 'Home::index', $authFilter);
$routes->get('/dashboard', 'Home::index', $authFilter);
$routes->get('/users/create', 'Users::create');  // form tambah user
$routes->post('/users/store', 'Users::store');   // aksi simpan user
$routes->get('/users', 'Users::index', $allRole); // menampilkan data user
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRole); // form edit user
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRole); // aksi update user
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $allRole); // aksi hapus user
// Menghubungkan URL /buku ke Controller Buku fungsi index
$routes->get('buku', 'Buku::index');
// Route untuk melihat daftar peminjaman
$routes->get('peminjaman', 'Peminjaman::index');

// Route untuk formulir transaksi pinjam baru
$routes->get('peminjaman/tambah', 'Peminjaman::tambah');
$routes->post('peminjaman/simpan', 'Peminjaman::simpan');
$routes->get('buku/tambah', 'Buku::tambah');
$routes->post('buku/simpan', 'Buku::simpan'); // Untuk memproses data yang diinput
$routes->post('buku/hapus/(:num)', 'Buku::hapus/$1');
// Tambahkan baris ini untuk menangkap ID buku dari tombol "Pinjam Sekarang"
$routes->get('peminjaman/proses/(:num)', 'Peminjaman::simpan/$1');

$routes->get('peminjaman', 'Peminjaman::index');
$routes->get('peminjaman/tambah', 'Peminjaman::tambah');
$routes->get('peminjaman/detail/(:num)', 'Peminjaman::detail/$1');
$routes->get('buku/tambah', 'Buku::tambah');
$routes->get('laporan', 'Laporan::index');
$routes->get('pengembalian', 'Pengembalian::index');
$routes->get('peminjaman/proses_kembali/(:num)', 'Peminjaman::proses_kembali/$1');
// Gunakan session() untuk mengecek role di Routes
if (session()->get('role') == 1) { 
    $routes->get('users', 'Users::index');
    // ... route admin lainnya ...
}
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRole); // form edit user
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRole); // aksi update user
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $allRole); // aksi hapus user
$routes->get('users/detail/(:num)', 'Users::detail/$1', $allRole); // aksi detail user
$routes->get('users/print', 'Users::print', $allRole); // aksi print data user
$routes->get('users/wa/(:num)', 'Users::wa/$1', $allRole); // aksi kirim ke whatsapp
$routes->get('buku/ajukan/(:num)', 'Buku::ajukan/$1');
$routes->get('peminjaman/konfirmasi/(:num)/(:any)', 'Peminjaman::konfirmasi/$1/$2');
// Izinkan akses POST untuk proses pengembalian
$routes->post('peminjaman/proses_kembali/(:num)', 'Peminjaman::proses_kembali/$1');

// Opsional: tambahkan ini juga jika belum ada untuk fitur konfirmasi
$routes->get('peminjaman/konfirmasi/(:num)/(:any)', 'Peminjaman::konfirmasi/$1/$2');
$routes->post('buku/ajukan/(:num)', 'Buku::ajukan/$1');