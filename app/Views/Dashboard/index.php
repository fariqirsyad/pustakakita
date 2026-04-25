<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php 
    date_default_timezone_set('Asia/Jakarta'); 
?>

<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
            <p class="text-muted small">Selamat Datang, <strong><?= session()->get('nama') ?></strong>. Berikut ringkasan perpustakaan Anda.</p>
        </div>
        <div class="col-auto">
            <div class="bg-white px-3 py-2 rounded-3 shadow-sm border small fw-bold text-primary">
                <i class="bi bi-calendar3 me-2"></i> <?= date('d F Y') ?> | <i class="bi bi-clock me-1"></i> <?= date('H:i') ?> WIB
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">TOTAL KOLEKSI</small>
                        <h2 class="fw-bold mb-0"><?= $total_buku ?? '0' ?></h2> <span class="text-success small"><i class="bi bi-check-circle"></i> Tersedia di Rak</span>
                    </div>
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-book text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">SEDANG DIPINJAM</small>
                        <h2 class="fw-bold mb-0"><?= $total_dipinjam ?? '0' ?></h2> <a href="<?= base_url('peminjaman') ?>" class="text-warning small text-decoration-none">Kelola Peminjaman &rarr;</a>
                    </div>
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-journal-bookmark-fill text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">SISWA AKTIF</small>
                        <h2 class="fw-bold mb-0"><?= $total_siswa ?? '0' ?></h2> <span class="text-muted small">Anggota terdaftar sistem</span>
                    </div>
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-people-fill text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="<?= (session()->get('role') == 'admin') ? 'col-lg-8' : 'col-lg-12' ?>">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">⌛ Batas Waktu Peminjaman</h5>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Monitor Realtime</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted" style="font-size: 0.85rem;">
                                    <th class="ps-4 py-3 fw-bold">Siswa</th>
                                    <th class="py-3 fw-bold">Judul Buku</th>
                                    <th class="py-3 fw-bold">Batas Waktu</th>
                                    <th class="py-3 text-center fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($aktivitas)) : ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="bi bi-check2-circle text-success fs-1"></i>
                                            <p class="text-muted mt-2 mb-0">Tidak ada peminjaman aktif saat ini.</p>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($aktivitas as $row) : ?>
                                        <tr class="border-bottom">
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark"><?= $row['nama'] ?></div>
                                                <small class="text-muted"><?= $row['kelas'] ?></small>
                                            </td>
                                            <td><span class="fw-medium text-truncate d-inline-block" style="max-width: 200px;"><?= $row['judul'] ?></span></td>
                                            <td>
                                                <?php 
                                                    $durasi_hari = intval($row['durasi']); 
                                                    $waktu_pinjam = strtotime($row['tanggal_pinjam']);
                                                    $batas_waktu = $waktu_pinjam + ($durasi_hari * 24 * 3600); 
                                                    $sekarang = time();
                                                    $selisih_detik = $batas_waktu - $sekarang;

                                                    $sisa_jam = floor($selisih_detik / 3600);
                                                    $sisa_menit = floor(($selisih_detik % 3600) / 60);
                                                ?>
                                                
                                                <div class="fw-bold <?= ($selisih_detik < 0) ? 'text-danger' : 'text-primary' ?>">
                                                    <i class="bi bi-clock-history me-1"></i> <?= date('d M, H:i', $batas_waktu) ?> WIB
                                                </div>
                                                
                                                <?php if ($selisih_detik < 0) : ?>
                                                    <small class="badge bg-danger">Terlambat!</small>
                                                <?php elseif ($selisih_detik <= (5 * 3600)) : ?>
                                                    <small class="badge bg-warning text-dark">
                                                        ⚠️ <?= $sisa_jam ?>j <?= $sisa_menit ?>m lagi
                                                    </small>
                                                <?php else : ?>
                                                    <small class="badge bg-info bg-opacity-10 text-info">
                                                        Sisa <?= ceil($selisih_detik / 86400) ?> hari lagi
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php if (session()->get('role') == 'admin') : ?>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-2 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Menu Cepat</h5>
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('buku/tambah') ?>" class="btn btn-dark p-3 rounded-4 border-0 shadow-sm text-start d-flex align-items-center">
                            <i class="bi bi-journal-plus fs-4 me-3"></i>
                            <div>
                                <div class="fw-bold">Tambah Buku</div>
                                <small class="opacity-75">Update stok koleksi</small>
                            </div>
                        </a>

                        <a href="<?= base_url('laporan') ?>" class="btn btn-light border p-3 rounded-4 text-start d-flex align-items-center">
                            <i class="bi bi-printer-fill fs-4 me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold text-dark">Cetak Laporan</div>
                                <small class="text-muted">Rekap bulanan/tahunan</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .rounded-4 { border-radius: 1rem !important; }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.045)!important; }
    .table thead th { background-color: #fcfcfc; border-top: none; }
    .btn-primary { background-color: #1a73e8; }
    .text-primary { color: #1a73e8 !important; }
</style>
<?= $this->endSection() ?>