
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
        require_admin_login();

        $data['mobil'] = $this->model->getAll();
        $data['username'] = $_SESSION['admin_username'] ?? '';

        $this->view('admin/mobil_view', $data);
    }

    // Menampilkan dan memproses tambah kendaraan
    public function tambah()
    {
        require_admin_login();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadedFiles = [];

            try {
                $data = [
                    'no_plat' => trim($_POST['no_plat'] ?? ''),
                    'nm_mobil' => trim($_POST['nm_mobil'] ?? ''),
                    'nkursi' => filter_var(
                        $_POST['nkursi'] ?? null,
                        FILTER_VALIDATE_INT
                    ),
                    'nbagasi' => filter_var(
                        $_POST['nbagasi'] ?? null,
                        FILTER_VALIDATE_INT
                    ),
                    'jns_mobil' => $_POST['jns_mobil'] ?? '',
                    'transmisi' => $_POST['transmisi'] ?? '',
                    'thn_buat' => filter_var(
                        $_POST['thn_buat'] ?? null,
                        FILTER_VALIDATE_INT
                    ),
                    'ft_depan' => null,
                    'ft_blkg' => null,
                    'ft_interior' => null,
                    'hrg_hari' => filter_var(
                        $_POST['hrg_hari'] ?? null,
                        FILTER_VALIDATE_INT
                    ),
                    'cttn_unit' => trim($_POST['cttn_unit'] ?? ''),
                    'status_unit' => $_POST['status_unit'] ?? ''
                ];

                $error = $this->validateMobilInput($data);

                if ($error !== null) {
                    throw new RuntimeException($error);
                }

                // Upload foto depan
                $data['ft_depan'] = $this->uploadFoto('ft_depan');
                if ($data['ft_depan'] !== null) {
                    $uploadedFiles[] = $data['ft_depan'];
                }

                // Upload foto belakang
                $data['ft_blkg'] = $this->uploadFoto('ft_blkg');
                if ($data['ft_blkg'] !== null) {
                    $uploadedFiles[] = $data['ft_blkg'];
                }

                // Upload foto interior
                $data['ft_interior'] = $this->uploadFoto('ft_interior');
                if ($data['ft_interior'] !== null) {
                    $uploadedFiles[] = $data['ft_interior'];
                }

                // Simpan data ke database
                if (!$this->model->create($data)) {
                    throw new RuntimeException(
                        'Data mobil gagal disimpan.'
                    );
                }

                set_flash_message(
                    'success',
                    'Data mobil berhasil ditambahkan.'
                );

                header('Location: ' . site_url('admin/mobil'));
                exit;

            } catch (Throwable $e) {
                $this->hapusFotoBaru($uploadedFiles);

                $dataView = [
                    'username' => $_SESSION['admin_username'] ?? '',
                    'error' => $e->getMessage()
                ];

                $this->view('admin/mobil_tambah', $dataView);
                return;
            }
        }

        $data = [
            'username' => $_SESSION['admin_username'] ?? '',
            'error' => null
        ];

        $this->view('admin/mobil_tambah', $data);
    }

    // Menampilkan dan memproses ubah kendaraan
    public function ubah(string $noPlat): void
    {
        require_admin_login();

        $noPlat = rawurldecode($noPlat);

        // Mencari data mobil berdasarkan nomor plat
        $mobilLama = $this->model->findByNoPlat($noPlat);

        if ($mobilLama === null) {
            set_flash_message(
                'danger',
                'Data kendaraan tidak ditemukan.'
            );

            header('Location: ' . site_url('admin/mobil'));
            exit;
        }

        // Jika halaman dibuka dengan GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderMobil('ubah', $mobilLama);
            return;
        }

        // Jika bukan GET atau POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode request tidak diizinkan.');
        }

        // Mengambil data dari formulir
        $data = [
            'no_plat' => $noPlat,
            'nm_mobil' => trim($_POST['nm_mobil'] ?? ''),
            'nkursi' => filter_var(
                $_POST['nkursi'] ?? null,
                FILTER_VALIDATE_INT
            ),
            'nbagasi' => filter_var(
                $_POST['nbagasi'] ?? null,
                FILTER_VALIDATE_INT
            ),
            'jns_mobil' => $_POST['jns_mobil'] ?? '',
            'transmisi' => $_POST['transmisi'] ?? '',
            'thn_buat' => filter_var(
                $_POST['thn_buat'] ?? null,
                FILTER_VALIDATE_INT
            ),

            // Foto lama dipertahankan jika tidak diganti
            'ft_depan' => $mobilLama['ft_depan'],
            'ft_blkg' => $mobilLama['ft_blkg'],
            'ft_interior' => $mobilLama['ft_interior'],

            'hrg_hari' => filter_var(
                $_POST['hrg_hari'] ?? null,
                FILTER_VALIDATE_INT
            ),
            'cttn_unit' => trim($_POST['cttn_unit'] ?? ''),
            'status_unit' => $_POST['status_unit'] ?? ''
        ];

        // Validasi data
        $error = $this->validateMobilInput($data);

        if ($error !== null) {
            $this->renderMobil(
                'ubah',
                array_merge($mobilLama, $data),
                $error
            );
            return;
        }

        $uploadedFiles = [];
        $fotoLamaUntukDihapus = [];

        try {
            // Memproses foto baru jika dipilih
            foreach (
                ['ft_depan', 'ft_blkg', 'ft_interior'] as $field
            ) {
                $filename = $this->uploadFoto($field);

                if ($filename !== null) {
                    $uploadedFiles[] = $filename;

                    if (!empty($mobilLama[$field])) {
                        $fotoLamaUntukDihapus[] =
                            $mobilLama[$field];
                    }

                    $data[$field] = $filename;
                }
            }

            // Memperbarui data di database
            $success = $this->model->update($noPlat, $data);

            if (!$success) {
                throw new RuntimeException(
                    'Data kendaraan gagal diperbarui.'
                );
            }

        } catch (Throwable $e) {
            // Hapus foto baru jika proses gagal
            $this->hapusFotoBaru($uploadedFiles);

            $this->renderMobil(
                'ubah',
                array_merge($mobilLama, $data),
                $e->getMessage()
            );
            return;
        }

        // Hapus foto lama setelah data berhasil diperbarui
        $this->hapusFotoBaru($fotoLamaUntukDihapus);

        set_flash_message(
            'success',
            'Data kendaraan berhasil diperbarui.'
        );

        header('Location: ' . site_url('admin/mobil'));
        exit;
    }

    // Validasi data kendaraan
    private function validateMobilInput(array $data): ?string
    {
        if (
            empty($data['no_plat']) ||
            empty($data['nm_mobil']) ||
            $data['nkursi'] === false ||
            $data['nkursi'] === null ||
            $data['nbagasi'] === false ||
            $data['nbagasi'] === null ||
            $data['thn_buat'] === false ||
            $data['thn_buat'] === null ||
            $data['hrg_hari'] === false ||
            $data['hrg_hari'] === null
        ) {
            return 'Semua data wajib harus diisi dengan benar.';
        }

        if (strlen($data['no_plat']) > 15) {
            return 'Nomor plat maksimal 15 karakter.';
        }

        if (strlen($data['nm_mobil']) > 40) {
            return 'Nama mobil maksimal 40 karakter.';
        }

        if ($data['nkursi'] < 1 || $data['nkursi'] > 25) {
            return 'Jumlah kursi harus antara 1 sampai 25.';
        }

        if ($data['nbagasi'] < 0 || $data['nbagasi'] > 30) {
            return 'Jumlah bagasi harus antara 0 sampai 30.';
        }

        if (!in_array(
            $data['jns_mobil'],
            ['SUV', 'MPV', 'City Car'],
            true
        )) {
            return 'Jenis mobil tidak valid.';
        }

        if (!in_array(
            $data['transmisi'],
            ['AT', 'MT'],
            true
        )) {
            return 'Transmisi tidak valid.';
        }

        if ($data['thn_buat'] < 1901 || $data['thn_buat'] > 2155) {
            return 'Tahun pembuatan tidak valid.';
        }

        if ($data['hrg_hari'] < 1 || $data['hrg_hari'] > 8388607) {
            return 'Harga sewa tidak valid.';
        }

        if (strlen($data['cttn_unit']) > 500) {
            return 'Catatan maksimal 500 karakter.';
        }

        if (!in_array(
            $data['status_unit'],
            ['tersedia', 'disewa', 'tidaktersedia'],
            true
        )) {
            return 'Status kendaraan tidak valid.';
        }

        return null;
    }

    // Menampilkan halaman tambah atau ubah
    private function renderMobil(
        string $aksi,
        array $mobil,
        ?string $error = null
    ): void {
        $dataView = [
            'mobil' => $mobil,
            'username' => $_SESSION['admin_username'] ?? '',
            'error' => $error
        ];

        $this->view('admin/mobil_' . $aksi, $dataView);
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
            'image/png' => 'png',
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

    // Menghapus foto yang tidak lagi digunakan
    private function hapusFotoBaru(array $files): void
    {
        foreach ($files as $filename) {
            $path = $this->uploadDir . basename($filename);

            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function hapus(string $noPlat): void
{
require_admin_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
http_response_code(405);
exit('Metode request tidak diizinkan.');
}
$noPlat = rawurldecode($noPlat);
$mobil = $this->model->findByNoPlat($noPlat);
if ($mobil === null) {
set_flash_message('danger', 'Data kendaraan tidak ditemukan.');
header('Location: ' . site_url('admin/mobil'));
exit;
}
try {
$success = $this->model->delete($noPlat);
} catch (Throwable $e) {
$success = false;
}
if (!$success) {
set_flash_message('danger', 'Data kendaraan gagal dihapus.');
header('Location: ' . site_url('admin/mobil'));
exit;
}
$this->hapusFotoBaru(array_filter([
$mobil['ft_depan'] ?? null,
$mobil['ft_blkg'] ?? null,
$mobil['ft_interior'] ?? null,
]));
set_flash_message('success', 'Data kendaraan berhasil dihapus.');
header('Location: ' . site_url('admin/mobil'));
exit;
}

}
