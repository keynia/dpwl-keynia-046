
<?php

class Mobil_controller extends Controller
{
    private Mobil_model $model;

    private string $uploadDir;

    public function __construct()
    {
        require_once APPPATH . 'models/Mobil_model.php';
        require_once APPPATH . 'helpers/auth_helper.php';
        require_once APPPATH . 'helpers/flash_helper.php';

        $this->model = new Mobil_model();

        $this->uploadDir = dirname(__DIR__, 2)
            . '/assets/uploads/mobil/';
    }

    // Menampilkan daftar kendaraan
    public function index()
    {
        $data['mobil'] = $this->model->getAll();
        $data['username'] = $_SESSION['admin_username'] ?? '';

        $this->view('admin/mobil_view', $data);
    }

    // Menampilkan halaman dan memproses tambah kendaraan
    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadedFiles = [];

            try {
                $data = [
                    'no_plat'       => trim($_POST['no_plat'] ?? ''),
                    'nm_mobil'      => trim($_POST['nm_mobil'] ?? ''),
                    'nkursi'        => $_POST['nkursi'] ?? '',
                    'nbagasi'       => $_POST['nbagasi'] ?? '',
                    'jns_mobil'     => $_POST['jns_mobil'] ?? '',
                    'transmisi'     => $_POST['transmisi'] ?? '',
                    'thn_buat'      => $_POST['thn_buat'] ?? '',
                    'hrg_hari'      => $_POST['hrg_hari'] ?? '',
                    'cttn_unit'     => trim($_POST['cttn_unit'] ?? ''),
                    'status_unit'   => $_POST['status_unit'] ?? '',
                    'ft_depan'      => null,
                    'ft_blkg'       => null,
                    'ft_interior'   => null
                ];

                // Validasi data wajib
                if (
                    $data['no_plat'] === '' ||
                    $data['nm_mobil'] === '' ||
                    $data['nkursi'] === '' ||
                    $data['nbagasi'] === '' ||
                    $data['thn_buat'] === '' ||
                    $data['hrg_hari'] === ''
                ) {
                    throw new RuntimeException(
                        'Semua data wajib harus diisi.'
                    );
                }

                if (strlen($data['no_plat']) > 15) {
                    throw new RuntimeException(
                        'Nomor plat maksimal 15 karakter.'
                    );
                }

                if (strlen($data['nm_mobil']) > 40) {
                    throw new RuntimeException(
                        'Nama mobil maksimal 40 karakter.'
                    );
                }

                if (
                    !filter_var($data['nkursi'], FILTER_VALIDATE_INT) ||
                    $data['nkursi'] < 1 ||
                    $data['nkursi'] > 25
                ) {
                    throw new RuntimeException(
                        'Jumlah kursi harus antara 1 sampai 25.'
                    );
                }

                if (
                    !filter_var($data['nbagasi'], FILTER_VALIDATE_INT) ||
                    $data['nbagasi'] < 0 ||
                    $data['nbagasi'] > 30
                ) {
                    throw new RuntimeException(
                        'Jumlah bagasi harus antara 0 sampai 30.'
                    );
                }

                // Validasi jenis mobil
                if (!in_array(
                    $data['jns_mobil'],
                    ['SUV', 'MPV', 'City Car'],
                    true
                )) {
                    throw new RuntimeException(
                        'Jenis mobil tidak valid.'
                    );
                }

                // Validasi transmisi
                if (!in_array(
                    $data['transmisi'],
                    ['AT', 'MT'],
                    true
                )) {
                    throw new RuntimeException(
                        'Transmisi tidak valid.'
                    );
                }

                // Validasi tahun pembuatan
                if (
                    !filter_var($data['thn_buat'], FILTER_VALIDATE_INT) ||
                    $data['thn_buat'] < 1901 ||
                    $data['thn_buat'] > 2155
                ) {
                    throw new RuntimeException(
                        'Tahun pembuatan tidak valid.'
                    );
                }

                // Validasi harga sewa
                if (
                    !filter_var($data['hrg_hari'], FILTER_VALIDATE_INT) ||
                    $data['hrg_hari'] < 1 ||
                    $data['hrg_hari'] > 8388607
                ) {
                    throw new RuntimeException(
                        'Harga sewa tidak valid.'
                    );
                }

                if (strlen($data['cttn_unit']) > 500) {
                    throw new RuntimeException(
                        'Catatan maksimal 500 karakter.'
                    );
                }

                // Validasi status kendaraan
                if (!in_array(
                    $data['status_unit'],
                    ['tersedia', 'disewa', 'tidaktersedia'],
                    true
                )) {
                    throw new RuntimeException(
                        'Status kendaraan tidak valid.'
                    );
                }

                // Upload foto kendaraan
                $data['ft_depan'] = $this->uploadFoto('ft_depan');
                if ($data['ft_depan'] !== null) {
                    $uploadedFiles[] = $data['ft_depan'];
                }

                $data['ft_blkg'] = $this->uploadFoto('ft_blkg');
                if ($data['ft_blkg'] !== null) {
                    $uploadedFiles[] = $data['ft_blkg'];
                }

                $data['ft_interior'] = $this->uploadFoto('ft_interior');
                if ($data['ft_interior'] !== null) {
                    $uploadedFiles[] = $data['ft_interior'];
                }

                // Simpan ke database
                if (!$this->model->create($data)) {
                    throw new RuntimeException(
                        'Data mobil gagal disimpan.'
                    );
                }

                // Berhasil, kembali ke daftar mobil
                header('Location: ' . site_url('admin/mobil'));
                exit;

            } catch (Throwable $e) {
                // Hapus foto jika penyimpanan gagal
                $this->hapusFotoBaru($uploadedFiles);

                $dataView['username'] =
                    $_SESSION['admin_username'] ?? '';

                $dataView['error'] = $e instanceof RuntimeException
                    ? $e->getMessage()
                    : 'Terjadi kesalahan saat menyimpan data. Pastikan nomor plat belum digunakan.';

                $this->view('admin/mobil_tambah', $dataView);
                return;
            }
        }

        // Jika GET, tampilkan form tambah mobil
        $data['username'] = $_SESSION['admin_username'] ?? '';
        $data['error'] = null;

        $this->view('admin/mobil_tambah', $data);
    }

    // Upload foto kendaraan
    private function uploadFoto(string $field): ?string
    {
        if (
            !isset($_FILES[$field]) ||
            $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file = $_FILES[$field];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                'Upload foto gagal. Periksa ukuran file.'
            );
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            throw new RuntimeException(
                'Ukuran foto maksimal 5 MB.'
            );
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($allowed[$mime])) {
            throw new RuntimeException(
                'Format foto harus JPG, PNG, atau WEBP.'
            );
        }

        if (!is_dir($this->uploadDir)) {
            throw new RuntimeException(
                'Folder upload mobil tidak ditemukan.'
            );
        }

        $filename = bin2hex(random_bytes(16))
            . '.' . $allowed[$mime];

        $destination = $this->uploadDir . $filename;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            throw new RuntimeException(
                'Foto gagal diunggah.'
            );
        }

        return $filename;
    }

    // Menghapus foto yang baru diunggah jika penyimpanan gagal
    private function hapusFotoBaru(array $files): void
    {
        foreach ($files as $filename) {
            $path = $this->uploadDir . basename($filename);

            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
