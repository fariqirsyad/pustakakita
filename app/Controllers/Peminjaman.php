<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UsersModel;

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
        $role = session()->get('role');
        $id_user = session()->get('id_user');

        // Memperbaiki JOIN agar mengarah ke tabel 'users' sesuai database kamu
        $builder = $this->peminjamanModel
            ->select('peminjaman.*, users.nama, buku.judul')
            ->join('users', 'users.id_user = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku');

        // Filter jika yang login bukan admin
        if ($role != 'admin') {
            $builder->where('peminjaman.id_user', $id_user);
        }

        $data = [
            'title'      => 'Daftar Transaksi Peminjaman',
            'peminjaman' => $builder->orderBy('peminjaman.id_pinjam', 'DESC')->findAll()
        ];

        return view('peminjaman/index', $data);
    }

    public function simpan()
    {
        $id_buku = $this->request->getPost('id_buku');
        $buku = $this->bukuModel->find($id_buku);

        if ($buku && $buku['stok'] > 0) {
            $this->peminjamanModel->save([
                'id_user'        => session()->get('id_user'),
                'id_buku'        => $id_buku,
                'tanggal_pinjam' => date('Y-m-d H:i:s'),
                'durasi'         => $this->request->getPost('durasi_pinjam'),
                'status'         => 'dipinjam'
            ]);

            $this->bukuModel->update($id_buku, ['stok' => $buku['stok'] - 1]);

            return redirect()->to('/peminjaman')->with('success', 'Buku berhasil dipinjam!');
        }
        return redirect()->back()->with('error', 'Maaf, stok buku sedang habis!');
    }

    // USER: Lapor kembali & upload bukti (image_884b9b.png)
    // --- USER MENGIRIM BUKTI ---
public function user_kembali($id) 
{
    $file = $this->request->getFile('bukti_bayar');
    $denda = $this->request->getPost('denda');
    $newName = null;

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move('img/bukti_bayar', $newName);
    }

    $this->peminjamanModel->update($id, [
        'status'        => 'proses_kembali',
        'bukti_bayar'   => $newName,
        'metode_bayar'  => $this->request->getPost('metode_bayar'),
        'denda'         => $denda,
        // JANGAN isi tanggal_kembali di sini agar di tabel tetap muncul "Belum Kembali"
    ]);

    return redirect()->back()->with('success', 'Bukti berhasil diunggah. Menunggu verifikasi admin.');
}

    // ADMIN: Tombol Konfirmasi Selesai
    public function konfirmasi_selesai($id)
    {
        if (session()->get('role') != 'admin') return redirect()->back();

        $peminjaman = $this->peminjamanModel->find($id);

        if ($peminjaman) {
            // 1. Set status selesai
            $this->peminjamanModel->update($id, [
                'status'          => 'dikembalikan',
                'tanggal_kembali' => date('Y-m-d H:i:s')
            ]);

            // 2. Kembalikan stok buku
            $buku = $this->bukuModel->find($peminjaman['id_buku']);
            if ($buku) {
                $this->bukuModel->update($peminjaman['id_buku'], [
                    'stok' => $buku['stok'] + 1
                ]);
            }

            return redirect()->back()->with('success', 'Data peminjaman berhasil diselesaikan.');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
}