
<?php
require APPPATH . 'views/templates/header.php';
require APPPATH . 'views/templates/sidebar.php';

$e = static function ($value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};

$old = $old ?? [];
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Tambah Penyewa</h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= site_url('admin') ?>">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="<?= site_url('admin/penyewa') ?>">
                    Manajemen Penyewa
                </a>
            </li>

            <li class="active">Tambah Penyewa</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">
                    Form Tambah Data Penyewa
                </h3>
            </div>

            <form
                action="<?= site_url('admin/penyewa/tambah') ?>"
                method="POST"
                enctype="multipart/form-data"
            >

                <div class="box-body">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert"
                            >
                                &times;
                            </button>

                            <?= $e($error) ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Username</label>

                        <input
                            type="text"
                            name="user_plg"
                            class="form-control"
                            value="<?= $e($old['user_plg'] ?? '') ?>"
                            maxlength="30"
                            required
                        >

                        <small class="text-muted">
                            Username 3 sampai 30 karakter.
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Password</label>

                        <input
                            type="password"
                            name="sandi"
                            class="form-control"
                            minlength="8"
                            maxlength="72"
                            required
                        >

                        <small class="text-muted">
                            Password minimal 8 karakter.
                        </small>
                    </div>

                    <div class="form-group">
                        <label>NIK</label>

                        <input
                            type="text"
                            name="nik"
                            class="form-control"
                            value="<?= $e($old['nik'] ?? '') ?>"
                            maxlength="16"
                            minlength="16"
                            pattern="[0-9]{16}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>

                        <input
                            type="text"
                            name="nm_lngkp"
                            class="form-control"
                            value="<?= $e($old['nm_lngkp'] ?? '') ?>"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>

                        <input
                            type="text"
                            name="no_hp"
                            class="form-control"
                            value="<?= $e($old['no_hp'] ?? '') ?>"
                            maxlength="20"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Alamat Lengkap</label>

                        <textarea
                            name="almt_lngkp"
                            class="form-control"
                            rows="3"
                            maxlength="300"
                            required
                        ><?= $e($old['almt_lngkp'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto KTP</label>

                        <input
                            type="file"
                            name="ft_ktp"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                        >

                        <small class="text-muted">
                            Format JPG, PNG, atau WEBP. Maksimal 5 MB.
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Foto SIM A</label>

                        <input
                            type="file"
                            name="ft_sim_a"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                        >

                        <small class="text-muted">
                            Format JPG, PNG, atau WEBP. Maksimal 5 MB.
                        </small>
                    </div>

                </div>

                <div class="box-footer">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fa fa-save"></i>
                        Simpan Data
                    </button>

                    <a
                        href="<?= site_url('admin/penyewa') ?>"
                        class="btn btn-default"
                    >
                        <i class="fa fa-arrow-left"></i>
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </section>

</div>
