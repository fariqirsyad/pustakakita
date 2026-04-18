<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h4 class="fw-bold mb-4">Daftar Transaksi Peminjaman</h4>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Siswa</th>
                            <th>Judul Buku</th>
                            <th>Durasi Rencana</th> <th>Tgl Pinjam</th> 
                            <th>Tgl Kembali</th> 
                            <th>Status</th>
                            <th>Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($peminjaman as $p) : ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td><?= $p['nama']; ?></td>
                                <td><?= $p['judul']; ?></td>
                                
                                <td>
                                    <span class="badge bg-info text-dark fw-normal">
                                        <?= esc($p['durasi'] ?? '-'); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= ($p['tanggal_pinjam'] && $p['tanggal_pinjam'] != '0000-00-00 00:00:00') 
                                        ? date('d/m/Y', strtotime($p['tanggal_pinjam'])) 
                                        : '<span class="text-muted small">-</span>'; ?>
                                </td>

                                <td>
                                    <?= ($p['tanggal_kembali'] && $p['tanggal_kembali'] != '0000-00-00 00:00:00') 
                                        ? date('d/m/Y', strtotime($p['tanggal_kembali'])) 
                                        : '<span class="text-muted small">-</span>'; ?>
                                </td>

                                <td>
                                    <?php 
                                        $st = strtolower($p['status'] ?? ''); 
                                        $badge = ($st == 'dipinjam') ? 'bg-primary' : (($st == 'pending' || $st == '') ? 'bg-warning text-dark' : 'bg-success');
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= ucfirst($st ?: 'Pending'); ?></span>
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
                                        <?php if ($st == 'pending' || $st == '') : ?>
                                            <a href="<?= base_url('peminjaman/konfirmasi/'.$p['id_pinjam'].'/setuju') ?>" class="btn btn-sm btn-success">Setuju</a>
                                            <a href="<?= base_url('peminjaman/konfirmasi/'.$p['id_pinjam'].'/tolak') ?>" class="btn btn-sm btn-danger">Tolak</a>
                                        <?php elseif ($st == 'dipinjam') : ?>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalKembali<?= $p['id_pinjam'] ?>">
                                                Proses Kembali
                                            </button>

                                            <div class="modal fade" id="modalKembali<?= $p['id_pinjam'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                                    <div class="modal-content text-start">
                                                        <form action="<?= base_url('peminjaman/proses_kembali/'.$p['id_pinjam']) ?>" method="post">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Input Denda</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label class="small mb-1">Jumlah Denda (Rp)</label>
                                                                <input type="number" name="denda" class="form-control" value="0" min="0">
                                                                <p class="text-muted small mt-2">Isi 0 jika tidak ada denda.</p>
                                                            </div>
                                                            <div class="modal-footer p-2">
                                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-sm btn-primary">Simpan & Kembali</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php else : ?>
                                            <span class="text-muted small">Selesai</span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <small class="text-muted">No Action</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>