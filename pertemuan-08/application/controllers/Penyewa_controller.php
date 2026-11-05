
<?php

require_once APPPATH . 'models/Penyewa_model.php';
require_once APPPATH . 'models/Transaksi_model.php';

class Penyewa_controller extends Controller
{
    private Penyewa_model $model;
    private Transaksi_model $transaksiModel;

    public function __construct()
    {
        $this->model = new Penyewa_model();
        $this->transaksiModel = new Transaksi_model();
    }

    // =====================================================
    // ADMIN: MENAMPILKAN DATA PENYEWA
    // =====================================================

    public function index(): void
    {
        require_once APPPATH . 'helpers/auth_helper.php';
        require_once APPPATH . 'helpers/flash_helper.php';

        require_admin_login();

        try {
            $data['penyewa'] = $this->model->getAll();
            $data['username'] = $_SESSION['admin_username'] ?? '';
            $data['flash'] = get_flash_message();

            $this->view('admin/penyewa_view', $data);

        } catch (Throwable $e) {
            error_log('Pengambilan data penyewa gagal: ' . $e->getMessage());

            http_response_code(500);
            exit('Data penyewa belum dapat ditampilkan. Silakan coba kembali.');
        }
    }

    // =====================================================
    // ADMIN: TAMBAH DATA PENYEWA
    // =====================================================

