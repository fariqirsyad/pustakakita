<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UsersModel;
use DateTime;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;
    protected $usersModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->bukuModel = new BukuModel();
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $role = session()->get('role');
        $id_user = session()->get('id_user');
        
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul, users.nama');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        
        // Filter: Jika bukan admin, hanya tampilkan pinjaman milik sendiri
        if ($role != 'admin') {
            $builder->where('peminjaman.id_user', $id_user);
        }

        $builder->orderBy('peminjaman.id_pinjam', 'DESC');
        
        $data = [
            'title'      => 'Daftar Transaksi Peminjaman',
            'peminjaman' => $builder->get()->getResultArray()
        ];

        return view('peminjaman/index', $data);
    }

    // Alur Pinjam Langsung oleh User
    public function simpan()
    {
        $id_buku = $this->request->getPost('id_buku');
        $buku = $this->bukuModel->find($id_buku);

        if ($buku['stok'] > 0) {
            $this->peminjamanModel->save([
                'id_user'        => session()->get('id_user'),
                'id_buku'        => $id_buku,
                'tanggal_pinjam' => date('Y-m-d H:i:s'),
                'durasi'         => $this->request->getPost('durasi_pinjam'),
                'status'         => 'dipinjam' // Langsung aktif tanpa konfirmasi
            ]);

            // Kurangi stok buku secara otomatis
            $this->bukuModel->update($id_buku, ['stok' => $buku['stok'] - 1]);

            return redirect()->to('/peminjaman')->with('success', 'Buku berhasil dipinjam!');
        }
        return redirect()->back()->with('error', 'Maaf, stok buku sedang habis!');
    }

    // Fungsi User Melaporkan Pengembalian & Upload Bukti Transfer
   public function user_kembali($id)
{
    $dataPinjam = $this->peminjamanModel->find($id);
    
    // Logika Hitung Denda Otomatis
    $deadline = date('Y-m-d', strtotime($dataPinjam['tanggal_pinjam']. ' + ' . $dataPinjam['durasi'] . ' days'));
    $hari_ini = date('Y-m-d');
    $total_denda = 0;

    if ($hari_ini > $deadline) {
        $tgl_deadline = new DateTime($deadline);
        $tgl_sekarang = new DateTime($hari_ini);
        $selisih = $tgl_sekarang->diff($tgl_deadline);
        $total_denda = $selisih->days * 10000; // 10rb per hari
    }

    $fileBukti = $this->request->getFile('bukti_bayar');
    $namaFile = null;

    if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
        $namaFile = $fileBukti->getRandomName();
        $fileBukti->move('img/bukti_bayar/', $namaFile);
    }

    $this->peminjamanModel->update($id, [
        'status'          => 'proses_kembali',
        'denda'           => $total_denda, // Denda otomatis masuk sini
        'metode_bayar'    => $this->request->getPost('metode_bayar'),
        'bukti_bayar'     => $namaFile,
        'tanggal_kembali' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/peminjaman')->with('success', 'Laporan berhasil dikirim!');
}
    // Fungsi Admin untuk Verifikasi Foto & Terima Buku
    public function konfirmasi_selesai($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $dataPinjam = $this->peminjamanModel->find($id);
        
        // Tambahkan kembali stok buku
        $buku = $this->bukuModel->find($dataPinjam['id_buku']);
        $this->bukuModel->update($dataPinjam['id_buku'], [
            'stok' => $buku['stok'] + 1
        ]);

        // Update status menjadi dikembalikan (Selesai)
        $this->peminjamanModel->update($id, [
        'status' => 'dikembalikan',
        'tanggal_kembali' => date('Y-m-d H:i:s') // Pastikan baris ini ada
    ]);

        return redirect()->to('/peminjaman')->with('success', 'Buku telah diterima dan stok telah diperbarui.');
    }

    // Opsional: Masih saya sisakan jika kamu butuh fitur permohonan/konfirmasi manual di masa depan
    public function konfirmasi($id, $aksi)
    {
        if (session()->get('role') != 'admin') return redirect()->back();

        if ($aksi == 'setuju') {
            $this->peminjamanModel->update($id, [
                'status' => 'dipinjam',
                'tanggal_pinjam' => date('Y-m-d H:i:s'),
            ]);
            $pesan = 'Peminjaman disetujui.';
        } else {
            $this->peminjamanModel->update($id, ['status' => 'ditolak']);
            $pesan = 'Pengajuan ditolak.';
        }

        return redirect()->to('/peminjaman')->with('success', $pesan);
    }
}