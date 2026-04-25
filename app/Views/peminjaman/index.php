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
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td><?= esc($p['nama']); ?></td>
                                <td><?= esc($p['judul']); ?></td>
                                <td>
                                    <span class="badge bg-info text-dark fw-normal">
                                        <?= esc($p['durasi'] ?? '-'); ?> Hari
                                    </span>
                                </td>
                                <td>
                                    <?= ($p['tanggal_pinjam'] && $p['tanggal_pinjam'] != '0000-00-00 00:00:00') 
                                        ? date('d/m/Y', strtotime($p['tanggal_pinjam'])) : '-'; ?>
                                </td>
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
                                <td>
                                    <?php if ($st == 'dikembalikan' && !empty($p['tanggal_kembali']) && $p['tanggal_kembali'] != '0000-00-00 00:00:00') : ?>
                                        <?= date('d/m/Y', strtotime($p['tanggal_kembali'])); ?>
                                    <?php else : ?>
                                        <span class="text-muted small">Belum Kembali</span>
                                    <?php endif; ?>
                                </td>
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
                                        <?php elseif ($st == 'dikembalikan') : ?>
                                            <span class="text-success small fw-bold"><i class="bi bi-check-all"></i> Terverifikasi</span>
                                        <?php else : ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>

                                    <?php else : ?>
                                        <?php if ($st == 'dipinjam') : ?>
                                            <button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUserKembali<?= $id_data ?>">
                                                <i class="bi bi-arrow-left-right me-1"></i> Kembalikan
                                            </button>
                                        <?php elseif ($st == 'proses_kembali') : ?>
                                            <small class="text-muted italic">Menunggu Verifikasi</small>
                                        <?php else : ?>
                                            <i class="bi bi-check2-all text-success fs-5"></i>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php if (session()->get('role') == 'admin' && $st == 'proses_kembali') : ?>
                            <div class="modal fade" id="modalAdminCek<?= $id_data ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-primary text-white">
                                            <h6 class="modal-title fw-bold text-white">Verifikasi Pembayaran & Buku</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center p-4">
                                            <div class="row mb-3 bg-light py-2 mx-0 rounded border text-start">
                                                <div class="col-6">
                                                    <small class="text-muted">Metode Bayar:</small><br>
                                                    <strong><?= esc($p['metode_bayar'] ?? 'Transfer'); ?></strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Nominal Denda:</small><br>
                                                    <strong class="text-danger">Rp <?= number_format($p['denda'] ?? 0, 0, ',', '.') ?></strong>
                                                </div>
                                            </div>
                                            
                                            <label class="small fw-bold d-block mb-2">Foto Bukti Transfer dari Siswa:</label>
                                            <?php if (!empty($p['bukti_bayar'])) : ?>
                                                <img src="<?= base_url('img/bukti_bayar/'.$p['bukti_bayar']) ?>" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 350px;">
                                            <?php else : ?>
                                                <div class="alert alert-warning py-3">
                                                    <i class="bi bi-exclamation-triangle d-block fs-4"></i>
                                                    <small>Siswa tidak melampirkan foto bukti.</small>
                                                </div>
                                            <?php endif; ?>
                                            <p class="text-muted small">Pastikan saldo sudah masuk sebelum melakukan konfirmasi.</p>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4">
                                            <a href="<?= base_url('peminjaman/konfirmasi_selesai/'.$id_data) ?>" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                                <i class="bi bi-check-circle me-1"></i> Konfirmasi Denda Telah Terbayar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="modal fade" id="modalUserKembali<?= $id_data ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="<?= base_url('peminjaman/user_kembali/'.$id_data) ?>" method="post" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Kembalikan Buku</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php 
                                                    $deadline = date('Y-m-d', strtotime($p['tanggal_pinjam']. ' + ' . $p['durasi'] . ' days'));
                                                    $hari_ini = date('Y-m-d');
                                                    $denda = 0;
                                                    if ($hari_ini > $deadline) {
                                                        $tgl_deadline = new DateTime($deadline);
                                                        $tgl_sekarang = new DateTime($hari_ini);
                                                        $selisih = $tgl_sekarang->diff($tgl_deadline);
                                                        $denda = $selisih->days * 10000;
                                                    }
                                                ?>
                                                <div class="alert alert-danger text-center mb-3">
                                                    <small class="d-block text-muted">Denda yang harus dibayar:</small>
                                                    <strong class="h5">Rp <?= number_format($denda, 0, ',', '.') ?></strong>
                                                    <input type="hidden" name="denda" value="<?= $denda ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold">Metode Pembayaran</label>
                                                    <select name="metode_bayar" class="form-select" required>
                                                        <option value="Transfer Bank">Transfer Bank</option>
                                                        <option value="E-Wallet">E-Wallet (Dana/Gopay)</option>
                                                        <option value="Tunai">Tunai ke Petugas</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold">Upload Bukti (Foto)</label>
                                                    <input type="file" name="bukti_bayar" class="form-control" accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Bukti Pembayaran</button>
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
<?= $this->endSection() ?>