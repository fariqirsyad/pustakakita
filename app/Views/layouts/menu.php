<div class="d-flex flex-column h-100 p-3 bg-white border-end shadow-sm">
    <div class="mb-5 px-3">
        <a class="navbar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>" style="text-decoration: none;">
            <div class="p-2 bg-primary rounded-3 me-2">
                <i class="bi bi-book-half text-white fs-5"></i>
            </div>
            <div>
                <span class="fw-bold fs-5 text-dark" style="letter-spacing: -0.5px;">PustakaKita</span>
            </div>
        </a>
    </div>

    <ul class="nav nav-pills flex-column mb-auto px-2">
        <li class="nav-item">
            <small class="text-uppercase text-muted fw-bold px-3 mb-2 d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Menu Utama</small>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center py-2 px-3 <?= (uri_string() == 'dashboard' || uri_string() == '/') ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-speedometer2 me-3 fs-5"></i> <span class="fw-semibold">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <small class="text-uppercase text-muted fw-bold px-3 mt-4 mb-2 d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Manajemen</small>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center py-2 px-3 <?= (strpos(uri_string(), 'buku') !== false) ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('/buku') ?>">
                <i class="bi bi-collection me-3 fs-5"></i> <span class="fw-semibold">Katalog Buku</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center py-2 px-3 <?= (strpos(uri_string(), 'peminjaman') !== false) ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('/peminjaman') ?>">
                <i class="bi bi-arrow-left-right me-3 fs-5"></i> <span class="fw-semibold">Peminjaman</span>
            </a>
        </li>

        <?php if (session()->get('role') == 'user') : ?>
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center py-2 px-3 <?= (uri_string() == 'favorite') ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('favorite') ?>">
                    <i class="bi bi-bookmark-star-fill me-3 fs-5"></i> <span class="fw-semibold">Buku Favorite</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (session()->get('role') == 'admin') : ?>
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center py-2 px-3 <?= (url_is('laporan*')) ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('laporan') ?>">
                    <i class="bi bi-file-earmark-bar-graph me-3 fs-5"></i> <span class="fw-semibold">Laporan</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center py-2 px-3 <?= (strpos(uri_string(), 'users') !== false && strpos(uri_string(), 'edit') === false) ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('/users') ?>">
                    <i class="bi bi-person-badge me-3 fs-5"></i> <span class="fw-semibold">Daftar Anggota</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <small class="text-uppercase text-muted fw-bold px-3 mt-4 mb-2 d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Sistem</small>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link d-flex align-items-center py-2 px-3 <?= (strpos(uri_string(), 'users/edit') !== false) ? 'active shadow-sm text-white' : 'text-secondary' ?>" href="<?= base_url('users/edit/' . session('id_user')) ?>">
                <i class="bi bi-sliders me-3 fs-5"></i> <span class="fw-semibold">Pengaturan</span>
            </a>
        </li>

        <?php if (session()->get('role') == 'admin') : ?>
            <li class="nav-item mt-2">
                <a href="<?= base_url('/backup') ?>" class="nav-link text-success small fw-bold">
                    <i class="bi bi-database-down me-2"></i> Backup Database
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="dropup border-top pt-3">
        <button class="btn d-flex align-items-center p-2 rounded-3 bg-light w-100 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="position-relative">
                <?php $foto = session()->get('foto') ?: 'default.jpg'; ?>
                <img src="<?= base_url('uploads/users/' . $foto) ?>" width="40" height="40" class="rounded-circle shadow-sm" style="object-fit: cover;" alt="User Profile" />
                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white border-2 rounded-circle"></span>
            </div>
            <div class="ms-3 text-start overflow-hidden text-truncate">
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.85rem;"><?= session('nama'); ?></h6>
                <span class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem;"><?= session('role'); ?></span>
            </div>
            <i class="bi bi-three-dots-vertical ms-auto text-muted"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mb-2" style="border-radius: 12px; min-width: 180px;">
            <li><a class="dropdown-item py-2 small" href="<?= base_url('users/edit/' . session('id_user')) ?>"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item py-2 small text-danger fw-bold" href="<?= base_url('/logout') ?>" onclick="return confirm('Yakin ingin menyudahi sesi baca kamu?')">
                    <i class="bi bi-door-open me-2"></i> Keluar Sesi
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    /* CSS untuk merapikan Navigasi */
    .nav-pills .nav-link {
        border-radius: 12px;
        transition: all 0.3s ease;
        font-size: 0.92rem;
    }

    .nav-pills .nav-link:not(.active):hover {
        background-color: #f1f5f9;
        color: #0d6efd !important;
        transform: translateX(4px);
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
    }

    /* Hilangkan panah default dropdown */
    .dropup .btn::after {
        display: none;
    }

    /* Efek hover pada box profile */
    .dropup .btn:hover {
        background-color: #e2e8f0 !important;
    }
</style>