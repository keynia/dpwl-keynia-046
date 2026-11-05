
<?php
require APPPATH . 'views/templates/header.php';
require APPPATH . 'views/templates/sidebar.php';

$e = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$penyewa = $penyewa ?? [];
$old = $old ?? [];

$userPlg = $penyewa['user_plg'] ?? '';
$nik = $old['nik'] ?? $penyewa['nik'] ?? '';
$nama = $old['nm_lngkp'] ?? $penyewa['nm_lngkp'] ?? '';
$noHp = $old['no_hp'] ?? $penyewa['no_hp'] ?? '';
$alamat = $old['almt_lngkp'] ?? $penyewa['almt_lngkp'] ?? '';
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Ubah Data Penyewa</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= site_url('admin') ?>">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= site_url('admin/penyewa') ?>">Manajemen Data Penyewa</a>
                </li>
                <li class="breadcrumb-item active">Ubah Data Penyewa</li>
            </ol>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Form Ubah Data Penyewa</h3>
                </div>

                <form
                    action="<?= site_url('admin/penyewa/ubah/' . rawurlencode($userPlg)) ?>"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <div class="card-body">

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <?= $e($error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Username Penyewa</label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= $e($userPlg) ?>"
                                readonly
                            >
                            <small class="text-muted">
                                Username tidak dapat diubah.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input
                                type="text"
                                name="nik"
                                id="nik"
                                class="form-control"
                                value="<?= $e($nik) ?>"
                                maxlength="16"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="nm_lngkp">Nama Lengkap</label>
                            <input
                                type="text"
                                name="nm_lngkp"
                                id="nm_lngkp"
                                class="form-control"
                                value="<?= $e($nama) ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input
                                type="text"
                                name="no_hp"
                                id="no_hp"
                                class="form-control"
                                value="<?= $e($noHp) ?>"
                                maxlength="15"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="almt_lngkp">Alamat Lengkap</label>
                            <textarea
                                name="almt_lngkp"
                                id="almt_lngkp"
                                class="form-control"
                                rows="3"
                                required
                            ><?= $e($alamat) ?></textarea>
                        </div>

                        <hr>

                        <h5>Foto KTP</h5>

                        <?php if (!empty($penyewa['ft_ktp'])): ?>
                            <div class="mb-2">
                                <p>Foto KTP saat ini:</p>
                                <a
                                    href="<?= base_url('uploads/penyewa/' . rawurlencode($penyewa['ft_ktp'])) ?>"
                                    target="_blank"
                                >
                                    Lihat Foto KTP
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Belum ada foto KTP.</p>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="ft_ktp">Ganti Foto KTP</label>
                            <input
                                type="file"
                                name="ft_ktp"
                                id="ft_ktp"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >
                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti foto KTP.
                            </small>
                        </div>

                        <hr>

                        <h5>Foto SIM A</h5>

                        <?php if (!empty($penyewa['ft_sim_a'])): ?>
                            <div class="mb-2">
                                <p>Foto SIM A saat ini:</p>
                                <a
                                    href="<?= base_url('uploads/penyewa/' . rawurlencode($penyewa['ft_sim_a'])) ?>"
                                    target="_blank"
                                >
                                    Lihat Foto SIM A
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Belum ada foto SIM A.</p>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="ft_sim_a">Ganti Foto SIM A</label>
                            <input
                                type="file"
                                name="ft_sim_a"
                                id="ft_sim_a"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >
                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti foto SIM A.
                            </small>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>

                        <a
                            href="<?= site_url('admin/penyewa') ?>"
                            class="btn btn-secondary"
                        >
                            Kembali
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </section>

</div>

<?php require APPPATH . 'views/templates/footer.php'; ?>