    public function tambah(): void
    {
        require_once APPPATH . 'helpers/auth_helper.php';
        require_once APPPATH . 'helpers/flash_helper.php';

        require_admin_login();

        $data = [
            'username' => $_SESSION['admin_username'] ?? '',
            'error' => null,
            'old' => [
                'user_plg' => '',
                'nik' => '',
                'nm_lngkp' => '',
                'no_hp' => '',
                'almt_lngkp' => ''
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('admin/penyewa_tambah', $data);
            return;
        }

        $userPlg = trim((string) ($_POST['user_plg'] ?? ''));
        $password = (string) ($_POST['sandi'] ?? '');
        $nik = trim((string) ($_POST['nik'] ?? ''));
        $nama = trim((string) ($_POST['nm_lngkp'] ?? ''));
        $noHp = trim((string) ($_POST['no_hp'] ?? ''));
        $alamat = trim((string) ($_POST['almt_lngkp'] ?? ''));

        $data['old'] = [
            'user_plg' => $userPlg,
            'nik' => $nik,
            'nm_lngkp' => $nama,
            'no_hp' => $noHp,
            'almt_lngkp' => $alamat
        ];

        $fotoBaru = [];

        try {
            $error = $this->validateAdminPenyewaInput(
                $userPlg,
                $nik,
                $nama,
                $noHp,
                $alamat
            );

            if ($error !== null) {
                throw new RuntimeException($error);
            }

            if (strlen($password) < 8 || strlen($password) > 72) {
                throw new RuntimeException(
                    'Password harus terdiri dari 8 sampai 72 byte.'
                );
            }

            if ($this->model->findByUserPlg($userPlg) !== null) {
                throw new RuntimeException(
                    'Username sudah digunakan. Gunakan username lain.'
                );
            }

            // Foto KTP dan SIM A wajib diisi saat tambah.
            if (
                !isset($_FILES['ft_ktp']) ||
                $_FILES['ft_ktp']['error'] === UPLOAD_ERR_NO_FILE
            ) {
                throw new RuntimeException('Foto KTP wajib diunggah.');
            }

            if (
                !isset($_FILES['ft_sim_a']) ||
                $_FILES['ft_sim_a']['error'] === UPLOAD_ERR_NO_FILE
            ) {
                throw new RuntimeException('Foto SIM A wajib diunggah.');
            }

            $uploadKtp = $this->uploadFotoPenyewa('ft_ktp');

            if (!$uploadKtp['success']) {
                throw new RuntimeException($uploadKtp['error']);
            }

            $fotoKtp = $uploadKtp['filename'];
            $fotoBaru[] = $fotoKtp;

            $uploadSim = $this->uploadFotoPenyewa('ft_sim_a');

            if (!$uploadSim['success']) {
                throw new RuntimeException($uploadSim['error']);
            }

            $fotoSim = $uploadSim['filename'];
            $fotoBaru[] = $fotoSim;

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            if ($passwordHash === false) {
                throw new RuntimeException(
                    'Password gagal diproses.'
                );
            }

            $berhasil = $this->model->create([
                'user_plg' => $userPlg,
                'sandi' => $passwordHash,
                'nik' => $nik,
                'nm_lngkp' => $nama,
                'no_hp' => $noHp,
                'almt_lngkp' => $alamat,
                'ft_ktp' => $fotoKtp,
                'ft_sim_a' => $fotoSim
            ]);

            if (!$berhasil) {
                throw new RuntimeException(
                    'Data penyewa gagal disimpan.'
                );
            }

            $fotoBaru = [];

            set_flash_message(
                'success',
                'Data penyewa berhasil ditambahkan.'
            );

            header('Location: ' . site_url('admin/penyewa'));
            exit;

        } catch (Throwable $e) {
            $this->hapusFotoPenyewa($fotoBaru);

            error_log('Tambah penyewa gagal: ' . $e->getMessage());

            $data['error'] = $e->getMessage();
        }

        $this->view('admin/penyewa_tambah', $data);
    }

    // =====================================================
    // ADMIN: UBAH DATA PENYEWA
    // =====================================================

    public function ubah(string $userPlg = ''): void
    {
        require_once APPPATH . 'helpers/auth_helper.php';
        require_once APPPATH . 'helpers/flash_helper.php';

        require_admin_login();

        $userPlg = trim($userPlg);

        if ($userPlg === '') {
            http_response_code(400);
            exit('Username penyewa tidak valid.');
        }

        try {
            $penyewa = $this->model->getByUserPlg($userPlg);

            if ($penyewa === null) {
                http_response_code(404);
                exit('Data penyewa tidak ditemukan.');
            }

            $data = [
                'username' => $_SESSION['admin_username'] ?? '',
                'penyewa' => $penyewa,
                'error' => null,
                'old' => []
            ];

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->view('admin/penyewa_ubah', $data);
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                header('Allow: GET, POST');
                exit('Method Not Allowed');
            }

            $nik = trim((string) ($_POST['nik'] ?? ''));
            $nama = trim((string) ($_POST['nm_lngkp'] ?? ''));
            $noHp = trim((string) ($_POST['no_hp'] ?? ''));
            $alamat = trim((string) ($_POST['almt_lngkp'] ?? ''));

            $data['old'] = [
                'nik' => $nik,
                'nm_lngkp' => $nama,
                'no_hp' => $noHp,
                'almt_lngkp' => $alamat
            ];

            $error = $this->validateAdminPenyewaInput(
                $userPlg,
                $nik,
                $nama,
                $noHp,
                $alamat
            );

            if ($error !== null) {
                $data['error'] = $error;
                $this->view('admin/penyewa_ubah', $data);
                return;
            }

            $fotoBaru = [];

            $uploadKtp = $this->uploadFotoPenyewa('ft_ktp');

            if (!$uploadKtp['success']) {
                throw new RuntimeException($uploadKtp['error']);
            }

            if ($uploadKtp['filename'] !== null) {
                $fotoBaru[] = $uploadKtp['filename'];
            }

            $uploadSim = $this->uploadFotoPenyewa('ft_sim_a');

            if (!$uploadSim['success']) {
                $this->hapusFotoPenyewa($fotoBaru);
                throw new RuntimeException($uploadSim['error']);
            }

            if ($uploadSim['filename'] !== null) {
                $fotoBaru[] = $uploadSim['filename'];
            }

            $fotoKtp = $uploadKtp['filename']
                ?? ($penyewa['ft_ktp'] ?? null);

            $fotoSim = $uploadSim['filename']
                ?? ($penyewa['ft_sim_a'] ?? null);

            $berhasil = $this->model->update(
                $userPlg,
                [
                    'nik' => $nik,
                    'nm_lngkp' => $nama,
                    'no_hp' => $noHp,
                    'almt_lngkp' => $alamat,
                    'ft_ktp' => $fotoKtp,
                    'ft_sim_a' => $fotoSim
                ]
            );

            if (!$berhasil) {
                $this->hapusFotoPenyewa($fotoBaru);

                throw new RuntimeException(
                    'Data penyewa gagal diperbarui.'
                );
            }

            // Hapus foto lama setelah data berhasil diperbarui.
            $fotoLama = [];

            if (
                $uploadKtp['filename'] !== null &&
                !empty($penyewa['ft_ktp'])
            ) {
                $fotoLama[] = [
                    'ft_ktp',
                    $penyewa['ft_ktp']
                ];
            }

            if (
                $uploadSim['filename'] !== null &&
                !empty($penyewa['ft_sim_a'])
            ) {
                $fotoLama[] = [
                    'ft_sim_a',
                    $penyewa['ft_sim_a']
                ];
            }

            $this->hapusFotoPenyewa($fotoLama);

            set_flash_message(
                'success',
                'Data penyewa berhasil diperbarui.'
            );

            header('Location: ' . site_url('admin/penyewa'));
            exit;

        } catch (Throwable $e) {
            error_log('Ubah penyewa gagal: ' . $e->getMessage());

            $data['error'] = $e->getMessage();
            $this->view('admin/penyewa_ubah', $data);
        }
    }

