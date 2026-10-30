
<?php
require APPPATH . '/views/templates/header.php';
require APPPATH . '/views/templates/sidebar.php';

$flash = $flash ?? null;
$error = $error ?? null;
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Tambah Kendaraan
            <small>Masukkan data kendaraan baru</small>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= site_url('admin') ?>">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin/mobil') ?>">
                    Manajemen Kendaraan
                </a>
            </li>
            <li class="active">Tambah Kendaraan</li>
        </ol>
    </section>

    <section class="content">

        <?php if ($error !== null): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Form Tambah Kendaraan</h3>
            </div>

            <form method="post"
                action="<?= site_url('admin/mobil/tambah') ?>"
                enctype="multipart/form-data">

                <div class="box-body">

                    <div class="form-group">
                        <label>Nomor Plat</label>
                        <input type="text" name="no_plat"
                            class="form-control"
                            placeholder="Contoh: B 1173 HFF"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Nama Mobil</label>
                        <input type="text" name="nm_mobil"
                            class="form-control"
                            placeholder="Masukkan nama mobil"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Kursi</label>
                        <input type="number" name="nkursi"
                            class="form-control"
                            min="1" max="25" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bagasi</label>
                        <input type="number" name="nbagasi"
                            class="form-control"
                            min="0" max="30" required>
                    </div>

                    <div class="form-group">
    <label>Jenis Mobil</label>
    <select name="jns_mobil" class="form-control" required>
        <option value="">-- Pilih Jenis Mobil --</option>
        <option value="SUV">SUV</option>
        <option value="MPV">MPV</option>
        <option value="City Car">City Car</option>
    </select>
</div>

                    <div class="form-group">
                        <label>Transmisi</label>
                        <select name="transmisi"
                            class="form-control" required>
                            <option value="">-- Pilih Transmisi --</option>
                            <option value="AT">Automatic (AT)</option>
                            <option value="MT">Manual (MT)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tahun Pembuatan</label>
                        <input type="number" name="thn_buat"
                            class="form-control"
                            placeholder="Contoh: 2025"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Harga Sewa per Hari</label>
                        <input type="number" name="hrg_hari"
                            class="form-control"
                            min="1"
                            placeholder="Contoh: 700000"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Catatan Unit</label>
                        <textarea name="cttn_unit"
                            class="form-control"
                            rows="3"
                            placeholder="Masukkan catatan kendaraan"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status Kendaraan</label>
                        <select name="status_unit"
                            class="form-control" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="disewa">Disewa</option>
                            <option value="tidaktersedia">Tidak Tersedia</option>
                        </select>
                    </div>

                    <hr>

                    <h4>Foto Kendaraan</h4>

                    <div class="form-group">
                        <label>Foto Depan</label>
                        <input type="file" name="ft_depan"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp">
                    </div>

                    <div class="form-group">
                        <label>Foto Belakang</label>
                        <input type="file" name="ft_blkg"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp">
                    </div>

                    <div class="form-group">
                        <label>Foto Interior</label>
                        <input type="file" name="ft_interior"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp">
                    </div>

                </div>

                <div class="box-footer">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>

                    <a href="<?= site_url('admin/mobil') ?>"
                        class="btn btn-default">
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </section>
</div>

<?php
require APPPATH . '/views/templates/footer.php';
?>
