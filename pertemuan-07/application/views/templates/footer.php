
<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?= htmlspecialchars(
    $adminlte_url . 'bower_components/jquery/dist/jquery.min.js',
    ENT_QUOTES,
    'UTF-8'
) ?>"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?= htmlspecialchars(
    $adminlte_url . 'bower_components/bootstrap/dist/js/bootstrap.min.js',
    ENT_QUOTES,
    'UTF-8'
) ?>"></script>

<!-- DataTables -->
<script src="<?= htmlspecialchars(
    $adminlte_url . 'bower_components/datatables.net/js/jquery.dataTables.min.js',
    ENT_QUOTES,
    'UTF-8'
) ?>"></script>

<!-- DataTables Bootstrap -->
<script src="<?= htmlspecialchars(
    $adminlte_url . 'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ENT_QUOTES,
    'UTF-8'
) ?>"></script>

<!-- AdminLTE App -->
<script src="<?= htmlspecialchars(
    $adminlte_url . 'dist/js/adminlte.min.js',
    ENT_QUOTES,
    'UTF-8'
) ?>"></script>

<!-- Inisialisasi DataTables -->
<script>
$(function() {
    $('.js-data-table').DataTable({

        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,

        // Urutan awal berdasarkan kolom No
        order: [[0, 'asc']],

        // Posisi Tampilkan di kiri dan Cari di kanan
        dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",

        columnDefs: [
            {
                orderable: false,
                targets: 'no-sort'
            }
        ],

        language: {
            lengthMenu: 'Tampilkan _MENU_ data',
            zeroRecords: 'Data tidak ditemukan',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(disaring dari _MAX_ data)',
            search: 'Cari:',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    });
});
</script>

</body>
</html>
