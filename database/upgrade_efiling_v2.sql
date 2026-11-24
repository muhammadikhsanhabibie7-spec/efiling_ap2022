-- Upgrade e-Filing AP2022 untuk PHP 8.2 + Fitur Surat, Disposisi, Peminjaman, Retensi, Buku Agenda & Statistik
-- Idempotent migration script

ALTER TABLE `arsip_konten`
  ADD COLUMN IF NOT EXISTS `jenis_surat` ENUM('M','K') NOT NULL DEFAULT 'M' COMMENT 'M=Surat Masuk, K=Surat Keluar' AFTER `id_arsip_jenis`,
  ADD COLUMN IF NOT EXISTS `pengirim` VARCHAR(150) DEFAULT NULL COMMENT 'Asal Instansi / Dari' AFTER `no_surat`,
  ADD COLUMN IF NOT EXISTS `keterangan` TEXT DEFAULT NULL AFTER `perihal`,
  ADD COLUMN IF NOT EXISTS `masa_aktif` INT(11) DEFAULT 1 COMMENT 'Masa aktif arsip (tahun)' AFTER `keterangan`,
  ADD COLUMN IF NOT EXISTS `tgl_inaktif` DATE DEFAULT NULL AFTER `masa_aktif`,
  ADD COLUMN IF NOT EXISTS `tgl_retensi` DATE DEFAULT NULL AFTER `tgl_inaktif`,
  ADD COLUMN IF NOT EXISTS `status_arsip` ENUM('Aktif','Inaktif','Retensi','Musnah','Permanen') NOT NULL DEFAULT 'Aktif' AFTER `tgl_retensi`;

