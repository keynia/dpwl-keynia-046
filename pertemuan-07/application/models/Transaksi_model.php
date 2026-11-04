<?php

class Transaksi_model
{
  private mysqli $db;

  public function __construct()
  {
    
    global $db;
    $this->db = $db;
  }
// Mengambil kode transaksi keranjang aktif milik penyewa.
public function getKodeKeranjangAktif(string $userPlg): ?string
{
    $userPlg = trim($userPlg);

    if ($userPlg === '') {
        return null;
    }

    $stmt = $this->db->prepare(
        "SELECT kode_sewa
        FROM t_sewa
        WHERE user_plg = ?
          AND status_sewa = 'keranjang'
        ORDER BY kode_sewa ASC
        LIMIT 1"
    );

    if (!$stmt) {
        throw new RuntimeException(
            'Pencarian kode keranjang gagal disiapkan.'
        );
    }

    $stmt->bind_param('s', $userPlg);

    if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException(
            'Pencarian kode keranjang gagal dijalankan.'
        );
    }

    $hasil = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $hasil !== null
        ? (string) $hasil['kode_sewa']
        : null;
}

  private function ambilKunci(
    string $namaKunci,
    int $timeout = 10
  ): void {
    $stmt = $this->db->prepare(
      'SELECT GET_LOCK(?, ?) AS lock_status'
    );

    if (!$stmt) {
      throw new RuntimeException(
        'Kunci proses gagal disiapkan.'
      );
    }

    $stmt->bind_param('si', $namaKunci, $timeout);

    if (!$stmt->execute()) {
      $stmt->close();
      throw new RuntimeException(
        'Kunci proses gagal dijalankan.'
      );
    }

    $hasil = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ((int) ($hasil['lock_status'] ?? 0) !== 1) {
      throw new RuntimeException(
        'Kunci proses tidak diperoleh. Silakan coba lagi.'
      );
    }
  }

  private function lepasKunci(string $namaKunci): void
  {
    $stmt = $this->db->prepare(
      'SELECT RELEASE_LOCK(?)'
    );

    if (!$stmt) {
      throw new RuntimeException(
        'Pelepasan kunci proses gagal disiapkan.'
      );
    }

    $stmt->bind_param('s', $namaKunci);
    $berhasil = $stmt->execute();
    $stmt->close();

    if (!$berhasil) {
      throw new RuntimeException(
        'Pelepasan kunci proses gagal dijalankan.'
      );
    }
  }

  private function kunciDanBuatKodeSewa(): array
  {
    $tanggal = (
      new DateTimeImmutable(
        'now',
        new DateTimeZone('Asia/Jakarta')
      )
    )->format('Ymd');

    $prefix = 'INV/' . $tanggal . '/';
    $namaKunci = 'dpwl_inv_' . $tanggal;

    $this->ambilKunci($namaKunci);

    try {
      $stmt = $this->db->prepare(
        "SELECT kode_sewa
        FROM t_sewa
        WHERE kode_sewa LIKE ?
        ORDER BY kode_sewa DESC
        LIMIT 1"
      );

      if (!$stmt) {
        throw new RuntimeException(
          'Pencarian nomor transaksi gagal disiapkan.'
        );
      }

      $pola = $prefix . '%';
      $stmt->bind_param('s', $pola);

      if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException(
          'Pencarian nomor transaksi gagal dijalankan.'
        );
      }

      $baris = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      $nomorTerakhir = $baris === null
        ? 0
        : (int) substr($baris['kode_sewa'], -2);

      $nomorBerikutnya = $nomorTerakhir + 1;

      if ($nomorBerikutnya > 99) {
        throw new RuntimeException(
          'Nomor transaksi harian telah mencapai batas 99.'
        );
      }

      return [
        'kode_sewa' => $prefix . str_pad(
          (string) $nomorBerikutnya,
          2,
          '0',
          STR_PAD_LEFT
        ),
        'nama_kunci' => $namaKunci
      ];
    } catch (Throwable $e) {
      $this->lepasKunci($namaKunci);
      throw $e;
    }
  }

  public function getJumlahMobilKeranjang(string $userPlg): int
  {
    $userPlg = trim($userPlg);

    if ($userPlg === '') {
      return 0;
    }

    $stmt = $this->db->prepare(
      "SELECT COUNT(*) AS jumlah
      FROM t_pilih p
      INNER JOIN t_sewa s ON s.kode_sewa = p.kode_sewa
      WHERE s.user_plg = ?
        AND s.status_sewa = 'keranjang'"
    );

    if (!$stmt) {
      throw new RuntimeException(
        'Penghitungan kendaraan dalam keranjang gagal disiapkan.'
      );
    }

    $stmt->bind_param('s', $userPlg);

    if (!$stmt->execute()) {
      $stmt->close();
      throw new RuntimeException(
        'Penghitungan kendaraan dalam keranjang gagal dijalankan.'
      );
    }

    $hasil = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return (int) ($hasil['jumlah'] ?? 0);
  }

  // E.1: Membuat nama kunci keranjang berdasarkan penyewa.
  private function namaKunciKeranjang(string $userPlg): string
  {
    return 'dpwl_cart_' . substr(hash('sha256', $userPlg), 0, 48);
  }

  // E.1: Mengunci dan mengambil data kendaraan.
  private function kunciKendaraan(string $noPlat): array
  {
    $stmt = $this->db->prepare(
      "SELECT no_plat, hrg_hari, status_unit
      FROM t_mobil
      WHERE no_plat = ?
      LIMIT 1
      FOR UPDATE"
    );

    if (!$stmt) {
      throw new RuntimeException(
        'Query penguncian kendaraan gagal disiapkan.'
      );
    }

    $stmt->bind_param('s', $noPlat);

    if (!$stmt->execute()) {
      $stmt->close();
      throw new RuntimeException(
        'Penguncian kendaraan gagal dijalankan.'
      );
    }

    $kendaraan = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($kendaraan === null) {
      throw new RuntimeException(
        'Kendaraan tidak ditemukan.'
      );
    }

    return $kendaraan;
  }

  // E.1: Menghitung lama penyewaan kendaraan.
  private function hitungHariSewa(
    string $tglMulai,
    string $tglSelesai
  ): int {
    $mulai = DateTimeImmutable::createFromFormat(
      '!Y-m-d',
      $tglMulai
    );

    $errorMulai = DateTimeImmutable::getLastErrors();

    if (
      $mulai === false ||
      (
        $errorMulai !== false &&
        (
          $errorMulai['warning_count'] > 0 ||
          $errorMulai['error_count'] > 0
        )
      ) ||
      $mulai->format('Y-m-d') !== $tglMulai
    ) {
      throw new InvalidArgumentException(
        'Tanggal mulai tidak valid.'
      );
    }

    $selesai = DateTimeImmutable::createFromFormat(
      '!Y-m-d',
      $tglSelesai
    );

    $errorSelesai = DateTimeImmutable::getLastErrors();

    if (
      $selesai === false ||
      (
        $errorSelesai !== false &&
        (
          $errorSelesai['warning_count'] > 0 ||
          $errorSelesai['error_count'] > 0
        )
      ) ||
      $selesai->format('Y-m-d') !== $tglSelesai
    ) {
      throw new InvalidArgumentException(
        'Tanggal selesai tidak valid.'
      );
    }

    if ($selesai <= $mulai) {
      throw new InvalidArgumentException(
        'Tanggal selesai harus setelah tanggal mulai.'
      );
    }

    $hari = (int) $mulai->diff($selesai)->days;

    if ($hari < 1 || $hari > 127) {
      throw new InvalidArgumentException(
        'Lama sewa harus antara 1 dan 127 hari.'
      );
    }

    return $hari;
  }

  // E.1: Menormalisasi format waktu pengambilan.
  private function normalisasiWaktu(string $waktu): string
  {
    foreach (['H:i:s', 'H:i'] as $format) {
      $hasil = DateTimeImmutable::createFromFormat(
        '!' . $format,
        $waktu
      );

      $errors = DateTimeImmutable::getLastErrors();

      if (
        $hasil !== false &&
        (
          $errors === false ||
          (
            $errors['warning_count'] === 0 &&
            $errors['error_count'] === 0
          )
        ) &&
        $hasil->format($format) === $waktu
      ) {
        return $hasil->format('H:i:s');
      }
    }

    throw new InvalidArgumentException(
      'Waktu pengambilan tidak valid.'
    );
  }

  // E.1: Menambahkan kendaraan ke keranjang penyewa.
  public function tambahKeKeranjang(
    string $userPlg,
    string $noPlat
  ): string {
    $userPlg = trim($userPlg);
    $noPlat = trim($noPlat);

    if ($userPlg === '' || $noPlat === '') {
      throw new InvalidArgumentException(
        'Identitas penyewa dan kendaraan wajib diisi.'
      );
    }

    $this->db->begin_transaction();

    $namaKunci = null;
    $namaKunciKeranjang = null;
    $kunciKeranjangDidapat = false;

    try {
      $kendaraan = $this->kunciKendaraan($noPlat);

      if ($kendaraan['status_unit'] !== 'tersedia') {
        throw new RuntimeException(
          'Kendaraan tidak berstatus tersedia.'
        );
      }

      $namaKunciKeranjang = $this->namaKunciKeranjang($userPlg);

      $this->ambilKunci($namaKunciKeranjang);
      $kunciKeranjangDidapat = true;

      $stmt = $this->db->prepare(
        "SELECT kode_sewa, tgl_mulai, tgl_selesai, wkt_ambil
        FROM t_sewa
        WHERE user_plg = ?
          AND status_sewa = 'keranjang'
        ORDER BY kode_sewa ASC
        LIMIT 1
        FOR UPDATE"
      );

      if (!$stmt) {
        throw new RuntimeException(
          'Pencarian keranjang aktif gagal disiapkan.'
        );
      }

      $stmt->bind_param('s', $userPlg);

      if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException(
          'Pencarian keranjang aktif gagal dijalankan.'
        );
      }

      $keranjang = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      if ($keranjang !== null) {
        $kodeSewa = (string) $keranjang['kode_sewa'];
        $tglMulai = (string) $keranjang['tgl_mulai'];
        $tglSelesai = (string) $keranjang['tgl_selesai'];

        $wktAmbil = $this->normalisasiWaktu(
          (string) $keranjang['wkt_ambil']
        );
      } else {
        $sekarang = new DateTimeImmutable('now');

        $tglMulai = $sekarang->format('Y-m-d');
        $tglSelesai = $sekarang->modify('+1 day')->format('Y-m-d');
        $wktAmbil = $sekarang->format('H:i:s');

        $dataKode = $this->kunciDanBuatKodeSewa();

        $kodeSewa = $dataKode['kode_sewa'];
        $namaKunci = $dataKode['nama_kunci'];

        $stmt = $this->db->prepare(
          "INSERT INTO t_sewa (
            kode_sewa,
            tgl_mulai,
            tgl_selesai,
            wkt_ambil,
            status_sewa,
            user_plg
          ) VALUES (?, ?, ?, ?, 'keranjang', ?)"
        );

        if (!$stmt) {
          throw new RuntimeException(
            'Pembuatan transaksi gagal disiapkan.'
          );
        }

        $stmt->bind_param(
          'sssss',
          $kodeSewa,
          $tglMulai,
          $tglSelesai,
          $wktAmbil,
          $userPlg
        );

        if (!$stmt->execute()) {
          $stmt->close();
          throw new RuntimeException(
            'Transaksi keranjang gagal dibuat.'
          );
        }

        $stmt->close();
      }

      // Cegah kendaraan yang sama dimasukkan dua kali.
      $stmt = $this->db->prepare(
        "SELECT 1
        FROM t_pilih
        WHERE kode_sewa = ?
          AND no_plat = ?
        LIMIT 1
        FOR UPDATE"
      );

      if (!$stmt) {
        throw new RuntimeException(
          'Pemeriksaan kendaraan dalam keranjang gagal disiapkan.'
        );
      }

      $stmt->bind_param('ss', $kodeSewa, $noPlat);

      if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException(
          'Pemeriksaan kendaraan dalam keranjang gagal dijalankan.'
        );
      }

      $kendaraanSudahDipilih =
        $stmt->get_result()->fetch_assoc() !== null;

      $stmt->close();

      if ($kendaraanSudahDipilih) {
        throw new RuntimeException(
          'Kendaraan sudah ada dalam keranjang ini.'
        );
      }

      $hari = $this->hitungHariSewa(
        $tglMulai,
        $tglSelesai
      );

      $stmt = $this->db->prepare(
        "INSERT INTO t_pilih (
          kode_sewa,
          no_plat,
          hrg_sewa,
          n_hari
        ) VALUES (?, ?, ?, ?)"
      );

      if (!$stmt) {
        throw new RuntimeException(
          'Detail transaksi gagal disiapkan.'
        );
      }

      $harga = (int) $kendaraan['hrg_hari'];

      $stmt->bind_param(
        'ssii',
        $kodeSewa,
        $noPlat,
        $harga,
        $hari
      );

      if (!$stmt->execute()) {
        $errno = $stmt->errno;
        $stmt->close();

        if ($errno === 1062) {
          throw new RuntimeException(
            'Kendaraan sudah ada dalam keranjang ini.'
          );
        }

        throw new RuntimeException(
          'Kendaraan gagal ditambahkan ke keranjang.'
        );
      }

      $stmt->close();

      $this->db->commit();

      if ($namaKunci !== null) {
        $this->lepasKunci($namaKunci);
        $namaKunci = null;
      }

      if (
        $kunciKeranjangDidapat &&
        $namaKunciKeranjang !== null
      ) {
        $this->lepasKunci($namaKunciKeranjang);
        $kunciKeranjangDidapat = false;
      }

      return $kodeSewa;
    } catch (Throwable $e) {
      $this->db->rollback();

      if ($namaKunci !== null) {
        $this->lepasKunci($namaKunci);
      }

      if (
        $kunciKeranjangDidapat &&
        $namaKunciKeranjang !== null
      ) {
        $this->lepasKunci($namaKunciKeranjang);
      }

      throw $e;
    }
  }
