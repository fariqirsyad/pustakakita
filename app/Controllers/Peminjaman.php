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

        $builder = $this->peminjamanModel
            ->select('peminjaman.*, users.nama, buku.judul')
            ->join('users', 'users.id_user = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku');

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
    $id_user = session()->get('id_user');
    $id_buku = $this->request->getPost('id_buku');
    $buku = $this->bukuModel->find($id_buku);

    // --- LOGIKA REVISI PENGUJI START ---

    // 1. Cek apakah user punya denda yang status_bayar-nya 'belum'
    // Kita pake $this->peminjamanModel biar sinkron sama model lu
    $dendaBelumLunas = $this->peminjamanModel->where([
        'id_user'      => $id_user,
        'status_bayar' => 'belum'
    ])->first();

    if ($dendaBelumLunas) {
        return redirect()->back()->with('error', 'Gak bisa pinjam, Bro! Lu masih punya denda yang belum dibayar.');
    }

    // 2. Cek limit maksimal 3 buku (diajukan + dipinjam)
    $jumlahDipinjam = $this->peminjamanModel->where('id_user', $id_user)
        ->whereIn('status', ['diajukan', 'dipinjam'])
        ->countAllResults();

    if ($jumlahDipinjam >= 3) {
        return redirect()->back()->with('error', 'Limit penuh! Maksimal cuma boleh pegang 3 buku.');
    }

    // --- LOGIKA REVISI PENGUJI END ---

    // 3. Cek Stok & Proses Simpan
    if ($buku && $buku['stok'] > 0) {
        $this->peminjamanModel->save([
            'id_user'        => $id_user,
            'id_buku'        => $id_buku,
            'tanggal_pinjam' => date('Y-m-d H:i:s'),
            'status'         => 'diajukan', // REVISI: Status awal harus 'diajukan'
            'status_bayar'   => 'n/a'       // Default karena belum ada denda
        ]);

        /* CATATAN: 
           PENGURANG STOK DIHAPUS DARI SINI. 
           Sesuai skenario penguji, stok berkurang PAS ADMIN klik "Serahkan Buku".
        */

        return redirect()->to('/peminjaman')->with('success', 'Peminjaman berhasil diajukan! Menunggu konfirmasi admin.');
    }

    return redirect()->back()->with('error', 'Maaf, stok buku sedang habis!');
}

    // --- LOGIKA BARU: PENGEMBALIAN BERDASARKAN DENDA ---
    public function user_kembali($id) 
    {
        $file = $this->request->getFile('bukti_transaksi'); // Nama input file disamakan
        $denda = (int) $this->request->getPost('denda');
        $newName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Folder penyimpanan tetap rapi
            $folder = ($denda > 0) ? 'img/bukti_bayar' : 'img/dokumentasi';
            $file->move($folder, $newName);
        }

        $dataUpdate = [
            'status'        => 'proses_kembali',
            'bukti_bayar'   => $newName, // Tetap masuk ke kolom bukti_bayar di DB
            'denda'         => $denda,
        ];

        // Jika tidak ada denda, simpan keterangan sebagai dokumentasi fisik
        if ($denda <= 0) {
            $dataUpdate['metode_bayar'] = 'Penyerahan Fisik';
        } else {
            $dataUpdate['metode_bayar'] = $this->request->getPost('metode_bayar');
        }

        $this->peminjamanModel->update($id, $dataUpdate);

        $pesan = ($denda > 0) ? 'Bukti transfer denda berhasil diunggah.' : 'Foto dokumentasi pengembalian berhasil diunggah.';
        return redirect()->back()->with('success', $pesan . ' Menunggu verifikasi admin.');
    }

    public function konfirmasi_selesai($id)
    {
        if (session()->get('role') != 'admin') return redirect()->back();

        $peminjaman = $this->peminjamanModel->find($id);

        if ($peminjaman) {
            $this->peminjamanModel->update($id, [
                'status'          => 'dikembalikan',
                'tanggal_kembali' => date('Y-m-d H:i:s')
            ]);

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