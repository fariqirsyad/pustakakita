<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- Variabel Filter & Role ---
$authFilter = ['filter' => 'auth'];
$admin      = ['filter' => 'role:admin'];
$user       = ['filter' => 'role:user'];
$allRole    = ['filter' => 'role:admin,user'];

// --- Auth & System ---
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// --- FITUR PENDAFTARAN (DIPINDAH KE LUAR AGAR BISA DIKLIK) ---
$routes->get('users/create', 'Users::create');
$routes->post('users/store', 'Users::store');

// Fitur Admin System
$routes->get('/backup', 'Backup::index', $admin);
$routes->get('/restore', 'Restore::index');
$routes->post('/restore/auth', 'Restore::auth');
$routes->get('/restore/form', 'Restore::form');
$routes->post('/restore/process', 'Restore::process');

// --- Dashboard ---
$routes->get('/', 'Dashboard::index', $authFilter);
$routes->get('dashboard', 'Dashboard::index', $authFilter);

// --- Manajemen Buku ---
$routes->group('buku', $authFilter, function ($routes) {
    $routes->get('/', 'Buku::index');
    $routes->get('detail/(:num)', 'Buku::detail/$1');

    // Khusus Admin
    $routes->get('tambah', 'Buku::tambah', ['filter' => 'role:admin']);
    $routes->post('simpan', 'Buku::simpan', ['filter' => 'role:admin']);
    $routes->get('edit/(:num)', 'Buku::edit/$1', ['filter' => 'role:admin']);
    $routes->post('update/(:num)', 'Buku::update/$1', ['filter' => 'role:admin']);
    $routes->get('hapus/(:num)', 'Buku::hapus/$1', ['filter' => 'role:admin']);
});

// --- Manajemen Users (Area Login) ---
$routes->group('users', $allRole, function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('detail/(:num)', 'Users::detail/$1');
    $routes->get('wa/(:num)', 'Users::wa/$1');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->post('update/(:num)', 'Users::update/$1');

    // Khusus Admin di dalam grup Users
    $routes->get('delete/(:num)', 'Users::delete/$1', ['filter' => 'role:admin']);
    $routes->get('print', 'Users::print', ['filter' => 'role:admin']);
});

// --- Transaksi Peminjaman ---
$routes->group('peminjaman', $authFilter, function ($routes) {
    $routes->get('/', 'Peminjaman::index');
    $routes->post('simpan', 'Peminjaman::simpan');
    $routes->get('detail/(:num)', 'Peminjaman::detail/$1');
    $routes->get('konfirmasi_selesai/(:num)', 'Peminjaman::konfirmasi_selesai/$1');

    // Khusus Admin
    $routes->get('tambah', 'Peminjaman::tambah', ['filter' => 'role:admin']);
    $routes->get('konfirmasi/(:num)/(:any)', 'Peminjaman::konfirmasi/$1/$2', ['filter' => 'role:admin']);
    $routes->post('proses_kembali/(:num)', 'Peminjaman::proses_kembali/$1', ['filter' => 'role:admin']);
    $routes->post('user_kembali/(:num)', 'Peminjaman::user_kembali/$1');
    $routes->get('verifikasi_kembali/(:num)', 'Peminjaman::verifikasi_kembali/$1', ['filter' => 'role:admin']);
});

// --- Ulasan ---
$routes->group('ulasan', $authFilter, function ($routes) {
    $routes->get('tambah/(:num)', 'Ulasan::tambah/$1');
    $routes->post('simpan', 'Ulasan::simpan');
});

// --- Laporan (Khusus Admin) ---
$routes->get('laporan', 'Laporan::index', $admin);
$routes->get('laporan/filter', 'Laporan::filter', $admin);
$routes->get('pengembalian', 'Pengembalian::index', $admin);

// --- Favorite ---
$routes->group('favorite', function ($routes) {
    $routes->get('/', 'Favorite::index');
    $routes->get('tambah/(:num)', 'Favorite::tambah/$1');
});