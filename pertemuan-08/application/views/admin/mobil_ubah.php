
<?php
require APPPATH . '/views/templates/header.php';
require APPPATH . '/views/templates/sidebar.php';

$mobil = $mobil ?? [];
$error = $error ?? null;
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Ubah Kendaraan
            <small>Edit data kendaraan</small>
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
            <li class="active">Ubah Kendaraan</li>
        </ol>
    </section>

    <section class="content">

        <?php if ($error !== null): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="box box-warning">

            <div class="box-header with-border">
                <h3 class="box-title">Form Ubah Kendaraan</h3>
            </div>

            <form method="post"
                action="<?= site_url('admin/mobil/ubah/' . rawurlencode($mobil['no_plat'])) ?>"
                enctype="multipart/form-data">

                <div class="box-body">

                    <div class="form-group">
                        <label>Nomor Plat</label>
                        <input type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($mobil['no_plat'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Mobil</label>
                        <input type="text" name="nm_mobil"
                            class="form-control"
                            value="<?= htmlspecialchars($mobil['nm_mobil'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Kursi</label>
                        <input type="number" name="nkursi"
                            class="form-control"
                            min="1" max="25"
                            value="<?= htmlspecialchars((string)($mobil['nkursi'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bagasi</label>
                        <input type="number" name="nbagasi"
                            class="form-control"
                            min="0" max="30"
                            value="<?= htmlspecialchars((string)($mobil['nbagasi'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Mobil</label>
                        <select name="jns_mobil"
                            class="form-control" required>
                            <option value="">-- Pilih Jenis Mobil --</option>

                            <?php foreach (['SUV', 'MPV', 'City Car'] as $jenis): ?>
                                <option value="<?= $jenis ?>"
                                    <?= ($mobil['jns_mobil'] ?? '') === $jenis ? 'selected' : '' ?>>
                                    <?= $jenis ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Transmisi</label>
                        <select name="transmisi"
                            class="form-control" required>
                            <option value="">-- Pilih Transmisi --</option>

                            <option value="AT"
                                <?= ($mobil['transmisi'] ?? '') === 'AT' ? 'selected' : '' ?>>
                                Automatic (AT)
                            </option>

                            <option value="MT"
                                <?= ($mobil['transmisi'] ?? '') === 'MT' ? 'selected' : '' ?>>
                                Manual (MT)
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tahun Pembuatan</label>
                        <input type="number" name="thn_buat"
                            class="form-control"
                            value="<?= htmlspecialchars((string)($mobil['thn_buat'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Harga Sewa per Hari</label>
                        <input type="number" name="hrg_hari"
                            class="form-control"
                            min="1"
                            value="<?= htmlspecialchars((string)($mobil['hrg_hari'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Catatan Unit</label>
                        <textarea name="cttn_unit"
                            class="form-control"
                            rows="3"><?= htmlspecialchars($mobil['cttn_unit'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status Kendaraan</label>
                        <select name="status_unit"
                            class="form-control" required>

                            <?php foreach ([
                                'tersedia' => 'Tersedia',
                                'disewa' => 'Disewa',
                                'tidaktersedia' => 'Tidak Tersedia'
                            ] as $value => $label): ?>

                                <option value="<?= $value ?>"
                                    <?= ($mobil['status_unit'] ?? '') === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <hr>

                    <h4>Foto Kendaraan</h4>
                    <p class="text-muted">
                        Kosongkan jika tidak ingin mengganti foto.
                    </p>

                    <?php
                    $fotoFields = [
                        'ft_depan' => 'Foto Depan',
                        'ft_blkg' => 'Foto Belakang',
                        'ft_interior' => 'Foto Interior'
                    ];
                    ?>

                    <?php foreach ($fotoFields as $field => $label): ?>
                        <div class="form-group">
                            <label><?= $label ?></label>

                            <?php if (!empty($mobil[$field])): ?>
                                <div>
                                    <img src="<?= htmlspecialchars(
                                        base_url('assets/uploads/mobil/' . rawurlencode($mobil[$field])),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                        alt="<?= $label ?>"
                                        style="max-width:180px; max-height:120px; margin-bottom:10px;">
                                </div>
                            <?php endif; ?>

                            <input type="file"
                                name="<?= $field ?>"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp">
                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="box-footer">

                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save"></i> Simpan Perubahan
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