// Mengambil data keranjang dan detail kendaraan milik penyewa
public function getKeranjang(
    string $kodeSewa,
    string $userPlg
): ?array {

    $sql = "SELECT
                s.kode_sewa,
                s.tgl_mulai,
                s.tgl_selesai,
                s.wkt_ambil,
                s.status_sewa,
                s.user_plg,
                p.no_plat,
                p.hrg_sewa,
                p.n_hari,
                m.nm_mobil,
                m.jns_mobil,
                m.transmisi,
                m.ft_depan
            FROM t_sewa s
            LEFT JOIN t_pilih p
                ON s.kode_sewa = p.kode_sewa
            LEFT JOIN t_mobil m
                ON p.no_plat = m.no_plat
            WHERE s.kode_sewa = ?
              AND s.user_plg = ?
              AND s.status_sewa = 'keranjang'
            ORDER BY p.no_plat ASC";

    $stmt = $this->db->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException(
            'Query keranjang gagal: ' . $this->db->error
        );
    }

    $stmt->bind_param('ss', $kodeSewa, $userPlg);

    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();

        throw new RuntimeException(
            'Query keranjang gagal dijalankan: ' . $error
        );
    }

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (empty($data)) {
        return null;
    }

    $detail = [];
    $total = 0;

    foreach ($data as $item) {
        if (empty($item['no_plat'])) {
            continue;
        }

        $harga = (int) $item['hrg_sewa'];
        $hari = (int) $item['n_hari'];

        $total += $harga * $hari;
        $detail[] = $item;
    }

    return [
        'kode_sewa' => $data[0]['kode_sewa'],
        'tgl_mulai' => $data[0]['tgl_mulai'],
        'tgl_selesai' => $data[0]['tgl_selesai'],
        'wkt_ambil' => $data[0]['wkt_ambil'],
        'status_sewa' => $data[0]['status_sewa'],
        'user_plg' => $data[0]['user_plg'],
        'detail' => $detail,
        'total' => $total
    ];
}
// Memproses checkout dan menyimpan pembayaran DP.
public function checkout(
    string $kodeSewa,
    string $userPlg
): bool {
    $kodeSewa = trim($kodeSewa);
    $userPlg = trim($userPlg);

    if ($kodeSewa === '' || $userPlg === '') {
        throw new InvalidArgumentException(
            'Kode transaksi dan penyewa wajib diisi.'
        );
    }

    $this->db->begin_transaction();

    try {
        // 1. Memastikan transaksi milik penyewa dan masih berstatus keranjang.
        $stmt = $this->db->prepare(
            "SELECT kode_sewa, tgl_mulai, tgl_selesai
             FROM t_sewa
             WHERE kode_sewa = ?
               AND user_plg = ?
               AND status_sewa = 'keranjang'
             FOR UPDATE"
        );

        if (!$stmt) {
            throw new RuntimeException(
                'Pemeriksaan transaksi gagal disiapkan.'
            );
        }

        $stmt->bind_param('ss', $kodeSewa, $userPlg);
        $stmt->execute();

        $transaksi = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($transaksi === null) {
            throw new RuntimeException(
                'Transaksi tidak ditemukan atau sudah tidak berstatus keranjang.'
            );
        }

        // 2. Menghitung durasi sewa.
        $hariSeharusnya = $this->hitungHariSewa(
            (string) $transaksi['tgl_mulai'],
            (string) $transaksi['tgl_selesai']
        );

        // 3. Mengambil detail kendaraan dan memeriksa ketersediaannya.
        $stmt = $this->db->prepare(
            "SELECT
                p.no_plat,
                p.hrg_sewa,
                p.n_hari,
                m.status_unit
             FROM t_pilih p
             INNER JOIN t_mobil m
                ON m.no_plat = p.no_plat
             WHERE p.kode_sewa = ?
             FOR UPDATE"
        );

        if (!$stmt) {
            throw new RuntimeException(
                'Pemeriksaan detail kendaraan gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $kodeSewa);
        $stmt->execute();

        $detail = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($detail)) {
            throw new RuntimeException(
                'Keranjang masih kosong. Tambahkan kendaraan terlebih dahulu.'
            );
        }

        $total = 0;

        foreach ($detail as $item) {
            if ($item['status_unit'] !== 'tersedia') {
                throw new RuntimeException(
                    'Kendaraan tidak berstatus tersedia.'
                );
            }

            if ((int) $item['n_hari'] !== $hariSeharusnya) {
                throw new RuntimeException(
                    'Durasi sewa kendaraan tidak sesuai dengan periode transaksi.'
                );
            }

            $total +=
                (int) $item['hrg_sewa'] *
                (int) $item['n_hari'];
        }

        if ($total <= 0) {
            throw new RuntimeException(
                'Total biaya transaksi tidak valid.'
            );
        }

        // 4. Memastikan DP belum pernah dibuat untuk transaksi ini.
        $stmt = $this->db->prepare(
            "SELECT kode_bayar
             FROM t_bayar
             WHERE kode_sewa = ?
               AND jns_byr = 'dp'
             LIMIT 1
             FOR UPDATE"
        );

        if (!$stmt) {
            throw new RuntimeException(
                'Pemeriksaan pembayaran DP gagal disiapkan.'
            );
        }

        $stmt->bind_param('s', $kodeSewa);
        $stmt->execute();

        $dpSudahAda = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();

        if ($dpSudahAda) {
            throw new RuntimeException(
                'Pembayaran DP untuk transaksi ini sudah tercatat.'
            );
        }

        // 5. Menghitung DP sebesar 30%.
        $nominalDp = (int) round($total * 30 / 100);

        // Kode pembayaran terdiri dari 15 karakter.
        $kodeBayar = 'B' . strtoupper(bin2hex(random_bytes(7)));

        // 6. Mengubah status transaksi menjadi diajukan.
        $stmt = $this->db->prepare(
            "UPDATE t_sewa
             SET status_sewa = 'diajukan'
             WHERE kode_sewa = ?
               AND user_plg = ?
               AND status_sewa = 'keranjang'"
        );

        if (!$stmt) {
            throw new RuntimeException(
                'Perubahan status transaksi gagal disiapkan.'
            );
        }

        $stmt->bind_param('ss', $kodeSewa, $userPlg);

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Status transaksi gagal diperbarui.'
            );
        }

        if ($stmt->affected_rows !== 1) {
            $stmt->close();

            throw new RuntimeException(
                'Status transaksi tidak berhasil diubah.'
            );
        }

        $stmt->close();

        // 7. Menyimpan pembayaran DP ke tabel t_bayar.
        $stmt = $this->db->prepare(
            "INSERT INTO t_bayar (
                kode_bayar,
                tgl_wkt_byr,
                ft_byr,
                mtode_byr,
                jns_byr,
                nominal,
                sttus_byr,
                tgl_wkt_ver,
                kode_sewa
             ) VALUES (
                ?, NOW(), NULL, 'transfer', 'dp', ?, 'pending', NULL, ?
             )"
        );

        if (!$stmt) {
            throw new RuntimeException(
                'Penyimpanan pembayaran DP gagal disiapkan.'
            );
        }

        $stmt->bind_param(
            'sis',
            $kodeBayar,
            $nominalDp,
            $kodeSewa
        );

        if (!$stmt->execute()) {
            $stmt->close();

            throw new RuntimeException(
                'Pembayaran DP gagal disimpan.'
            );
        }

        $stmt->close();

        // 8. Menyimpan seluruh perubahan.
        $this->db->commit();

        return true;

    } catch (Throwable $e) {
        $this->db->rollback();
        throw $e;
    }
}
}