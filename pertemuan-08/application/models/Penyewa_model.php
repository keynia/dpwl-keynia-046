
<?php

class Penyewa_model
{
    private mysqli $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    // Mencari username penyewa.
    public function findByUserPlg(string $userPlg): ?array
    {
        $sql = "SELECT user_plg
                FROM t_penyewa
                WHERE user_plg = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Pemeriksaan username penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $userPlg);

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Pemeriksaan username penyewa gagal dijalankan.'
            );
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row ?: null;
    }

    // Menambahkan data penyewa baru.
    public function create(array $data): bool
    {
        $sql = "INSERT INTO t_penyewa (
                    user_plg,
                    sandi,
                    nik,
                    nm_lngkp,
                    no_hp,
                    almt_lngkp,
                    ft_ktp,
                    ft_sim_a
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query tambah penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param(
            'ssssssss',
            $data['user_plg'],
            $data['sandi'],
            $data['nik'],
            $data['nm_lngkp'],
            $data['no_hp'],
            $data['almt_lngkp'],
            $data['ft_ktp'],
            $data['ft_sim_a']
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Mengambil data akun penyewa untuk login.
    public function findForLogin(string $userPlg): ?array
    {
        $sql = "SELECT user_plg, sandi, nm_lngkp
                FROM t_penyewa
                WHERE user_plg = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query pencarian akun penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $userPlg);

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Pencarian akun penyewa gagal dijalankan.'
            );
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row ?: null;
    }

    // Mengambil profil penyewa.
    public function getProfil(string $userPlg): ?array
    {
        $sql = "SELECT
                    user_plg,
                    nik,
                    nm_lngkp,
                    no_hp,
                    almt_lngkp,
                    ft_ktp,
                    ft_sim_a
                FROM t_penyewa
                WHERE user_plg = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query profil penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $userPlg);

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Pengambilan profil penyewa gagal dijalankan.'
            );
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row ?: null;
    }

    // Memverifikasi password penyewa.
    public function verifikasiPassword(
        string $userPlg,
        string $passwordSaatIni
    ): bool {
        $sql = "SELECT sandi
                FROM t_penyewa
                WHERE user_plg = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query verifikasi password gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $userPlg);

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Verifikasi password gagal dijalankan.'
            );
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row !== null
            && password_verify($passwordSaatIni, $row['sandi']);
    }

    // Memperbarui profil dan password penyewa.
    public function updateProfil(
        string $userPlg,
        string $nik,
        string $nama,
        string $noHp,
        string $alamat,
        ?string $ftKtp,
        ?string $ftSimA,
        ?string $passwordHash = null
    ): bool {
        if ($passwordHash !== null) {
            $sql = "UPDATE t_penyewa
                    SET nik = ?,
                        nm_lngkp = ?,
                        no_hp = ?,
                        almt_lngkp = ?,
                        ft_ktp = ?,
                        ft_sim_a = ?,
                        sandi = ?
                    WHERE user_plg = ?";

            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                throw new RuntimeException(
                    'Query update profil dan password gagal disiapkan.'
                );
            }

            $stmt->bind_param(
                'ssssssss',
                $nik,
                $nama,
                $noHp,
                $alamat,
                $ftKtp,
                $ftSimA,
                $passwordHash,
                $userPlg
            );
        } else {
            $sql = "UPDATE t_penyewa
                    SET nik = ?,
                        nm_lngkp = ?,
                        no_hp = ?,
                        almt_lngkp = ?,
                        ft_ktp = ?,
                        ft_sim_a = ?
                    WHERE user_plg = ?";

            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                throw new RuntimeException(
                    'Query update profil gagal disiapkan.'
                );
            }

            $stmt->bind_param(
                'sssssss',
                $nik,
                $nama,
                $noHp,
                $alamat,
                $ftKtp,
                $ftSimA,
                $userPlg
            );
        }

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Mengambil seluruh data penyewa untuk halaman Admin.
    public function getAll(): array
    {
        $sql = "SELECT
                    user_plg,
                    nik,
                    nm_lngkp,
                    no_hp,
                    almt_lngkp,
                    ft_ktp,
                    ft_sim_a
                FROM t_penyewa
                ORDER BY user_plg ASC";

        $result = $this->db->query($sql);

        if (!$result) {
            throw new RuntimeException(
                'Data penyewa gagal diambil.'
            );
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Mengambil satu data penyewa berdasarkan username.
    public function getByUserPlg(string $userPlg): ?array
    {
        $sql = "SELECT
                    user_plg,
                    nik,
                    nm_lngkp,
                    no_hp,
                    almt_lngkp,
                    ft_ktp,
                    ft_sim_a
                FROM t_penyewa
                WHERE user_plg = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query data penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $userPlg);

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Pengambilan data penyewa gagal dijalankan.'
            );
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return $row ?: null;
    }

    // Mengubah data penyewa dari halaman Admin.
    public function update(
        string $userPlg,
        array $data
    ): bool {
        $sql = "UPDATE t_penyewa
                SET nik = ?,
                    nm_lngkp = ?,
                    no_hp = ?,
                    almt_lngkp = ?,
                    ft_ktp = ?,
                    ft_sim_a = ?
                WHERE user_plg = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query update penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param(
            'sssssss',
            $data['nik'],
            $data['nm_lngkp'],
            $data['no_hp'],
            $data['almt_lngkp'],
            $data['ft_ktp'],
            $data['ft_sim_a'],
            $userPlg
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Menghapus data penyewa dari halaman Admin.
    public function delete(string $userPlg): bool
    {
        $sql = "DELETE FROM t_penyewa
                WHERE user_plg = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Query hapus penyewa gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $userPlg);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
