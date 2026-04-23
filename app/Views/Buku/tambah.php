<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">Form Tambah Buku</h5>
                </div>
                <div class="card-body p-4">
                   <form action="<?= base_url('buku/simpan') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required placeholder="Masukkan judul lengkap">
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required placeholder="Nama pengarang">
                        </div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-bold">Penerbit</label>
        <input type="text" name="penerbit" class="form-control" placeholder="Nama Penerbit">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-bold">ISBN</label>
        <input type="text" name="isbn" class="form-control" placeholder="Contoh: 978-602-...">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label small fw-bold">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" class="form-control" placeholder="2024">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label small fw-bold">Ukuran Buku</label>
        <input type="text" name="ukuran_buku" class="form-control" placeholder="14x20 cm">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label small fw-bold">Halaman</label>
        <input type="number" name="halaman" class="form-control" placeholder="250">
    </div>
</div>
                        <div class="row text-start">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Kategori</label>
                                <select name="kategori" class="form-select">
                                    <option value="Sains">Sains</option>
                                    <option value="Matematika">Matematika</option>
                                    <option value="Sejarah">Sejarah</option>
                                    <option value="Sastra">Sastra</option>
                                    <option value="Sastra">Islam</option>
                                    <option value="Sastra">Teknologi</option>
                                    <option value="Sastra">Biologi</option>
                                    <option value="Sastra">Kimia</option>
                                    <option value="Sastra">Hukum</option>

                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Jumlah Stok</label>
                                <input type="number" name="stok" class="form-control" required min="1" value="1">
                            </div>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold">Cover Buku</label>
                            <input type="file" name="cover" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">Simpan Buku</button>
                            <a href="<?= base_url('buku') ?>" class="btn btn-light px-4 border">Batal</a>
                        </div>
                    </form> </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>