CREATE TABLE IF NOT EXISTS `arsip_disposisi` (
  `id_disposisi` INT(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` INT(11) UNSIGNED NOT NULL,
  `no_agenda` VARCHAR(50) DEFAULT NULL,
  `tgl_disposisi` DATE NOT NULL,
  `dari_pegawai` INT(11) DEFAULT NULL,
  `tujuan_bidang` INT(11) DEFAULT NULL COMMENT 'Diteruskan ke Unit Organisasi/Bidang',
  `tujuan_pegawai` INT(11) DEFAULT NULL COMMENT 'Diteruskan ke Pegawai/PIC',
  `diteruskan_kepada` VARCHAR(200) DEFAULT NULL,
  `sifat` ENUM('Biasa','Penting','Segera','Rahasia') NOT NULL DEFAULT 'Biasa',
  `instruksi` TEXT NOT NULL COMMENT 'Instruksi Disposisi',
  `tembusan` VARCHAR(255) DEFAULT NULL COMMENT 'Tembusan (Mengetahui)',
  `catatan` TEXT DEFAULT NULL,
  `tindak_lanjut` TEXT DEFAULT NULL COMMENT 'Tindak Lanjut (TL)',
  `status_tl` ENUM('Menunggu','Diproses','Selesai') NOT NULL DEFAULT 'Menunggu',
  `tgl_tindak_lanjut` DATETIME DEFAULT NULL,
  `updated_by` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_disposisi`),
  KEY `idx_disp_konten` (`id_arsip_konten`),
  KEY `idx_disp_status` (`status_tl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `arsip_peminjaman` (
  `id_peminjaman` INT(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` INT(11) UNSIGNED NOT NULL,
  `id_pegawai_peminjam` INT(11) DEFAULT NULL,
  `nama_peminjam` VARCHAR(150) NOT NULL,
  `unit_peminjam` VARCHAR(150) DEFAULT NULL,
  `keperluan` TEXT NOT NULL,
  `tgl_pinjam` DATE NOT NULL,
  `lama_pinjam` INT(11) NOT NULL DEFAULT 7,
  `tgl_kembali` DATE NOT NULL,
  `tgl_dikembalikan` DATE DEFAULT NULL,
  `status_pinjam` ENUM('Menunggu','Dipinjam','Ditolak','Dikembalikan','Telat') NOT NULL DEFAULT 'Menunggu',
  `diverifikasi_oleh` INT(11) DEFAULT NULL,
  `tgl_verifikasi` DATETIME DEFAULT NULL,
  `catatan_verifikasi` TEXT DEFAULT NULL,
  `divalidasi_superadmin` TINYINT(1) NOT NULL DEFAULT 0,
  `tgl_validasi_superadmin` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_peminjaman`),
  KEY `idx_pinjam_konten` (`id_arsip_konten`),
  KEY `idx_pinjam_status` (`status_pinjam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `arsip_retensi_log` (
  `id_log` INT(11) NOT NULL AUTO_INCREMENT,
  `id_arsip_konten` INT(11) UNSIGNED NOT NULL,
  `status_lama` VARCHAR(30) DEFAULT NULL,
  `status_baru` VARCHAR(30) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `id_pegawai` INT(11) DEFAULT NULL,
  `tgl_perubahan` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_ret_konten` (`id_arsip_konten`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu Navigasi Baru (e-Filing id_aplikasi = 103)
INSERT IGNORE INTO `nav` (`id_nav`, `id_par_nav`, `id_aplikasi`, `ref`, `kode`, `tipe`, `judul`, `link`, `fa`, `urut`, `separator`, `aktif`) VALUES
(1850, NULL, 103, 1, NULL, 2, 'Disposisi', 'filing/disposisi', 'share-square-o', 4, 0, 1),
(1851, 1850, 103, 1, 'DSPAD', 1, 'Tambah Disposisi', NULL, NULL, 1, 0, 1),
(1852, 1850, 103, 1, 'DSPTL', 1, 'Tindak Lanjut Disposisi', NULL, NULL, 2, 0, 1),
(1853, 1850, 103, 1, 'DSPDE', 1, 'Hapus Disposisi', NULL, NULL, 3, 0, 1),
(1854, NULL, 103, 1, NULL, 2, 'Peminjaman Arsip', 'filing/peminjaman', 'book', 5, 0, 1),
(1855, 1854, 103, 1, 'PJMAD', 1, 'Ajukan Peminjaman', NULL, NULL, 1, 0, 1),
(1856, 1854, 103, 1, 'PJMVR', 1, 'Verifikasi Peminjaman', NULL, NULL, 2, 0, 1),
(1857, 1854, 103, 1, 'PJMDE', 1, 'Hapus Peminjaman', NULL, NULL, 3, 0, 1),
(1858, NULL, 103, 1, NULL, 2, 'Retensi Arsip', 'filing/retensi', 'archive', 6, 0, 1),
(1859, 1858, 103, 1, 'RETED', 1, 'Kelola Retensi', NULL, NULL, 1, 0, 1),
(1860, 1794, 103, 1, NULL, 2, 'Buku Agenda (M/K)', 'filing/laporan/agenda', 'book', 11, 0, 1);

UPDATE `nav` SET `urut` = 7 WHERE `id_nav` = 1794;

-- Role Pimpinan (Read-only arsip + Disposisi + Laporan)
INSERT IGNORE INTO `ref_role` (`id_role`, `nama_role`, `id_aplikasi`) VALUES
(84, 'Pimpinan', 103);

-- Hak akses untuk ADMIN (61) & Super Admin (83)
INSERT INTO `ref_role_nav` (`id_role`, `id_nav`)
SELECT r.id_role, n.id_nav
FROM (SELECT 61 AS id_role UNION ALL SELECT 83) r
CROSS JOIN (
  SELECT 1850 AS id_nav UNION ALL SELECT 1851 UNION ALL SELECT 1852 UNION ALL SELECT 1853
  UNION ALL SELECT 1854 UNION ALL SELECT 1855 UNION ALL SELECT 1856 UNION ALL SELECT 1857
  UNION ALL SELECT 1858 UNION ALL SELECT 1859 UNION ALL SELECT 1860
) n
WHERE NOT EXISTS (
  SELECT 1 FROM `ref_role_nav` x WHERE x.id_role = r.id_role AND x.id_nav = n.id_nav
);

-- Hak akses untuk Operator (82)
INSERT INTO `ref_role_nav` (`id_role`, `id_nav`)
SELECT 82, n.id_nav
FROM (
  SELECT 1850 AS id_nav UNION ALL SELECT 1851 UNION ALL SELECT 1852
  UNION ALL SELECT 1854 UNION ALL SELECT 1855
  UNION ALL SELECT 1858 UNION ALL SELECT 1860
) n
WHERE NOT EXISTS (
  SELECT 1 FROM `ref_role_nav` x WHERE x.id_role = 82 AND x.id_nav = n.id_nav
);

-- Hak akses untuk Pimpinan (84) - Lihat arsip (tanpa tambah/edit/hapus), Disposisi, Peminjaman (lihat), Retensi (lihat), Laporan & Statistik
INSERT INTO `ref_role_nav` (`id_role`, `id_nav`)
SELECT 84, n.id_nav
FROM (
  SELECT 1793 AS id_nav UNION ALL SELECT 1850 UNION ALL SELECT 1851 UNION ALL SELECT 1852
  UNION ALL SELECT 1854 UNION ALL SELECT 1858
  UNION ALL SELECT 1794 UNION ALL SELECT 1795 UNION ALL SELECT 1806 UNION ALL SELECT 1807 UNION ALL SELECT 1860
) n
WHERE NOT EXISTS (
  SELECT 1 FROM `ref_role_nav` x WHERE x.id_role = 84 AND x.id_nav = n.id_nav
);

-- Audit Trail & Device Cookie Tracking (Immutable Log Aktivitas)
CREATE TABLE IF NOT EXISTS `arsip_audit_log` (
  `id_audit` INT(11) NOT NULL AUTO_INCREMENT,
  `waktu` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pegawai` INT(11) DEFAULT NULL,
  `username` VARCHAR(100) DEFAULT NULL,
  `nama_user` VARCHAR(150) DEFAULT NULL,
  `nama_role` VARCHAR(100) DEFAULT NULL,
  `modul` VARCHAR(60) NOT NULL,
  `aksi` VARCHAR(40) NOT NULL,
  `objek` VARCHAR(200) DEFAULT NULL,
  `detail` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(60) DEFAULT NULL,
  `device_cookie` VARCHAR(80) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `url_path` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_audit`),
  KEY `idx_audit_waktu` (`waktu`),
  KEY `idx_audit_user` (`id_pegawai`),
  KEY `idx_audit_modul` (`modul`),
  KEY `idx_audit_aksi` (`aksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `nav` (`id_nav`, `id_par_nav`, `id_aplikasi`, `ref`, `kode`, `tipe`, `judul`, `link`, `fa`, `urut`, `separator`, `aktif`) VALUES
(1861, 1784, 103, 1, NULL, 2, 'Log Aktivitas (Audit)', 'filing/audit_log', 'history', 4, 0, 1);

-- Hak akses Audit Log untuk ADMIN (61), Super Admin (83), dan Pimpinan (84)
INSERT INTO `ref_role_nav` (`id_role`, `id_nav`)
SELECT r.id_role, 1861
FROM (SELECT 61 AS id_role UNION ALL SELECT 83 UNION ALL SELECT 84) r
WHERE NOT EXISTS (
  SELECT 1 FROM `ref_role_nav` x WHERE x.id_role = r.id_role AND x.id_nav = 1861
);

-- ============================================================================
-- 8. Perbaikan Data Referensi Unit Kerja yang Kosong (match ref_bidang.id_unit)
-- ============================================================================
INSERT IGNORE INTO `ref_unit` (`id_unit`, `id_par_unit`, `kode_unit`, `unit`, `alamat`, `telp`, `id_kepala`, `level_unit`, `aktif`, `urut`) VALUES
  (27, NULL, 'PLJ', 'Politeknik LP3I Jakarta', 'Jakarta', NULL, 0, 1, 1, 1),
  (32, 27, 'KPG', 'Kampus Pengembangan', 'Jakarta', NULL, 0, 2, 1, 2);

-- ============================================================================
-- 9. Sinkronisasi Hak Akses Role (Pimpinan Audit Menu + Kontrol 2 Lapis Admin)
-- ============================================================================
INSERT INTO `ref_role_nav` (`id_role`, `id_nav`)
SELECT 84, 1784 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `ref_role_nav` WHERE `id_role` = 84 AND `id_nav` = 1784);

DELETE FROM `ref_role_nav` WHERE `id_role` = 61 AND `id_nav` = 1800;
DELETE FROM `ref_role_nav` WHERE `id_role` = 61 AND `id_nav` IN (1785, 1786);
