<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h4 class="fw-bold mb-4">Daftar Transaksi Peminjaman</h4>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Siswa</th>
                            <th>Judul Buku</th>
                            <th>Durasi</th> 
                            <th>Tgl Pinjam</th> 
                            <th>Status</th>
                            <th>Tgl Kembali</th> 
                            <th>Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($peminjaman as $p) : ?>
                            <?php 
                                $id_data = $p['id_pinjam']; 
                                $st = strtolower(trim($p['status'] ?? '')); 
                                
                                // Perhitungan denda real-time untuk User
                                $deadline = date('Y-m-d', strtotime($p['tanggal_pinjam']. ' + ' . $p['durasi'] . ' days'));
                                $hari_ini = date('Y-m-d');
                                $denda_hitung = 0;
                                if ($hari_ini > $deadline && $st == 'dipinjam') {
                                    $tgl_deadline = new DateTime($deadline);
                                    $tgl_sekarang = new DateTime($hari_ini);
                                    $selisih = $tgl_sekarang->diff($tgl_deadline);
                                    $denda_hitung = $selisih->days * 10000;
                                }
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td><?= esc($p['nama']); ?></td>
                                <td><?= esc($p['judul']); ?></td>
                                <td><span class="badge bg-info text-dark fw-normal"><?= esc($p['durasi'] ?? '-'); ?> Hari</span></td>
                                <td><?= ($p['tanggal_pinjam'] && $p['tanggal_pinjam'] != '0000-00-00 00:00:00') ? date('d/m/Y', strtotime($p['tanggal_pinjam'])) : '-'; ?></td>
                                <td>
                                    <?php if ($st == 'dipinjam') : ?>
                                        <span class="badge bg-primary">Sedang Dipinjam</span>
                                    <?php elseif ($st == 'proses_kembali') : ?>
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    <?php elseif ($st == 'dikembalikan') : ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary"><?= ucfirst($st); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= ($st == 'dikembalikan' && !empty($p['tanggal_kembali'])) ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '<span class="text-muted small">Belum Kembali</span>'; ?></td>
                                <td>
                                    <?php if(isset($p['denda']) && $p['denda'] > 0) : ?>
                                        <span class="text-danger fw-bold">Rp <?= number_format((int)$p['denda'], 0, ',', '.'); ?></span>
                                    <?php else : ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (session()->get('role') == 'admin') : ?>
                                        <?php if ($st == 'proses_kembali') : ?>
                                            <button type="button" class="btn btn-sm btn-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAdminCek<?= $id_data ?>">
                                                <i class="bi bi-eye-fill me-1"></i> Cek Bukti
                                            </button>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if ($st == 'dipinjam') : ?>
                                            <button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUserKembali<?= $id_data ?>">
                                                <i class="bi bi-arrow-left-right me-1"></i> Kembalikan
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUserKembali<?= $id_data ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="<?= base_url('peminjaman/user_kembali/'.$id_data) ?>" method="post" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Form Pengembalian Buku</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="denda" value="<?= $denda_hitung ?>">
                                                
                                                <?php if ($denda_hitung > 0) : ?>
                                                    <div class="alert alert-danger text-center mb-3">
                                                        <small class="d-block text-muted">Keterlambatan Terdeteksi!</small>
                                                        <strong class="h5">Denda: Rp <?= number_format($denda_hitung, 0, ',', '.') ?></strong>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="small fw-bold">Pilih Metode Pembayaran Denda:</label>
                                                        <select name="metode_bayar" class="form-select" onchange="updateView(<?= $id_data ?>, this.value)" required>
                                                            <option value="">-- Pilih Metode --</option>
                                                            <option value="Tunai">Bayar di Tempat (Pustakawan)</option>
                                                            <option value="Transfer">Transfer Online (E-Wallet/Bank)</option>
                                                        </select>
                                                    </div>

                                                    <div id="paymentInstructions<?= $id_data ?>" class="mt-3"></div>

                                                <?php else : ?>
                                                    <div class="alert alert-success text-center mb-3">
                                                        <strong>Anda Tepat Waktu!</strong>
                                                        <small class="d-block text-muted mt-1">Silakan unggah foto buku saat dikembalikan.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold">Upload Foto Dokumentasi Buku</label>
                                                        <input type="file" name="bukti_transaksi" class="form-control" accept="image/*" required>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Laporan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateView(id, value) {
    const container = document.getElementById('paymentInstructions' + id);
    if (value === 'Tunai') {
        container.innerHTML = `
            <div class="alert alert-info small shadow-sm">
                <i class="bi bi-info-circle-fill me-1"></i> <strong>Instruksi:</strong> Silakan bayar ke petugas dan ambil foto dokumentasi <strong>bersama buku di depan petugas/meja pustakawan</strong>.
            </div>
            <div class="mb-3">
                <label class="small fw-bold">Upload Foto Dokumentasi (Di Tempat)</label>
                <input type="file" name="bukti_transaksi" class="form-control" accept="image/*" required>
            </div>
        `;
    } else if (value === 'Transfer') {
        container.innerHTML = `
            <div class="bg-light p-3 rounded border mb-3">
                <h6 class="fw-bold small mb-3 text-center">Transfer E-Wallet & Bank</h6>
                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <a href="https://link.dana.id/send-money/08123456789" target="_blank" class="btn btn-sm btn-primary w-100 fw-bold py-2">DANA</a>
                    </div>
                    
                </div>
                <div class="bg-white border rounded p-2 mb-1 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">BCA:</span> <span class="small fw-bold">123-456-7890</span>
                </div>
                <div class="bg-white border rounded p-2 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">BRI:</span> <span class="small fw-bold">0011-01-234567-89-0</span>
                </div>
                <small class="text-center d-block mt-2 text-muted" style="font-size: 0.7rem;">A/N Admin PustakaKita</small>
            </div>
            <div class="mb-3">
                <label class="small fw-bold">Upload Bukti Transfer / Screenshot</label>
                <input type="file" name="bukti_transaksi" class="form-control" accept="image/*" required>
            </div>
        `;
    } else {
        container.innerHTML = '';
    }
}
</script>

<?= $this->endSection() ?>