    // =====================================================
    // ADMIN: HAPUS DATA PENYEWA
    // =====================================================

    public function hapus(string $userPlg = ''): void
    {
        require_once APPPATH . 'helpers/auth_helper.php';
        require_once APPPATH . 'helpers/flash_helper.php';

        require_admin_login();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Method Not Allowed');
        }

        $userPlg = trim($userPlg);

        try {
            $penyewa = $this->model->getByUserPlg($userPlg);

            if ($penyewa === null) {
                throw new RuntimeException(
                    'Data penyewa tidak ditemukan.'
                );
            }

            $berhasil = $this->model->delete($userPlg);

            if (!$berhasil) {
                throw new RuntimeException(
                    'Data penyewa gagal dihapus.'
                );
            }

            $fotoLama = [];

            if (!empty($penyewa['ft_ktp'])) {
                $fotoLama[] = [
                    'ft_ktp',
                    $penyewa['ft_ktp']
                ];
            }

            if (!empty($penyewa['ft_sim_a'])) {
                $fotoLama[] = [
                    'ft_sim_a',
                    $penyewa['ft_sim_a']
                ];
            }

            $this->hapusFotoPenyewa($fotoLama);

            set_flash_message(
                'success',
                'Data penyewa berhasil dihapus.'
            );

        } catch (Throwable $e) {
            error_log('Hapus penyewa gagal: ' . $e->getMessage());

            set_flash_message(
                'danger',
                'Data penyewa gagal dihapus. Pastikan tidak ada transaksi yang masih menggunakan data tersebut.'
            );
        }

