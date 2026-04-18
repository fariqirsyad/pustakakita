<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;

class Buku extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->bukuModel = new BukuModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $kategori = $this->request->getGet('kategori');

        // Inisialisasi model
        $builder = $this->bukuModel;

        // Logika Pencarian: jika ada keyword, cari berdasarkan judul
        if ($keyword) {
            $builder = $builder->like('judul', $keyword);
        }

        // Logika Kategori: jika ada kategori, filter berdasarkan kategori
        if ($kategori) {
            $builder = $builder->where('kategori', $kategori);
        }

        $data = [
            'title' => 'Katalog Buku',
            // Ambil hasil akhir setelah filter search/kategori
            'buku'  => $builder->findAll(),
            'kategori_aktif' => $kategori,
            'keyword' => $keyword
        ];

        return view('buku/index', $data);
    }

   public function ajukan($id_buku)
{
    $buku = $this->bukuModel->find($id_buku);

    if ($buku['stok'] > 0) {
        // Ambil input durasi dari form
        $durasiInput = $this->request->getPost('durasi_pinjam');

        $this->peminjamanModel->save([
            'id_user'           => session()->get('id_user'),
            'id_buku'           => $id_buku,
            'durasi'            => $durasiInput, // Simpan pesan durasi di sini
            'tanggal_pengajuan' => date('Y-m-d H:i:s'),
            'status'            => 'pending' 
        ]);

        $this->bukuModel->update($id_buku, [
            'stok' => $buku['stok'] - 1
        ]);

        return redirect()->to('/buku')->with('success', 'Berhasil mengajukan pinjaman.');
    } else {
        return redirect()->back()->with('error', 'Maaf, stok buku sudah habis!');
    }
}

    public function tambah()
    {
        $data = [
            'title' => 'Tambah Koleksi Buku Baru'
        ];
        return view('buku/tambah', $data);
    }

    public function simpan()
    {
        // Baris dd() dihapus agar proses simpan ke database tidak terhenti
        $fileCover = $this->request->getFile('cover');

        if ($fileCover && $fileCover->getError() == 4) {
            $namaCover = 'default.jpg';
        } else {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('img', $namaCover);
        }

        $this->bukuModel->save([
            'judul'        => $this->request->getPost('judul'),
            'penulis'      => $this->request->getPost('penulis'),
            'penerbit'     => $this->request->getPost('penerbit'), 
            'isbn'         => $this->request->getPost('isbn'),     
            'tahun_terbit' => $this->request->getPost('tahun_terbit'), 
            'ukuran_buku'  => $this->request->getPost('ukuran_buku'),   
            'halaman'      => $this->request->getPost('halaman'),     
            'kategori'     => $this->request->getPost('kategori'),
            'stok'         => $this->request->getPost('stok'),
            'cover'        => $namaCover
        ]);

        return redirect()->to('/buku')->with('success', 'Buku berhasil disimpan.');
    }

    public function pinjam($id_buku)
    {
        $buku = $this->bukuModel->find($id_buku);

        // Cek apakah stok masih ada
        if ($buku['stok'] > 0) {
            // Simpan ke tabel peminjaman
            $this->peminjamanModel->save([
                'id_user' => session('id_user'),
                'id_buku' => $id_buku,
                'tanggal_pinjam' => date('Y-m-d H:i:s'),
                'status' => 'dipinjam'
            ]);

            // Kurangi stok buku
            $this->bukuModel->update($id_buku, ['stok' => $buku['stok'] - 1]);

            return redirect()->to('/buku')->with('success', 'Buku berhasil dipinjam!');
        } else {
            return redirect()->to('/buku')->with('error', 'Maaf, stok buku habis.');
        }
    }
}