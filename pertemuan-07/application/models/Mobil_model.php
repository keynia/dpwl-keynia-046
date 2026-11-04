
<?php

class Mobil_model
{
    private mysqli $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    // Mengambil semua data mobil
    public function getAll(): array
    {
        $sql = "SELECT
                    no_plat,
                    nm_mobil,
                    nkursi,
                    nbagasi,
                    jns_mobil,
                    transmisi,
                    thn_buat,
                    ft_depan,
                    ft_blkg,
                    ft_interior,
                    hrg_hari,
                    cttn_unit,
                    status_unit
                FROM t_mobil
                ORDER BY no_plat ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $rows;
    }

    // Menambahkan data mobil
    public function create(array $data): bool
    {
        $sql = "INSERT INTO t_mobil (
                    no_plat,
                    nm_mobil,
                    nkursi,
                    nbagasi,
                    jns_mobil,
                    transmisi,
                    thn_buat,
                    ft_depan,
                    ft_blkg,
                    ft_interior,
                    hrg_hari,
                    cttn_unit,
                    status_unit
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $no_plat = $data['no_plat'];
        $nm_mobil = $data['nm_mobil'];
        $nkursi = (int) $data['nkursi'];
        $nbagasi = (int) $data['nbagasi'];
        $jns_mobil = $data['jns_mobil'];
        $transmisi = $data['transmisi'];
        $thn_buat = (int) $data['thn_buat'];
        $ft_depan = $data['ft_depan'] ?? null;
        $ft_blkg = $data['ft_blkg'] ?? null;
        $ft_interior = $data['ft_interior'] ?? null;
        $hrg_hari = (int) $data['hrg_hari'];
        $cttn_unit = $data['cttn_unit'];
        $status_unit = $data['status_unit'];

        $stmt->bind_param(
            'ssiississsiss',
            $no_plat,
            $nm_mobil,
            $nkursi,
            $nbagasi,
            $jns_mobil,
            $transmisi,
            $thn_buat,
            $ft_depan,
            $ft_blkg,
            $ft_interior,
            $hrg_hari,
            $cttn_unit,
            $status_unit
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    public function findByNoPlat(string $noPlat): ?array
{
$sql = "SELECT no_plat, nm_mobil, nkursi, nbagasi, jns_mobil,
transmisi, thn_buat, ft_depan, ft_blkg, ft_interior,
hrg_hari, cttn_unit, status_unit
FROM t_mobil WHERE no_plat = ? LIMIT 1";
$stmt = $this->db->prepare($sql);
$stmt->bind_param('s', $noPlat);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
return $row ?: null;
}
public function update(string $noPlat, array $data): bool
{
$sql = "UPDATE t_mobil SET nm_mobil = ?, nkursi = ?, nbagasi = ?,
jns_mobil = ?, transmisi = ?, thn_buat = ?, ft_depan = ?,
ft_blkg = ?, ft_interior = ?, hrg_hari = ?, cttn_unit = ?,
status_unit = ? WHERE no_plat = ?";
$stmt = $this->db->prepare($sql);
$stmt->bind_param(
'siississsisss',
$data['nm_mobil'],
$data['nkursi'],
$data['nbagasi'],
$data['jns_mobil'],
$data['transmisi'],
$data['thn_buat'],
$data['ft_depan'],
$data['ft_blkg'],
$data['ft_interior'],
$data['hrg_hari'],
$data['cttn_unit'],
$data['status_unit'],
$noPlat
);
$success = $stmt->execute();
$stmt->close();
return $success;
}

public function delete(string $noPlat): bool
{
$stmt = $this->db->prepare("DELETE FROM t_mobil WHERE no_plat = ?");
$stmt->bind_param('s', $noPlat);
$success = $stmt->execute();
$stmt->close();
return $success;
}

// P6: mendapatkan mobil untuk katalog
public function getKatalog(): array
{
$sql = "SELECT
no_plat,
nm_mobil,
nkursi,
nbagasi,
jns_mobil,
transmisi,
thn_buat,
ft_depan,
ft_blkg,
ft_interior,
hrg_hari,
cttn_unit,
status_unit
FROM t_mobil
ORDER BY nm_mobil ASC";
$stmt = $this->db->prepare($sql);
if (!$stmt) {
    throw new RuntimeException(
'Query katalog kendaraan gagal disiapkan.'
);
}
if (!$stmt->execute()) {
$stmt->close();
throw new RuntimeException(
'Data katalog kendaraan gagal diambil.'
);
}
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
return $data;
}


}
