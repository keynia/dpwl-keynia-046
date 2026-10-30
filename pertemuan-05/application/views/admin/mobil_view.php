
<?php
require APPPATH . '/views/templates/header.php';
require APPPATH . '/views/templates/sidebar.php';

$mobil = $mobil ?? [];
$flash = $flash ?? null;
$flashClass = (($flash['type'] ?? '') === 'success')
    ? 'alert-success'
    : 'alert-danger';
$error = $error ?? null;
?>

<!-- Content Wrapper -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Manajemen Kendaraan
            <small>Kelola data kendaraan</small>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= htmlspecialchars(site_url('admin'), ENT_QUOTES, 'UTF-8') ?>">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>
            <li class="active">Manajemen Kendaraan</li>
        </ol>
    </section>

    <section class="content">

        <!-- Flash Message -->
        <?php if (!empty($flash)): ?>
            <div class="alert <?= $flashClass ?> alert-dismissible">
                <button type="button" class="close"
                    data-dismiss="alert" aria-hidden="true">&times;</button>

                <i class="fa <?= (($flash['type'] ?? '') === 'success')
                    ? 'fa-check'
                    : 'fa-warning' ?>"></i>

                <?= htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <!-- Data Table -->
        <div class="box">

            <div class="box-header">
                <h3 class="box-title">Data Kendaraan</h3>

                <div class="box-tools">
                    <a href="<?= htmlspecialchars(site_url('admin/mobil/tambah'), ENT_QUOTES, 'UTF-8') ?>"
                        class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Kendaraan
                    </a>
                </div>
            </div>

            <div class="box-body">

                <div class="table-responsive">

                    <table id="mobilTable"
                        class="table table-bordered table-striped js-data-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Plat</th>
                                <th>Nama Mobil</th>
                                <th>Kursi</th>
                                <th>Bagasi</th>
                                <th>Jenis Mobil</th>
                                <th>Transmisi</th>
                                <th>Tahun</th>
                                <th>Harga/Hari</th>
                                <th>Status</th>
                                <th>Foto Depan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($mobil as $no => $item): ?>

                                <tr>
                                    <td><?= (int) $no + 1 ?></td>

                                    <td>
                                        <?= htmlspecialchars($item['no_plat'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['nm_mobil'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td><?= (int) ($item['nkursi'] ?? 0) ?></td>

                                    <td><?= (int) ($item['nbagasi'] ?? 0) ?></td>

                                    <td>
                                        <?= htmlspecialchars($item['jns_mobil'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['transmisi'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['thn_buat'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format((int) ($item['hrg_hari'] ?? 0), 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?php
                                        $status = $item['status_unit'] ?? '';

                                        $statusClass = match ($status) {
                                            'tersedia' => 'label-success',
                                            'disewa' => 'label-warning',
                                            'tidaktersedia' => 'label-danger',
                                            default => 'label-default',
                                        };
                                        ?>

                                        <span class="label <?= $statusClass ?>">
                                            <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>

                                    
<td>
    <?php if (!empty($item['ft_depan'])): ?>

        <img
            src="<?= htmlspecialchars(
                base_url('assets/uploads/mobil/' . rawurlencode($item['ft_depan'])),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            alt="Foto depan"
            style="width: 80px; height: 55px; object-fit: cover;">

    <?php else: ?>

        <span class="text-muted">Belum ada foto</span>

    <?php endif; ?>
</td>


                                    <td>
                                        <a class="btn btn-warning btn-xs"
                                            href="<?= htmlspecialchars(
                                                site_url('admin/mobil/ubah/' .
                                                rawurlencode((string) $item['no_plat'])),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>">
                                            <i class="fa fa-edit"></i> Ubah
                                        </a>

                                        <form method="post"
                                            style="display:inline"
                                            action="<?= htmlspecialchars(
                                                site_url('admin/mobil/hapus/' .
                                                rawurlencode((string) $item['no_plat'])),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">

                                            <button type="submit" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>

                                        </form>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>No. Plat</th>
                                <th>Nama Mobil</th>
                                <th>Kursi</th>
                                <th>Bagasi</th>
                                <th>Jenis Mobil</th>
                                <th>Transmisi</th>
                                <th>Tahun</th>
                                <th>Harga/Hari</th>
                                <th>Status</th>
                                <th>Foto Depan</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>

        <!-- Pesan Error -->
        <?php if ($error !== null): ?>

            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close"
                    data-dismiss="alert" aria-hidden="true">&times;</button>

                <i class="fa fa-warning"></i>

                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>

        <?php endif; ?>

    </section>
</div>

<?php
require APPPATH . '/views/templates/footer.php';
?>