        header('Location: ' . site_url('admin/penyewa'));
        exit;
    }

    // =====================================================
    // VALIDASI INPUT ADMIN
    // =====================================================

    private function validateAdminPenyewaInput(
        string $userPlg,
        string $nik,
        string $nama,
        string $noHp,
        string $alamat
    ): ?string {
        if (!preg_match('/\A[a-zA-Z0-9_.-]{3,30}\z/', $userPlg)) {
            return 'Username harus 3-30 karakter dan hanya berisi huruf, angka, titik, garis bawah, atau tanda hubung.';
        }

        if (!preg_match('/\A[0-9]{16}\z/', $nik)) {
            return 'NIK wajib terdiri dari tepat 16 digit angka.';
        }

        if (
            $nama === '' ||
            mb_strlen($nama) > 100 ||
            !preg_match(
                "/\A[\p{L}\p{M}]+(?:[ .'-][\p{L}\p{M}]+)*\z/u",
                $nama
            )
        ) {
            return 'Nama lengkap wajib diisi, maksimal 100 karakter, dan hanya berisi huruf serta pemisah nama yang wajar.';
        }

        if (
            $noHp === '' ||
            strlen($noHp) > 15 ||
            !preg_match('/\A[0-9+(). -]+\z/', $noHp) ||
            !preg_match('/[0-9]/', $noHp)
        ) {
            return 'Nomor HP wajib diisi, maksimal 15 karakter, dan hanya boleh berisi angka serta tanda +, titik, kurung, spasi, atau tanda hubung.';
        }

        if (
            $alamat === '' ||
            mb_strlen($alamat) > 300 ||
            preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $alamat)
        ) {
            return 'Alamat wajib diisi, maksimal 300 karakter, dan tidak boleh mengandung karakter kontrol.';
        }

        return null;
    }

    // =====================================================
    // PENYEWA: PROFIL
    // =====================================================

    public function profil(): void
    {
        if (empty($_SESSION['penyewa_user_plg'])) {
            $_SESSION['flash_penyewa_nav'] =
                'Silakan login terlebih dahulu untuk mengakses profil.';

            header('Location: ' . site_url('auth/login-penyewa'));
            exit;
        }

        $userPlg = (string) $_SESSION['penyewa_user_plg'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $profil = $this->model->getProfil($userPlg);

                if ($profil === null) {
                    unset($_SESSION['penyewa_user_plg']);

                    $_SESSION['flash_penyewa_nav'] =
                        'Data akun tidak ditemukan. Silakan login kembali.';

                    header('Location: ' . site_url('auth/login-penyewa'));
                    exit;
                }

                $flash = $_SESSION['flash_profil_penyewa'] ?? null;
                unset($_SESSION['flash_profil_penyewa']);

                $jumlahKeranjang =
                    $this->transaksiModel->getJumlahMobilKeranjang($userPlg);

                $this->view('penyewa/profil', [
                    'profil' => $profil,
                    'flash' => $flash,
                    'error' => null,
                    'old' => [],
                    'jumlahKeranjang' => $jumlahKeranjang
                ]);

                return;

            } catch (Throwable $e) {
                error_log('Pengambilan profil penyewa gagal: ' . $e->getMessage());

                http_response_code(500);
                exit('Profil belum dapat ditampilkan. Silakan coba kembali.');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: GET, POST');
            exit('Method Not Allowed');
        }

        $nik = trim((string) ($_POST['nik'] ?? ''));
        $nama = trim((string) ($_POST['nm_lngkp'] ?? ''));
        $noHp = trim((string) ($_POST['no_hp'] ?? ''));
        $alamat = trim((string) ($_POST['almt_lngkp'] ?? ''));

        $passwordSaatIni = (string) ($_POST['password_saat_ini'] ?? '');
        $passwordBaru = (string) ($_POST['password_baru'] ?? '');
        $konfirmasiPassword = (string) (
            $_POST['konfirmasi_password'] ?? ''
        );

        $old = [
            'nik' => $nik,
            'nm_lngkp' => $nama,
            'no_hp' => $noHp,
            'almt_lngkp' => $alamat
        ];

        $error = $this->validateProfilInput(
            $nik,
            $nama,
            $noHp,
            $alamat
        );

        $uploadedFiles = [];
        $profil = [];

        try {
            $profil = $this->model->getProfil($userPlg);

            if ($profil === null) {
                unset($_SESSION['penyewa_user_plg']);

                $_SESSION['flash_penyewa_nav'] =
                    'Data akun tidak ditemukan. Silakan login kembali.';

                header('Location: ' . site_url('auth/login-penyewa'));
                exit;
            }

            $passwordHashBaru = null;

            $inginGantiPassword =
                $passwordSaatIni !== '' ||
                $passwordBaru !== '' ||
                $konfirmasiPassword !== '';

            if ($inginGantiPassword) {
                if (
                    $passwordSaatIni === '' ||
                    $passwordBaru === '' ||
                    $konfirmasiPassword === ''
                ) {
                    $error = 'Untuk mengganti password, ketiga kolom password wajib diisi.';

                } elseif (strlen($passwordBaru) < 8) {
                    $error = 'Password baru minimal 8 karakter.';

                } elseif (strlen($passwordBaru) > 72) {
                    $error = 'Password baru maksimal 72 byte.';

                } elseif ($passwordBaru !== $konfirmasiPassword) {
                    $error = 'Konfirmasi password baru tidak sama.';

                } elseif (!$this->model->verifikasiPassword(
                    $userPlg,
                    $passwordSaatIni
                )) {
                    $error = 'Password saat ini tidak sesuai.';

                } else {
                    $passwordHashBaru = password_hash(
                        $passwordBaru,
                        PASSWORD_DEFAULT
                    );

                    if ($passwordHashBaru === false) {
                        throw new RuntimeException(
                            'Pembuatan hash password baru gagal.'
                        );
                    }
                }
            }

            $jumlahKeranjang =
                $this->transaksiModel->getJumlahMobilKeranjang($userPlg);

            if ($error !== null) {
                $this->view('penyewa/profil', [
                    'profil' => $profil,
                    'flash' => null,
                    'error' => $error,
                    'old' => $old,
                    'jumlahKeranjang' => $jumlahKeranjang
                ]);

                return;
            }

            $uploadKtp = $this->uploadFotoPenyewa('ft_ktp');

            if (!$uploadKtp['success']) {
                $this->renderProfilError(
                    $profil,
                    $old,
                    $uploadKtp['error']
                );
                return;
            }

            if ($uploadKtp['filename'] !== null) {
                $uploadedFiles[] = $uploadKtp['filename'];
            }

            $uploadSim = $this->uploadFotoPenyewa('ft_sim_a');

            if (!$uploadSim['success']) {
                $this->hapusFotoPenyewa($uploadedFiles);

                $this->renderProfilError(
                    $profil,
                    $old,
                    $uploadSim['error']
                );
                return;
            }

            if ($uploadSim['filename'] !== null) {
                $uploadedFiles[] = $uploadSim['filename'];
            }

            // Foto lama dipertahankan jika tidak ada foto baru.
            $ftKtpBaru = $uploadKtp['filename']
                ?? ($profil['ft_ktp'] ?? null);

            $ftSimBaru = $uploadSim['filename']
                ?? ($profil['ft_sim_a'] ?? null);

            $success = $this->model->updateProfil(
                $userPlg,
                $nik,
                $nama,
                $noHp,
                $alamat,
                $ftKtpBaru,
                $ftSimBaru,
                $passwordHashBaru
            );

            if (!$success) {
                $this->hapusFotoPenyewa($uploadedFiles);

                $this->renderProfilError(
                    $profil,
                    $old,
                    'Profil gagal diperbarui. Periksa kembali data Anda.'
                );
                return;
            }

            // Hapus foto lama setelah database berhasil diperbarui.
            $fotoLama = [];

            if (
                $uploadKtp['filename'] !== null &&
                !empty($profil['ft_ktp'])
            ) {
                $fotoLama[] = [
                    'ft_ktp',
                    $profil['ft_ktp']
                ];
            }

            if (
                $uploadSim['filename'] !== null &&
                !empty($profil['ft_sim_a'])
            ) {
                $fotoLama[] = [
                    'ft_sim_a',
                    $profil['ft_sim_a']
                ];
            }

            $this->hapusFotoPenyewa($fotoLama);

            $_SESSION['flash_profil_penyewa'] =
                'Profil berhasil diperbarui.';

            header('Location: ' . site_url('penyewa/profil'));
            exit;

        } catch (Throwable $e) {
            error_log('Update profil penyewa gagal: ' . $e->getMessage());

            if (!empty($uploadedFiles)) {
                $this->hapusFotoPenyewa($uploadedFiles);
            }

            $this->renderProfilError(
                $profil,
                $old,
                'Profil belum berhasil diperbarui. Silakan coba kembali.'
            );
        }
    }

    // =====================================================
    // VALIDASI INPUT PROFIL
    // =====================================================

    private function validateProfilInput(
        string $nik,
        string $nama,
        string $noHp,
        string $alamat
    ): ?string {
        if (!preg_match('/\A[0-9]{16}\z/', $nik)) {
            return 'NIK wajib terdiri dari tepat 16 digit angka.';
        }

        if (
            $nama === '' ||
            mb_strlen($nama) > 100 ||
            !preg_match(
                "/\A[\p{L}\p{M}]+(?:[ .'-][\p{L}\p{M}]+)*\z/u",
                $nama
            )
        ) {
            return 'Nama lengkap wajib diisi, maksimal 100 karakter, dan hanya berisi huruf serta pemisah nama yang wajar.';
        }

        if (
            $noHp === '' ||
            strlen($noHp) > 15 ||
            !preg_match('/\A[0-9+(). -]+\z/', $noHp) ||
            !preg_match('/[0-9]/', $noHp)
        ) {
            return 'Nomor HP wajib diisi, maksimal 15 karakter, dan hanya boleh berisi angka serta tanda +, titik, kurung, spasi, atau tanda hubung.';
        }

        if (
            $alamat === '' ||
            mb_strlen($alamat) > 300 ||
            preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $alamat)
        ) {
            return 'Alamat wajib diisi, maksimal 300 karakter, dan tidak boleh mengandung karakter kontrol.';
        }

        return null;
    }

    // =====================================================
    // MENAMPILKAN ERROR PROFIL
    // =====================================================

    private function renderProfilError(
        array $profil,
        array $old,
        string $error
    ): void {
        $userPlg = trim(
            (string) ($_SESSION['penyewa_user_plg'] ?? '')
        );

        $jumlahKeranjang = $userPlg === ''
            ? 0
            : $this->transaksiModel->getJumlahMobilKeranjang($userPlg);

        $this->view('penyewa/profil', [
            'profil' => $profil,
            'flash' => null,
            'error' => $error,
            'old' => $old,
            'jumlahKeranjang' => $jumlahKeranjang
        ]);
    }

    // =====================================================
    // UPLOAD FOTO KTP DAN SIM A
    // =====================================================

    private function uploadFotoPenyewa(string $field): array
    {
        if (!in_array($field, ['ft_ktp', 'ft_sim_a'], true)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Field foto tidak valid.'
            ];
        }

        if (
            !isset($_FILES[$field]) ||
            ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE)
                === UPLOAD_ERR_NO_FILE
        ) {
            return [
                'success' => true,
                'filename' => null,
                'error' => null
            ];
        }

        $file = $_FILES[$field];

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Unggah foto gagal. Periksa ukuran file dan konfigurasi PHP.'
            ];
        }

        if (
            !isset($file['tmp_name'], $file['size']) ||
            !is_uploaded_file($file['tmp_name']) ||
            (int) $file['size'] < 1 ||
            (int) $file['size'] > 5 * 1024 * 1024
        ) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Foto harus berukuran lebih dari 0 byte dan maksimal 5 MB.'
            ];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (
            !isset($allowed[$mime]) ||
            @getimagesize($file['tmp_name']) === false
        ) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Format foto harus JPEG, PNG, atau WEBP yang valid.'
            ];
        }

        $projectRoot = dirname(APPPATH);

        $uploadDir = $projectRoot
            . DIRECTORY_SEPARATOR . 'uploads'
            . DIRECTORY_SEPARATOR . 'penyewa'
            . DIRECTORY_SEPARATOR;

        if (
            !is_dir($uploadDir) &&
            !mkdir($uploadDir, 0755, true) &&
            !is_dir($uploadDir)
        ) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Folder penyimpanan foto penyewa tidak dapat dibuat.'
            ];
        }

        if (!is_writable($uploadDir)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Folder penyimpanan foto penyewa tidak dapat ditulis.'
            ];
        }

        try {
            $filename = $field . '_' .
                date('YmdHis') . '_' .
                bin2hex(random_bytes(8)) . '.' .
                $allowed[$mime];

        } catch (Throwable $e) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Nama file foto gagal dibuat.'
            ];
        }

        if (!move_uploaded_file(
            $file['tmp_name'],
            $uploadDir . $filename
        )) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Foto gagal dipindahkan ke folder penyimpanan.'
            ];
        }

        return [
            'success' => true,
            'filename' => $filename,
            'error' => null
        ];
    }

    // =====================================================
    // MENGHAPUS FOTO PENYEWA
    // =====================================================

    private function hapusFotoPenyewa(array $files): void
    {
        $uploadDir = dirname(APPPATH)
            . DIRECTORY_SEPARATOR . 'uploads'
            . DIRECTORY_SEPARATOR . 'penyewa'
            . DIRECTORY_SEPARATOR;

        foreach ($files as $item) {

            if (is_array($item)) {
                [$field, $filename] = $item;

            } else {
                $filename = $item;

                $field = is_string($filename)
                    ? (
                        str_starts_with($filename, 'ft_ktp_')
                            ? 'ft_ktp'
                            : 'ft_sim_a'
                    )
                    : '';
            }

            if (
                !in_array($field, ['ft_ktp', 'ft_sim_a'], true) ||
                !is_string($filename)
            ) {
                continue;
            }

            $pattern = '/^' . preg_quote($field, '/') .
                '_\d{10,14}_[a-f0-9]{12,16}\.(jpg|jpeg|png|webp)$/';

            if (!preg_match($pattern, $filename)) {
                continue;
            }

            $path = $uploadDir . $filename;

            if (is_file($path)) {
                @unlink($path);
            }
        }
    }
}
