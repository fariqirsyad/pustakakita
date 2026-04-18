<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id_pinjam';
    protected $allowedFields = ['id_buku', 'id_user', 'durasi', 'status', 'tanggal_pinjam', 'tanggal_kembali', 'denda'];
}