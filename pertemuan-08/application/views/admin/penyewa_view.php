<?php
require APPPATH . 'views/templates/header.php';
require APPPATH . 'views/templates/sidebar.php';


$e = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Manajemen Penyewa</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('admin') ?>">Dashboard</a></li>
            <li class="active">Manajemen Penyewa</li>
        </ol>
    </section>

    <section class="content">

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= $e($flash['type']) ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button>
                <?= $e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Penyewa</h3>

                <div class="box-tools">
                    <a href="<?= site_url('admin/penyewa/tambah') ?>"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Penyewa
                    </a>
                </div>
            </div>

            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Foto KTP</th>
                            <th>Foto SIM A</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($penyewa)): ?>
                            <?php $no = 1; ?>

                            <?php foreach ($penyewa as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $e($row['user_plg']) ?></td>
                                    <td><?= $e($row['nik']) ?></td>
                                    <td><?= $e($row['nm_lngkp']) ?></td>
                                    <td><?= $e($row['no_hp']) ?></td>
                                    <td><?= $e($row['almt_lngkp']) ?></td>

                                    <td>
                                        <?php if (!empty($row['ft_ktp'])): ?>
                                            <a href="<?= base_url('uploads/penyewa/' . rawurlencode($row['ft_ktp'])) ?>"
                                               target="_blank">
                                                Lihat KTP
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($row['ft_sim_a'])): ?>
                                            <a href="<?= base_url('uploads/penyewa/' . rawurlencode($row['ft_sim_a'])) ?>"
                                               target="_blank">
                                                Lihat SIM A
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="<?= site_url('admin/penyewa/ubah/' . rawurlencode($row['user_plg'])) ?>"
                                           class="btn btn-warning btn-xs">
                                            <i class="fa fa-edit"></i> Ubah
                                        </a>

                                        <form action="<?= site_url('admin/penyewa/hapus/' . rawurlencode($row['user_plg'])) ?>"
                                              method="POST"
                                              style="display:inline;"
                                              onsubmit="return confirm('Yakin ingin menghapus data penyewa ini?');">

                                            <button type="submit"
                                                    class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">
                                    Belum ada data penyewa.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>