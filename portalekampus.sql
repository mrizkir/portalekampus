--
-- Table structure for table `agama`
--
CREATE TABLE `agama` (
  `idagama` tinyint(4) NOT NULL,
  `nama_agama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `agama` (`idagama`, `nama_agama`) VALUES
(0, 'none'),
(1, 'ISLAM'),
(2, 'PROTESTAN'),
(3, 'KHATOLIK'),
(4, 'HINDU DARMA'),
(5, 'BUDHA'),
(6, 'LAIN-LAIN');
-- --------------------------------------------------------

--
-- Table structure for table `backup_log`
--

CREATE TABLE `backup_log` (
  `backup_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `backup_time` datetime NOT NULL,
  `backup_file` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `idberita` int(11) NOT NULL,
  `idcat_berita` tinyint(4) NOT NULL,
  `tanggal_berita` datetime NOT NULL,
  `tanggal_modifikasi` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `tipe` char(2) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `content_resume` text NOT NULL,
  `main_content` text NOT NULL,
  `draft` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bipend`
--

CREATE TABLE `bipend` (
  `idbipend` bigint(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `no_faktur` varchar(150) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `gelombang` tinyint(4) NOT NULL,
  `dibayarkan` int(11) NOT NULL,
  `ket` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cat_berita`
--

CREATE TABLE `cat_berita` (
  `idcat_berita` tinyint(4) NOT NULL,
  `namecat_berita` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_konversi`
--

CREATE TABLE `data_konversi` (
  `idkonversi` bigint(20) NOT NULL,
  `iddata_konversi` bigint(20) NOT NULL,
  `nim` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_konversi2`
--

CREATE TABLE `data_konversi2` (
  `iddata_konversi` bigint(20) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `no_telp` varchar(25) NOT NULL,
  `nim_asal` varchar(30) NOT NULL,
  `kode_pt_asal` varchar(6) NOT NULL,
  `nama_pt_asal` varchar(100) NOT NULL,
  `kjenjang` char(1) NOT NULL,
  `kode_ps_asal` varchar(6) NOT NULL,
  `nama_ps_asal` varchar(100) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `idkur` tinyint(4) NOT NULL,
  `perpanjangan` tinyint(1) NOT NULL,
  `date_added` date NOT NULL,
  `date_modified` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `iddosen` mediumint(9) NOT NULL,
  `nidn` varchar(15) NOT NULL,
  `nipy` varchar(30) NOT NULL,
  `nama_dosen` varchar(70) NOT NULL,
  `gelar_depan` varchar(20) NOT NULL,
  `gelar_belakang` varchar(20) NOT NULL,
  `idjabatan` tinyint(4) NOT NULL,
  `alamat_dosen` varchar(50) NOT NULL,
  `telp_hp` varchar(25) NOT NULL,
  `email` varchar(60) NOT NULL,
  `website` varchar(70) NOT NULL,
  `username` varchar(30) NOT NULL,
  `userpassword` varchar(40) NOT NULL,
  `theme` varchar(25) NOT NULL DEFAULT 'cube',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_wali`
--

CREATE TABLE `dosen_wali` (
  `iddosen_wali` mediumint(9) NOT NULL,
  `iddosen` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dulang`
--

CREATE TABLE `dulang` (
  `iddulang` bigint(20) NOT NULL,
  `nim` char(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `tasmt` int(5) NOT NULL,
  `tanggal` datetime NOT NULL,
  `idkelas` char(1) DEFAULT NULL,
  `status_sebelumnya` char(1) NOT NULL,
  `k_status` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `formulir_pendaftaran`
--

CREATE TABLE `formulir_pendaftaran` (
  `no_formulir` int(11) NOT NULL,
  `nama_mhs` varchar(200) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `idagama` tinyint(4) NOT NULL,
  `nama_ibu_kandung` varchar(150) NOT NULL,
  `idwarga` enum('WNI','WNA') DEFAULT NULL,
  `nik` varchar(60) NOT NULL,
  `idstatus` enum('TIDAK_BEKERJA','PEKERJA') DEFAULT NULL,
  `alamat_kantor` varchar(200) NOT NULL,
  `alamat_rumah` varchar(200) NOT NULL,
  `kelurahan` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `telp_kantor` varchar(50) NOT NULL,
  `telp_rumah` varchar(50) NOT NULL,
  `telp_hp` varchar(50) NOT NULL,
  `idjp` tinyint(4) NOT NULL,
  `pendidikan_terakhir` varchar(40) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `kota` varchar(60) DEFAULT NULL,
  `provinsi` varchar(40) DEFAULT NULL,
  `tahun_pa` year(4) DEFAULT NULL,
  `jenis_slta` enum('SMU','SMK') NOT NULL,
  `asal_slta` varchar(150) NOT NULL,
  `status_slta` enum('NEGERI','SWASTA') DEFAULT NULL,
  `nomor_ijazah` varchar(60) NOT NULL,
  `kjur1` tinyint(4) DEFAULT NULL,
  `kjur2` tinyint(4) DEFAULT NULL,
  `idkelas` char(1) NOT NULL,
  `daftar_via` enum('FO','WEB') DEFAULT NULL,
  `ta` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `waktu_mendaftar` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `formulir_pendaftaran_temp`
--

CREATE TABLE `formulir_pendaftaran_temp` (
  `no_pendaftaran` int(11) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `nama_mhs` varchar(200) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `telp_hp` varchar(50) NOT NULL,
  `kjur1` tinyint(4) DEFAULT NULL,
  `kjur2` tinyint(4) DEFAULT NULL,
  `idkelas` char(1) NOT NULL,
  `ta` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `salt` varchar(7) NOT NULL,
  `userpassword` varchar(150) NOT NULL,
  `waktu_mendaftar` datetime NOT NULL,
  `file_bukti_bayar` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forumkategori`
--

CREATE TABLE `forumkategori` (
  `idkategori` smallint(4) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `forumkategori` (`idkategori`, `nama_kategori`) VALUES
(1, 'NILAI'),
(2, 'KRS'),
(3, 'AKADEMIK'),
(4, 'DOSEN'),
(5, 'KEUANGAN'),
(6, 'HAL UMUM'),
(7, 'BUG REPORT');
-- --------------------------------------------------------

--
-- Table structure for table `forumposts`
--

CREATE TABLE `forumposts` (
  `idpost` int(11) NOT NULL,
  `idkategori` smallint(6) NOT NULL,
  `parentpost` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `sumlike` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tipe` char(2) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gantinim`
--

CREATE TABLE `gantinim` (
  `idgantinim` int(11) NOT NULL,
  `nim_baru` char(20) NOT NULL,
  `nim_lama` char(20) NOT NULL,
  `tanggal` date NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `ket` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gantinirm`
--

CREATE TABLE `gantinirm` (
  `idgantinirm` int(11) NOT NULL,
  `nirm_baru` char(20) NOT NULL,
  `nirm_lama` char(20) NOT NULL,
  `tanggal` date NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `ket` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `group_access`
--

CREATE TABLE `group_access` (
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `r` int(1) NOT NULL DEFAULT '0',
  `w` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_akademik`
--

CREATE TABLE `jabatan_akademik` (
  `idjabatan` tinyint(4) NOT NULL,
  `nama_jabatan` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan_akademik`
--

INSERT INTO `jabatan_akademik` (`idjabatan`, `nama_jabatan`) VALUES
(0, 'STAF PENGAJAR'),
(1, 'ASISTEN AHLI'),
(2, 'LEKTOR'),
(3, 'LEKTOR KEPALA'),
(4, 'GURU BESAR');
-- --------------------------------------------------------

--
-- Table structure for table `jadwalsidang`
--

CREATE TABLE `jadwalsidang` (
  `idjadwalsidang` int(11) NOT NULL,
  `tanggalsidang` date NOT NULL,
  `jamsidang_awal` varchar(9) NOT NULL,
  `jamsidang_akhir` varchar(9) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `tahun` year(4) NOT NULL,
  `nim` char(20) NOT NULL,
  `penguji1` mediumint(9) NOT NULL,
  `penguji2` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_ujian_pmb`
--

CREATE TABLE `jadwal_ujian_pmb` (
  `idjadwal_ujian` int(11) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `nama_kegiatan` varchar(200) NOT NULL,
  `tanggal_ujian` date NOT NULL,
  `jam_mulai` varchar(5) NOT NULL,
  `jam_akhir` varchar(5) NOT NULL,
  `tanggal_akhir_daftar` date NOT NULL,
  `idruangkelas` smallint(6) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban`
--

CREATE TABLE `jawaban` (
  `idjawaban` int(11) NOT NULL,
  `idsoal` int(11) NOT NULL,
  `jawaban` tinytext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_ujian`
--

CREATE TABLE `jawaban_ujian` (
  `idjawabanujian` int(11) NOT NULL,
  `idsoal` int(11) NOT NULL,
  `idjawaban` int(11) NOT NULL,
  `no_formulir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pekerjaan`
--

CREATE TABLE `jenis_pekerjaan` (
  `idjp` tinyint(4) NOT NULL,
  `nama_pekerjaan` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jenis_pekerjaan`
--

INSERT INTO `jenis_pekerjaan` (`idjp`, `nama_pekerjaan`) VALUES
(0, 'none'),
(1, 'PNS'),
(2, 'PENSIUNAN, PURNAWIRAWAN'),
(3, 'KARYAWAN SWASTA'),
(4, 'PETANI'),
(5, 'BURUH/TUKANG'),
(6, 'TNI/POLRI'),
(7, 'PENGUSAHA'),
(8, 'PEDAGANG'),
(9, 'NELAYAN'),
(10, 'LAIN-LAIN');
-- --------------------------------------------------------

--
-- Table structure for table `jenjang_studi`
--

CREATE TABLE `jenjang_studi` (
  `kjenjang` char(1) NOT NULL,
  `njenjang` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `jenjang_studi` (`kjenjang`, `njenjang`) VALUES
('A', 'S-3'),
('B', 'S-2'),
('C', 'S-1'),
('D', 'D-4'),
('E', 'D-3'),
('F', 'D-2'),
('G', 'D-1'),
('H', 'SP-1'),
('I', 'SP-2'),
('J', 'PROFESI'),
('X', 'NON-AKADEMIK');
-- --------------------------------------------------------

--
-- Table structure for table `kartu_ujian`
--

CREATE TABLE `kartu_ujian` (
  `no_formulir` int(11) NOT NULL,
  `no_ujian` varchar(25) NOT NULL,
  `tgl_ujian` datetime NOT NULL,
  `tgl_selesai_ujian` datetime NOT NULL,
  `isfinish` tinyint(1) NOT NULL DEFAULT '0',
  `idtempat_spmb` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kbm`
--

CREATE TABLE `kbm` (
  `idkbm` int(11) NOT NULL,
  `idkelas_mhs` int(11) NOT NULL,
  `pertemuan_ke` tinyint(4) NOT NULL,
  `hari` tinyint(4) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL,
  `metode` varchar(35) NOT NULL,
  `materi` varchar(255) NOT NULL,
  `periksa` tinyint(1) NOT NULL,
  `tanggal_periksa` datetime NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kbm_detail`
--

CREATE TABLE `kbm_detail` (
  `idkbm_detail` bigint(20) NOT NULL,
  `idkbm` int(11) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `kehadiran` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `idkelas` char(1) NOT NULL,
  `nkelas` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`idkelas`, `nkelas`) VALUES
('A', 'REGULER (S1)'),
('B', 'KARYAWAN (S1)'),
('C', 'EKSTENSI (S1)'),
('D', 'REGULER (S2)');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_mhs`
--

CREATE TABLE `kelas_mhs` (
  `idkelas_mhs` int(11) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `nama_kelas` tinyint(4) NOT NULL,
  `hari` varchar(7) NOT NULL,
  `jam_masuk` char(5) NOT NULL,
  `jam_keluar` char(5) NOT NULL,
  `idpengampu_penyelenggaraan` int(11) NOT NULL,
  `idruangkelas` smallint(6) NOT NULL,
  `persen_quiz` tinyint(4) NOT NULL DEFAULT '15',
  `persen_tugas` tinyint(4) NOT NULL DEFAULT '15',
  `persen_uts` tinyint(4) NOT NULL DEFAULT '30',
  `persen_uas` tinyint(4) NOT NULL DEFAULT '40',
  `persen_absen` tinyint(4) NOT NULL DEFAULT '0',
  `isi_nilai` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kelas_mhs_detail`
--

CREATE TABLE `kelas_mhs_detail` (
  `idkelas_mhs_detail` bigint(20) NOT NULL,
  `idkelas_mhs` int(11) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_pertanyaan`
--

CREATE TABLE `kelompok_pertanyaan` (
  `idkelompok_pertanyaan` smallint(6) NOT NULL,
  `idkategori` tinyint(4) NOT NULL,
  `nama_kelompok` varchar(50) NOT NULL,
  `orders` tinyint(4) NOT NULL,
  `create_at` date NOT NULL,
  `update_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kombi`
--

CREATE TABLE `kombi` (
  `idkombi` tinyint(4) NOT NULL,
  `nama_kombi` varchar(50) NOT NULL,
  `periode_pembayaran` enum('none','semesteran','sekali') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Dumping data for table kombi
--

INSERT INTO kombi (idkombi, nama_kombi, periode_pembayaran) VALUES
(1, 'Biaya Pendaftaran', 'none'),
(2, 'Heregestrasi', 'semesteran'),
(3, 'Aptisi', 'semesteran'),
(4, 'MOPSPEK', 'sekali'),
(5, 'Kartu Tanda Mahasiswa (KTM)', 'sekali'),
(6, 'Asuransi Mahasiswa', 'semesteran'),
(7, 'Dana Kemahasiswaan', 'semesteran'),
(8, 'Dana Pemeliharaan Sarana', 'sekali'),
(9, 'SPP', 'semesteran'),
(10, 'Iuran Perpustakaan', 'semesteran'),
(11, 'Jaket Almamater', 'sekali'),
(12, 'Cuti', 'none'),
(13, 'Wisuda', 'none'),
(14, 'Per SKS', 'none'),
(15, 'Iuran Kesehatan Mahasiswa', 'semesteran'),
(16, 'Public Speaking Training', 'sekali');
-- --------------------------------------------------------

--
-- Table structure for table `kombi_konfirmasi_pembayaran`
--

CREATE TABLE `kombi_konfirmasi_pembayaran` (
  `idkonfirmasi` int(11) NOT NULL,
  `idkombi` tinyint(4) NOT NULL,
  `biaya` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kombi_per_ta`
--

CREATE TABLE `kombi_per_ta` (
  `idkombi_per_ta` int(11) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `idkombi` tinyint(4) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `biaya` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `konfirmasi_pembayaran`
--

CREATE TABLE `konfirmasi_pembayaran` (
  `idkonfirmasi` int(11) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `idrekening_institusi` tinyint(4) NOT NULL,
  `idmetode_pembayaran` tinyint(4) NOT NULL,
  `pembayaran_dari_bank` varchar(20) NOT NULL,
  `pemilik_rekening` varchar(60) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `ta` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `konsentrasi`
--

CREATE TABLE `konsentrasi` (
  `idkonsentrasi` tinyint(4) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `nama_konsentrasi` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kontrak_matakuliah`
--

CREATE TABLE `kontrak_matakuliah` (
  `idkelas_mhs` int(11) NOT NULL,
  `absensi` tinyint(4) NOT NULL,
  `quiz` tinyint(4) NOT NULL,
  `tugas` tinyint(4) NOT NULL,
  `uts` tinyint(4) NOT NULL,
  `uas` tinyint(4) NOT NULL,
  `keterangan` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `idkrs` bigint(20) NOT NULL,
  `tgl_krs` date NOT NULL,
  `no_krs` varchar(50) NOT NULL,
  `nim` char(20) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tasmt` int(5) NOT NULL,
  `sah` tinyint(1) NOT NULL,
  `tgl_disahkan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `krsmatkul`
--

CREATE TABLE `krsmatkul` (
  `idkrsmatkul` bigint(20) NOT NULL,
  `idkrs` bigint(20) NOT NULL,
  `idpenyelenggaraan` bigint(20) NOT NULL,
  `batal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner`
--

CREATE TABLE `kuesioner` (
  `idkuesioner` int(11) NOT NULL,
  `old_idkuesioner` int(11) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idkelompok_pertanyaan` smallint(6) NOT NULL,
  `pertanyaan` varchar(200) NOT NULL,
  `orders` tinyint(4) NOT NULL,
  `date_added` date NOT NULL,
  `date_modified` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_hasil`
--

CREATE TABLE `kuesioner_hasil` (
  `idpengampu_penyelenggaraan` int(11) NOT NULL,
  `jumlah_mhs` mediumint(9) NOT NULL,
  `total_nilai` mediumint(9) NOT NULL,
  `jumlah_soal` smallint(6) NOT NULL,
  `skor_tertinggi` int(11) NOT NULL,
  `skor_terendah` int(11) NOT NULL,
  `intervals` decimal(15,2) NOT NULL,
  `maks_sangatburuk` decimal(15,2) NOT NULL,
  `maks_buruk` decimal(15,2) NOT NULL,
  `maks_sedang` decimal(15,2) NOT NULL,
  `maks_baik` decimal(15,2) NOT NULL,
  `maks_sangatbaik` decimal(15,2) NOT NULL,
  `n_kuan` tinyint(11) NOT NULL,
  `n_kual` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_indikator`
--

CREATE TABLE `kuesioner_indikator` (
  `idindikator` int(11) NOT NULL,
  `idkuesioner` int(11) NOT NULL,
  `nilai_indikator` tinyint(4) NOT NULL,
  `nama_indikator` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_jawaban`
--

CREATE TABLE `kuesioner_jawaban` (
  `idkuesioner_jawaban` bigint(20) NOT NULL,
  `idpengampu_penyelenggaraan` int(11) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `idkuesioner` int(11) NOT NULL,
  `idindikator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum`
--

CREATE TABLE `kurikulum` (
  `idkur` tinyint(4) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `ta` year(4) NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` varchar(60) NOT NULL,
  `default_` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_master`
--

CREATE TABLE `log_master` (
  `idlog_master` bigint(20) NOT NULL,
  `userid` int(11) NOT NULL,
  `tipe_id` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_nilai_matakuliah`
--

CREATE TABLE `log_nilai_matakuliah` (
  `idlog` int(11) NOT NULL,
  `idlog_master` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `nim` char(20) NOT NULL,
  `kmatkul` char(9) NOT NULL,
  `nmatkul` varchar(50) NOT NULL,
  `aktivitas` varchar(15) NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_transkrip_asli`
--

CREATE TABLE `log_transkrip_asli` (
  `idlog` int(11) NOT NULL,
  `idlog_master` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `nim` char(20) NOT NULL,
  `kmatkul` char(9) NOT NULL,
  `nmatkul` varchar(50) NOT NULL,
  `aktivitas` varchar(15) NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `kmatkul` char(9) NOT NULL,
  `idkur` tinyint(4) NOT NULL,
  `nmatkul` varchar(50) NOT NULL,
  `sks` char(1) NOT NULL,
  `idkonsentrasi` tinyint(4) NOT NULL,
  `ispilihan` tinyint(1) NOT NULL DEFAULT '0',
  `islintas_prodi` tinyint(1) NOT NULL DEFAULT '0',
  `semester` char(2) NOT NULL,
  `sks_tatap_muka` tinyint(4) NOT NULL,
  `sks_praktikum` tinyint(4) NOT NULL,
  `sks_praktik_lapangan` tinyint(4) NOT NULL,
  `minimal_nilai` char(1) NOT NULL,
  `syarat_ta` tinyint(1) NOT NULL,
  `aktif` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah_syarat`
--

CREATE TABLE `matakuliah_syarat` (
  `idsyarat_kmatkul` int(11) NOT NULL,
  `kmatkul` char(9) NOT NULL,
  `kmatkul_syarat` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `idmetode_pembayaran` tinyint(4) NOT NULL,
  `nama_pembayaran` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_absensi`
--

CREATE TABLE `nilai_absensi` (
  `idnilai_absensi` bigint(20) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `n_kuan` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_imported`
--

CREATE TABLE `nilai_imported` (
  `idkrsmatkul` bigint(20) NOT NULL,
  `idkelas_mhs` int(11) NOT NULL,
  `persentase_quiz` decimal(5,2) NOT NULL,
  `persentase_tugas` decimal(5,2) NOT NULL,
  `persentase_uts` decimal(5,2) NOT NULL,
  `persentase_uas` decimal(5,2) NOT NULL,
  `persentase_absen` decimal(5,2) NOT NULL,
  `nilai_quiz` decimal(5,2) NOT NULL,
  `nilai_tugas` decimal(5,2) NOT NULL,
  `nilai_uts` decimal(5,2) NOT NULL,
  `nilai_uas` decimal(5,2) NOT NULL,
  `nilai_absen` decimal(5,2) NOT NULL,
  `n_kuan` decimal(5,2) NOT NULL,
  `n_kual` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_konversi2`
--

CREATE TABLE `nilai_konversi2` (
  `idnilai_konversi` bigint(20) NOT NULL,
  `iddata_konversi` bigint(20) NOT NULL,
  `kmatkul` varchar(9) NOT NULL,
  `kmatkul_asal` varchar(9) NOT NULL,
  `matkul_asal` varchar(80) NOT NULL,
  `sks_asal` tinyint(4) NOT NULL,
  `n_kual` char(1) NOT NULL,
  `keterangan` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_matakuliah`
--

CREATE TABLE `nilai_matakuliah` (
  `idnilai` bigint(20) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `persentase_quiz` decimal(5,2) NOT NULL,
  `persentase_tugas` decimal(5,2) NOT NULL,
  `persentase_uts` decimal(5,2) NOT NULL,
  `persentase_uas` decimal(5,2) NOT NULL,
  `persentase_absen` decimal(5,2) NOT NULL,
  `nilai_quiz` decimal(5,2) NOT NULL,
  `nilai_tugas` decimal(5,2) NOT NULL,
  `nilai_uts` decimal(5,2) NOT NULL,
  `nilai_uas` decimal(5,2) NOT NULL,
  `nilai_absen` decimal(5,2) NOT NULL,
  `n_kuan` decimal(5,2) NOT NULL,
  `n_kual` char(1) NOT NULL,
  `userid_input` smallint(6) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `userid_modif` smallint(6) NOT NULL,
  `tanggal_modif` datetime NOT NULL,
  `bydosen` tinyint(1) NOT NULL DEFAULT '0',
  `ket` varchar(20) NOT NULL,
  `telah_isi_kuesioner` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_isi_kuesioner` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_quiz`
--

CREATE TABLE `nilai_quiz` (
  `idnilai_quiz` bigint(20) NOT NULL,
  `idquiz_mk` int(11) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `n_kuan` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_tugas`
--

CREATE TABLE `nilai_tugas` (
  `idnilai_tugas` bigint(20) NOT NULL,
  `idtugas_mk` int(11) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `n_kuan` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_uas`
--

CREATE TABLE `nilai_uas` (
  `idnilai_uas` bigint(20) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `n_kuan` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_ujian_masuk`
--

CREATE TABLE `nilai_ujian_masuk` (
  `idnilai_ujian_masuk` bigint(20) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `jumlah_soal` smallint(6) NOT NULL,
  `jawaban_benar` smallint(6) NOT NULL,
  `jawaban_salah` smallint(6) NOT NULL,
  `soal_tidak_terjawab` smallint(6) NOT NULL,
  `passing_grade_1` decimal(5,2) NOT NULL,
  `passing_grade_2` decimal(5,2) NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `ket_lulus` tinyint(1) NOT NULL DEFAULT '0',
  `kjur` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_uts`
--

CREATE TABLE `nilai_uts` (
  `idnilai_uts` bigint(20) NOT NULL,
  `idkrsmatkul` bigint(20) NOT NULL,
  `n_kuan` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `passinggrade`
--

CREATE TABLE `passinggrade` (
  `idpassing_grade` int(11) NOT NULL,
  `idjadwal_ujian` int(11) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `nilai` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_konsentrasi`
--

CREATE TABLE `pendaftaran_konsentrasi` (
  `nim` char(20) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `idkonsentrasi` tinyint(4) NOT NULL,
  `jumlah_sks` smallint(6) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `tanggal_daftar` datetime NOT NULL,
  `status_daftar` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `pendaftaran_kampus_merdeka`
--

CREATE TABLE `pendaftaran_kampus_merdeka` (
  `nim` char(20) NOT NULL,
  `kjur` tinyint(4) NOT NULL,  
  `jumlah_sks` smallint(6) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `tanggal_daftar` datetime NOT NULL,
  `status_daftar` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengampu_penyelenggaraan`
--

CREATE TABLE `pengampu_penyelenggaraan` (
  `idpengampu_penyelenggaraan` int(11) NOT NULL,
  `idpenyelenggaraan` bigint(20) NOT NULL,
  `iddosen` mediumint(9) NOT NULL,
  `verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `idpost` int(11) NOT NULL,
  `idkategori` smallint(6) NOT NULL,
  `parentpost` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `sumlike` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tipe` char(2) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT '1',
  `file_name` varchar(100) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` mediumint(9) NOT NULL,
  `file_path` varchar(100) NOT NULL,
  `file_url` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penyelenggaraan`
--

CREATE TABLE `penyelenggaraan` (
  `idpenyelenggaraan` bigint(20) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kmatkul` char(9) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `iddosen` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `perpanjangan_studi`
--

CREATE TABLE `perpanjangan_studi` (
  `idperpanjangan` int(11) NOT NULL,
  `nim` char(20) NOT NULL,
  `nim_lama` char(20) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `peserta_ujian_pmb`
--

CREATE TABLE `peserta_ujian_pmb` (
  `idpeserta_ujian` int(11) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `idjadwal_ujian` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pin`
--

CREATE TABLE `pin` (
  `no_pin` varchar(100) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `semester_masuk` tinyint(4) NOT NULL,
  `idkelas` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pindahkelas`
--

CREATE TABLE `pindahkelas` (
  `idpindahkelas` int(11) NOT NULL,
  `nim` char(20) NOT NULL,
  `idkelas_lama` char(1) NOT NULL,
  `idkelas_baru` char(1) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `tanggal` date NOT NULL,
  `no_surat` varchar(30) NOT NULL,
  `Keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pkrs`
--

CREATE TABLE `pkrs` (
  `idpkrs` int(11) NOT NULL,
  `nim` char(20) NOT NULL,
  `idpenyelenggaraan` bigint(20) NOT NULL,
  `tambah` tinyint(1) NOT NULL,
  `hapus` tinyint(1) NOT NULL,
  `batal` tinyint(1) NOT NULL,
  `sah` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


--
-- Table structure for table `profiles_mahasiswa`
--

CREATE TABLE `profiles_mahasiswa` (
  `idprofile` int(11) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `nim` char(20) NOT NULL,
  `userpassword` varchar(60) NOT NULL,
  `theme` varchar(25) NOT NULL DEFAULT 'cube',
  `photo_profile` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profiles_ortu`
--

CREATE TABLE `profiles_ortu` (
  `idprofile` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `nim` char(20) NOT NULL,
  `userpassword` varchar(60) NOT NULL,
  `theme` varchar(25) NOT NULL DEFAULT 'cube'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `kjur` tinyint(4) NOT NULL,
  `kode_epsbed` char(5) NOT NULL,
  `nama_ps` varchar(30) NOT NULL,
  `nama_ps_alias` varchar(6) NOT NULL,
  `kjenjang` char(1) NOT NULL,
  `konsentrasi` varchar(40) NOT NULL,
  `idkur` int(11) NOT NULL,
  `iddosen` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_mk`
--

CREATE TABLE `quiz_mk` (
  `idquiz_mk` int(11) NOT NULL,
  `idkelas_mhs` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tujuan` mediumtext NOT NULL,
  `tanggal_quiz` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `register_mahasiswa`
--

CREATE TABLE `register_mahasiswa` (
  `nim` char(20) NOT NULL,
  `nirm` char(20) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `tanggal` date NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `idkonsentrasi` tinyint(4) NOT NULL,
  `is_merdeka` tinyint(1) NOT NULL DEFAULT '0',
  `iddosen_wali` mediumint(9) NOT NULL,
  `k_status` char(1) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `perpanjang` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_laporan_pembayaran_per_semester`
--

CREATE TABLE `rekap_laporan_pembayaran_per_semester` (
  `idrekap` bigint(20) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `nim` char(20) NOT NULL,
  `nirm` char(20) NOT NULL,
  `nama_mhs` varchar(200) NOT NULL,
  `jk` char(1) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `semester_masuk` tinyint(1) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `n_kelas` varchar(15) NOT NULL,
  `dibayarkan` decimal(10,0) NOT NULL,
  `kewajiban` decimal(10,0) NOT NULL,
  `sisa` decimal(10,0) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `kjur` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_status_mahasiswa`
--

CREATE TABLE `rekap_status_mahasiswa` (
  `idrekap` bigint(20) NOT NULL,
  `nim` char(20) NOT NULL,
  `nirm` char(20) NOT NULL,
  `nama_mhs` varchar(200) NOT NULL,
  `jk` char(1) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `ta` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `is_bayar` tinyint(1) NOT NULL DEFAULT '0',
  `k_status` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rekening_institusi`
--

CREATE TABLE `rekening_institusi` (
  `idrekening_institusi` int(11) NOT NULL,
  `no_rekening` varchar(30) NOT NULL,
  `bank` varchar(30) NOT NULL,
  `idkelas` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ruangkelas`
--

CREATE TABLE `ruangkelas` (
  `idruangkelas` smallint(6) NOT NULL,
  `namaruang` varchar(70) NOT NULL,
  `kapasitas` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `setting_id` smallint(6) NOT NULL,
  `group` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `key` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `value` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES
(1, 'general', 'default_ta', '2017'),
(2, 'general', 'default_semester', '2'),
(3, 'general', 'default_pagesize', '15'),
(7, 'general', 'minimal_sks_daftar_konsentrasi', '60'),
(8, 'general', 'jslogger', '0'),
(9, 'general', 'alamat_pt', 'Jl. Fisabilillah No. 8'),
(10, 'path', 'config_logo', 'resources/headerLogo.jpg'),
(11, 'general', 'kota_pt', 'Tanjungpinang'),
(12, 'general', 'provinsi_pt', 'Kepulauan Riau'),
(401, 'server', 'captcha_public_key', '$'),
(402, 'server', 'captcha_private_key', '$'),

(20, 'transkripnilai', 'id_penandatangan_transkrip', '6'),
(21, 'transkripnilai', 'nama_jabatan_transkrip', 'KETUA'),
(22, 'transkripnilai', 'nama_penandatangan_transkrip', 'ENDRI SANOPAKA, MPM'),
(23, 'transkripnilai', 'jabfung_penandatangan_transkrip', 'Lektor'),
(24, 'transkripnilai', 'nipy_penandatangan_transkrip', '125 133 050'),
(25, 'transkripnilai', 'nidn_penandatangan_transkrip', '1005118101'),
(30, 'nilaikhs', 'id_penandatangan_khs', '4'),
(31, 'nilaikhs', 'nama_jabatan_khs', 'PEMBANTU KETUA I BIDANG AKADEMIK'),
(32, 'nilaikhs', 'nama_penandatangan_khs', 'FERIZONE, MPM'),
(33, 'nilaikhs', 'jabfung_penandatangan_khs', 'LEKTOR'),
(34, 'nilaikhs', 'nipy_penandatangan_khs', '125 133 056'),
(35, 'nilahkhs', 'nidn_penandatangan_khs', '1013068702'),
(40, 'dpna', 'id_penandatangan_dpna', '4'),
(41, 'dpna', 'nama_jabatan_dpna', 'PEMBANTU KETUA I BIDANG AKADEMIK'),
(42, 'dpna', 'nama_penandatangan_dpna', 'FERIZONE, MPM'),
(43, 'dpna', 'jabfung_penandatangan_dpna', 'LEKTOR'),
(44, 'dpna', 'nipy_penandatangan_dpna', '125 133 056'),
(45, 'dpna', 'nidn_penandatangan_dpna', '1013068702'),
(55, 'spmb', 'minimal_nilai_kelulusan', '30'),
(4, 'general', 'nama_pt', 'SEKOLAH TINGGI ILMU SOSIAL DAN ILMU POLITIK RAJA HAJI'),
(100, 'report', 'header_line_1', 'SEKOLAH TINGGI ILMU SOSIAL DAN ILMU POLITIK'),
(101, 'report', 'header_line_2', 'RAJA HAJI TANJUNGPINANG'),
(102, 'report', 'header_line_3', 'JL. RAJA HAJI FISABILILLAH NO. 48 TANJUNGPINANG - KEPULAUAN RIAU'),
(103, 'report', 'header_line_4', 'TELP. (0771) 7000652 Website : http://www.stisipolrajahaji.ac.id Email : info@stisipolrajahaji.ac.id'),
(5, 'general', 'nama_pt_alias', 'STISIPOL Raja Haji Tanjungpinang'),
(6, 'general', 'default_kjur', '1'),
(60, 'krs', 'jumlah_sks_krs_setelah_cuti', '12'),
(61, 'krs', 'jumlah_sks_krs_mhs_baru', '21'),
(56, 'spmb', 'default_tahun_pendaftaran', '2018');

--
-- Table structure for table `soal`
--

CREATE TABLE `soal` (
  `idsoal` int(11) NOT NULL,
  `nama_soal` text NOT NULL,
  `date_added` date NOT NULL,
  `date_modified` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status_mhs`
--

CREATE TABLE `status_mhs` (
  `k_status` char(1) NOT NULL,
  `n_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status_mhs`
--

INSERT INTO `status_mhs` (`k_status`, `n_status`) VALUES
('A', 'AKTIF'),
('C', 'CUTI'),
('D', 'DROP-OUT / PUTUS'),
('K', 'KELUAR'),
('L', 'LULUS'),
('N', 'NON-AKTIF');
-- --------------------------------------------------------

--
-- Table structure for table `skripsi`
--
CREATE TABLE `skripsi` (
  `nim` char(20) NOT NULL,
  `judul_skripsi` varchar(255) NOT NULL,
  `abstrak` text NOT NULL,
  `iddosen_pembimbing` mediumint(9) NOT NULL,
  `iddosen_pembimbing2` mediumint(9) NOT NULL,
  `ta` year(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL,
  `file_skripsi` varchar(255) NOT NULL,
  `created_at` DATE NOT NULL,
  `updated_at` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `ta`
--

CREATE TABLE `ta` (
  `tahun` year(4) NOT NULL,
  `tahun_akademik` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tempat_spmb`
--

CREATE TABLE `tempat_spmb` (
  `idtempat_spmb` tinyint(4) NOT NULL,
  `nama_tempat` varchar(60) NOT NULL,
  `alamat` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempat_spmb`
--

INSERT INTO `tempat_spmb` (`idtempat_spmb`, `nama_tempat`, `alamat`) VALUES
(1, 'PORTAL EKAMPUS', 'http://portal.stisipolrajahaji.ac.id');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `no_transaksi` bigint(20) NOT NULL,
  `no_faktur` varchar(150) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `nim` char(20) NOT NULL,
  `commited` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal` date NOT NULL,
  `userid` int(11) NOT NULL,
  `jumlah_sks` smallint(6) NOT NULL,
  `disc` decimal(5,2) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `transaksi_api`
--

CREATE TABLE `transaksi_api` (
  `no_transaksi` bigint(20) NOT NULL,
  `no_faktur` varchar(150) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `no_formulir` int(11) NOT NULL,
  `nim` char(20) NOT NULL,
  `commited` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal` date NOT NULL,
  `userid` int(11) NOT NULL,
  `total` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_cuti`
--

CREATE TABLE `transaksi_cuti` (
  `no_transaksi` int(11) NOT NULL,
  `no_faktur` varchar(150) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `nim` char(20) NOT NULL,
  `idkombi` tinyint(4) NOT NULL,
  `dibayarkan` decimal(10,0) NOT NULL,
  `commited` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` date NOT NULL,
  `date_modified` date NOT NULL,
  `tanggal` date NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `idtransaksi_detail` bigint(20) NOT NULL,
  `no_transaksi` bigint(20) NOT NULL,
  `idkombi` tinyint(4) NOT NULL,
  `dibayarkan` decimal(10,0) NOT NULL,
  `jumlah_sks` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_sp`
--

CREATE TABLE `transaksi_sp` (
  `no_transaksi` bigint(20) NOT NULL,
  `no_faktur` varchar(150) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(4) NOT NULL,
  `idkelas` char(1) NOT NULL,
  `nim` char(20) NOT NULL,
  `tanggal` date NOT NULL,
  `sks` tinyint(4) NOT NULL,
  `dibayarkan` decimal(10,0) NOT NULL,
  `commited` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transkrip_asli`
--

CREATE TABLE `transkrip_asli` (
  `nim` char(20) NOT NULL,
  `nomor_ijazah` varchar(25) NOT NULL,
  `nomor_transkrip` varchar(20) NOT NULL,
  `predikat_kelulusan` varchar(30) NOT NULL,
  `tanggal_lulus` date NOT NULL,
  `judul_skripsi` varchar(255) NOT NULL,
  `iddosen_pembimbing` mediumint(9) NOT NULL,
  `iddosen_pembimbing2` mediumint(9) NOT NULL,
  `iddosen_ketua` mediumint(9) NOT NULL,
  `iddosen_pemket` mediumint(9) NOT NULL,
  `tahun` year(4) NOT NULL,
  `idsmt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transkrip_asli_detail`
--

CREATE TABLE `transkrip_asli_detail` (
  `idtranskrip_detail` bigint(20) NOT NULL,
  `nim` char(20) NOT NULL,
  `kmatkul` char(9) NOT NULL,
  `nmatkul` varchar(50) NOT NULL,
  `sks` char(1) NOT NULL,
  `semester` tinyint(2) NOT NULL,
  `n_kual` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_mk`
--

CREATE TABLE `tugas_mk` (
  `idtugas_mk` int(11) NOT NULL,
  `idkelas_mhs` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tujuan` mediumtext NOT NULL,
  `tanggal_buat` datetime NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `id` bigint(20) NOT NULL,
  `tweets` varchar(140) NOT NULL,
  `dt` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `tipe` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tweetscomment`
--

CREATE TABLE `tweetscomment` (
  `idcomment` bigint(20) NOT NULL,
  `id` bigint(20) NOT NULL,
  `comment` varchar(140) NOT NULL,
  `dt` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `tipe` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `idbank` smallint(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `userpassword` varchar(150) NOT NULL,
  `salt` varchar(7) NOT NULL,
  `page` enum('sa','m','pmb','d','dw','mb','mh','k','on','api') NOT NULL DEFAULT 'm',
  `group_id` smallint(6) NOT NULL,
  `kjur` tinyint(4) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `email` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '1',
  `theme` varchar(10) NOT NULL,
  `foto` varchar(70) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ipaddress` varchar(255) DEFAULT NULL,
  `logintime` datetime NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user` (`userid`, `idbank`, `username`, `userpassword`, `salt`, `page`, `group_id`, `kjur`, `nama`, `email`, `active`, `isdeleted`, `theme`, `foto`, `token`, `ipaddress`, `logintime`, `date_added`) VALUES
(1, 0, 'admin', '7c4def070993d3a2d11d7fcdbed80e98cce4bbc9ef163fb79ce650e34cfcee21', '6d6f96', 'sa', 1, 0, 'pe', 'pe@portalekampus.com', 1, 1, 'cube', 'resources/userimages/3cab9012-fotoku_santai.jpg', NULL, NULL, '2018-05-29 15:19:02', '2016-09-15 05:44:48');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `group_id` smallint(6) NOT NULL,
  `group_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`group_id`, `group_name`) VALUES
(1, 'MANAJEMEN'),
(2, 'BAAK'),
(3, 'PRODI'),
(4, 'FRONT OFFICE');
-- --------------------------------------------------------

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2018 at 05:47 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `portalekampus`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas_user`
--

CREATE TABLE `log_aktivitas_user` (
  `idlog` bigint(20) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `halaman` varchar(100) NOT NULL,
  `aktivitas` text NOT NULL,
  `date_activity` datetime NOT NULL
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Structure for view `v_datamhs`
--
DROP TABLE IF EXISTS `v_datamhs`;

CREATE VIEW `v_datamhs`  AS  SELECT `rm`.`nim` AS `nim`,`rm`.`nirm` AS `nirm`,`rm`.`no_formulir` AS `no_formulir`,`fm`.`nama_mhs` AS `nama_mhs`,`fm`.`tempat_lahir` AS `tempat_lahir`,`fm`.`tanggal_lahir` AS `tanggal_lahir`,`fm`.`jk` AS `jk`,`fm`.`alamat_kantor` AS `alamat_kantor`,`fm`.`alamat_rumah` AS `alamat_rumah`,`fm`.`telp_rumah` AS `telp_rumah`,`fm`.`telp_hp` AS `telp_hp`,`pm`.`email` AS `email`,`pm`.`userpassword` AS `userpassword`,`rm`.`tahun` AS `tahun_masuk`,`rm`.`idsmt` AS `semester_masuk`,`rm`.`iddosen_wali` AS `iddosen_wali`,`rm`.`kjur` AS `kjur`,`ps`.`nama_ps` AS `nama_ps`,`rm`.`idkonsentrasi` AS `idkonsentrasi`,`rm`.`is_merdeka` AS `is_merdeka`,`rm`.`k_status` AS `k_status`,`rm`.`perpanjang` AS `perpanjang`,`rm`.`idkelas` AS `idkelas`,`pm`.`theme` AS `theme`,`pm`.`photo_profile` AS `photo_profile` FROM (((`register_mahasiswa` `rm` join `formulir_pendaftaran` `fm`) join `program_studi` `ps`) join `profiles_mahasiswa` `pm`) WHERE ((`rm`.`no_formulir` = `fm`.`no_formulir`) and (`rm`.`kjur` = `ps`.`kjur`) and (`rm`.`no_formulir` = `pm`.`no_formulir`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_kelas_mhs`
--
DROP TABLE IF EXISTS `v_kelas_mhs`;

CREATE VIEW `v_kelas_mhs`  AS  SELECT `pp`.`idpenyelenggaraan` AS `idpenyelenggaraan`,`km`.`idpengampu_penyelenggaraan` AS `idpengampu_penyelenggaraan`,`km`.`idkelas_mhs` AS `idkelas_mhs`,`kmd`.`idkrsmatkul` AS `idkrsmatkul`,`km`.`idkelas` AS `idkelas`,`km`.`nama_kelas` AS `nama_kelas`,`km`.`hari` AS `hari`,`km`.`jam_masuk` AS `jam_masuk`,`km`.`jam_keluar` AS `jam_keluar`,`d`.`nidn` AS `nidn`,`pp`.`iddosen` AS `iddosen`,`km`.`idruangkelas` AS `idruangkelas`,CONCAT(`d`.`gelar_depan`,_latin1' ',`d`.`nama_dosen`,_latin1' ',`d`.`gelar_belakang`) AS `nama_dosen` FROM (((`kelas_mhs_detail` `kmd` join `kelas_mhs` `km`) join `pengampu_penyelenggaraan` `pp`) join `dosen` `d`) WHERE ((`km`.`idkelas_mhs` = `kmd`.`idkelas_mhs`) and (`pp`.`idpengampu_penyelenggaraan` = `km`.`idpengampu_penyelenggaraan`) and (`pp`.`iddosen` = `d`.`iddosen`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_konfirmasi_pembayaran`
--
DROP TABLE IF EXISTS `v_konfirmasi_pembayaran`;

CREATE VIEW `v_konfirmasi_pembayaran`  AS  SELECT `kp`.`idkonfirmasi` AS `idkonfirmasi`,`kp`.`no_formulir` AS `no_formulir`,`kp`.`jumlah_bayar` AS `jumlah_bayar`,`kkp`.`biaya` AS `biaya`,`kp`.`tanggal_bayar` AS `tanggal_bayar`,`kp`.`pembayaran_dari_bank` AS `pembayaran_dari_bank`,`kp`.`pemilik_rekening` AS `pemilik_rekening`,`kp`.`idkelas` AS `idkelas`,`kp`.`ta` AS `ta`,`kp`.`idsmt` AS `idsmt`,`kp`.`kjur` AS `kjur`,`kp`.`verified` AS `verified`,`kkp`.`idkombi` AS `idkombi`,`kp`.`idrekening_institusi` AS `idrekening_institusi`,CONCAT(`rk`.`bank`,_latin1' ',`rk`.`no_rekening`,_latin1'(',`k`.`nkelas`,_latin1')') AS `rekening_tujuan`,`mp`.`nama_pembayaran` AS `metode_pembayaran` FROM ((((`konfirmasi_pembayaran` `kp` join `kombi_konfirmasi_pembayaran` `kkp`) join `rekening_institusi` `rk`) join `kelas` `k`) join `metode_pembayaran` `mp`) WHERE ((`kkp`.`idkonfirmasi` = `kp`.`idkonfirmasi`) and (`rk`.`idrekening_institusi` = `kp`.`idrekening_institusi`) and (`k`.`idkelas` = `rk`.`idkelas`) and (`mp`.`idmetode_pembayaran` = `kp`.`idmetode_pembayaran`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_konversi2`
--
DROP TABLE IF EXISTS `v_konversi2`;

CREATE VIEW `v_konversi2`  AS  SELECT `dk`.`iddata_konversi` AS `iddata_konversi`,`dk`.`nama` AS `nama`,`dk`.`alamat` AS `alamat`,`dk`.`no_telp` AS `no_telp`,`dk`.`nim_asal` AS `nim_asal`,`dk`.`kode_pt_asal` AS `kode_pt_asal`,`dk`.`kjenjang` AS `kjenjang`,`js`.`njenjang` AS `njenjang`,`dk`.`kode_ps_asal` AS `kode_ps_asal`,`dk`.`kjur` AS `kjur`,`dk`.`tahun` AS `tahun`,`nk`.`kmatkul` AS `kmatkul`,`nk`.`kmatkul_asal` AS `kmatkul_asal`,`nk`.`matkul_asal` AS `matkul_asal`,`nk`.`sks_asal` AS `sks_asal`,`nk`.`idnilai_konversi` AS `idnilai_konversi`,`nk`.`n_kual` AS `n_kual`,`m`.`nmatkul` AS `nmatkul`,`m`.`sks` AS `sks`,`m`.`semester` AS `semester` FROM (((`data_konversi2` `dk` join `nilai_konversi2` `nk`) join `jenjang_studi` `js`) join `matakuliah` `m`) WHERE ((`dk`.`iddata_konversi` = `nk`.`iddata_konversi`) and (`dk`.`kjenjang` = `js`.`kjenjang`) and (`m`.`kmatkul` = `nk`.`kmatkul`)) order by `m`.`semester`,`m`.`kmatkul` ;

-- --------------------------------------------------------

--
-- Structure for view `v_krsmhs`
--
DROP TABLE IF EXISTS `v_krsmhs`;

CREATE VIEW `v_krsmhs`  AS  SELECT `km`.`idkrsmatkul` AS `idkrsmatkul`,`km`.`idpenyelenggaraan` AS `idpenyelenggaraan`,`k`.`idkrs` AS `idkrs`,`km`.`batal` AS `batal`,`k`.`tgl_krs` AS `tgl_krs`,`k`.`no_krs` AS `no_krs`,`k`.`nim` AS `nim`,`p`.`kjur` AS `kjur`,`k`.`idsmt` AS `idsmt`,`k`.`tahun` AS `tahun`,`k`.`tasmt` AS `tasmt`,`k`.`sah` AS `sah`,`k`.`tgl_disahkan` AS `tgl_disahkan`,`m`.`kmatkul` AS `kmatkul`,`m`.`nmatkul` AS `nmatkul`,`m`.`sks` AS `sks`,`m`.`semester` AS `semester`,`d`.`nidn` AS `nidn`,CONCAT(`d`.`gelar_depan`,_latin1' ',`d`.`nama_dosen`,_latin1' ',`d`.`gelar_belakang`) AS `nama_dosen`,`m`.`aktif` AS `aktif` FROM ((((`krsmatkul` `km` join `krs` `k`) join `penyelenggaraan` `p`) join `matakuliah` `m`) join `dosen` `d`) WHERE ((`km`.`idkrs` = `k`.`idkrs`) and (`km`.`idpenyelenggaraan` = `p`.`idpenyelenggaraan`) and (`p`.`kmatkul` = `m`.`kmatkul`) and (`p`.`iddosen` = `d`.`iddosen`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_nilai`
--
DROP TABLE IF EXISTS `v_nilai`;

CREATE VIEW `v_nilai`  AS  SELECT `km`.`idkrsmatkul` AS `idkrsmatkul`,`km`.`idpenyelenggaraan` AS `idpenyelenggaraan`,`k`.`nim` AS `nim`,`k`.`idsmt` AS `idsmt`,`k`.`tahun` AS `tahun`,`k`.`tasmt` AS `tasmt`,`p`.`kmatkul` AS `kmatkul`,`m`.`nmatkul` AS `nmatkul`,`m`.`sks` AS `sks`,`m`.`semester` AS `semester`,`nm`.`n_kuan` AS `n_kuan`,`nm`.`n_kual` AS `n_kual`,`d`.`nidn` AS `nidn`,CONCAT(`d`.`gelar_depan`,_latin1' ',`d`.`nama_dosen`,_latin1' ',`d`.`gelar_belakang`) AS `nama_dosen`,`m`.`aktif` AS `aktif`,`m`.`idkur` AS `idkur`,`nm`.`telah_isi_kuesioner` AS `telah_isi_kuesioner`,`nm`.`tanggal_isi_kuesioner` AS `tanggal_isi_kuesioner` FROM (((((`nilai_matakuliah` `nm` join `krsmatkul` `km`) join `krs` `k`) join `penyelenggaraan` `p`) join `dosen` `d`) join `matakuliah` `m`) WHERE ((`nm`.`idkrsmatkul` = `km`.`idkrsmatkul`) and (`km`.`idkrs` = `k`.`idkrs`) and (`km`.`idpenyelenggaraan` = `p`.`idpenyelenggaraan`) and (`d`.`iddosen` = `p`.`iddosen`) and (`p`.`kmatkul` = `m`.`kmatkul`) and (`k`.`sah` = 1) and (`km`.`batal` = 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_nilai_khs`
--
DROP TABLE IF EXISTS `v_nilai_khs`;

CREATE VIEW `v_nilai_khs`  AS  SELECT `k`.`idkrs` AS `idkrs`,`km`.`idkrsmatkul` AS `idkrsmatkul`,`km`.`idpenyelenggaraan` AS `idpenyelenggaraan`,`k`.`nim` AS `nim`,`k`.`idsmt` AS `idsmt`,`k`.`tahun` AS `tahun`,`k`.`tasmt` AS `tasmt`,`p`.`kmatkul` AS `kmatkul`,`m`.`nmatkul` AS `nmatkul`,`m`.`sks` AS `sks`,`m`.`idkonsentrasi` AS `idkonsentrasi`,`m`.`ispilihan` AS `ispilihan`,`m`.`islintas_prodi` AS `islintas_prodi`,`m`.`semester` AS `semester`,`nm`.`n_kuan` AS `n_kuan`,`nm`.`n_kual` AS `n_kual`,`nm`.`telah_isi_kuesioner` AS `telah_isi_kuesioner`,`nm`.`tanggal_isi_kuesioner` AS `tanggal_isi_kuesioner`,`d`.`iddosen` AS `iddosen`,`d`.`nidn` AS `nidn`,CONCAT(`d`.`gelar_depan`,_latin1' ',`d`.`nama_dosen`,_latin1' ',`d`.`gelar_belakang`) AS `nama_dosen`,`m`.`aktif` AS `aktif` FROM (((((`krs` `k` join `krsmatkul` `km` on((`k`.`idkrs` = `km`.`idkrs`))) join `penyelenggaraan` `p` on((`km`.`idpenyelenggaraan` = `p`.`idpenyelenggaraan`))) join `matakuliah` `m` on((`p`.`kmatkul` = `m`.`kmatkul`))) join `dosen` `d` on((`d`.`iddosen` = `p`.`iddosen`))) left join `nilai_matakuliah` `nm` on((`nm`.`idkrsmatkul` = `km`.`idkrsmatkul`))) WHERE ((`k`.`sah` = 1) and (`km`.`batal` = 0)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_nilai_komponen`
--
DROP TABLE IF EXISTS `v_nilai_komponen`;

CREATE VIEW `v_nilai_komponen`  AS  SELECT `km`.`idkrsmatkul` AS `idkrsmatkul`,`na`.`n_kuan` AS `absensi`,`nt`.`n_kuan` AS `tugas`,`nq`.`n_kuan` AS `quiz`,`nut`.`n_kuan` AS `uts`,`nu`.`n_kuan` AS `uas` FROM (((((`krsmatkul` `km` left join `nilai_absensi` `na` on((`na`.`idkrsmatkul` = `km`.`idkrsmatkul`))) left join `nilai_tugas` `nt` on((`nt`.`idkrsmatkul` = `km`.`idkrsmatkul`))) left join `nilai_quiz` `nq` on((`nq`.`idkrsmatkul` = `km`.`idkrsmatkul`))) left join `nilai_uts` `nut` on((`nut`.`idkrsmatkul` = `km`.`idkrsmatkul`))) left join `nilai_uas` `nu` on((`nu`.`idkrsmatkul` = `km`.`idkrsmatkul`))) WHERE (`km`.`batal` = 0) ;

-- --------------------------------------------------------

--
-- Structure for view `v_pengampu_penyelenggaraan`
--
DROP TABLE IF EXISTS `v_pengampu_penyelenggaraan`;

CREATE VIEW `v_pengampu_penyelenggaraan`  AS  SELECT 
                                        `pp`.`idpengampu_penyelenggaraan` AS `idpengampu_penyelenggaraan`,
                                        `p`.`idpenyelenggaraan` AS `idpenyelenggaraan`,
                                        `p`.`kjur` AS `kjur`,
                                        `p`.`idsmt` AS `idsmt`,
                                        `p`.`tahun` AS `tahun`, 
                                        `p`.`kmatkul` AS `kmatkul`,
                                        `m`.`nmatkul` AS `nmatkul`,
                                        `m`.`sks` AS `sks`,
                                        `m`.`semester` AS `semester`,
                                        `m`.`aktif` AS `aktif`,
                                        `pp`.`iddosen` AS `iddosen`,
                                        `d`.`nidn` AS `nidn`,                                        
                                        CONCAT(`d`.`gelar_depan`,_latin1' ',`d`.`nama_dosen`,_latin1' ',`d`.`gelar_belakang`) AS `nama_dosen`,
                                        `d`.`idjabatan`,
                                        `ja`.`nama_jabatan`,
                                        `pp`.`verified` AS `verified`
                                      FROM 
                                      `pengampu_penyelenggaraan` `pp` JOIN `penyelenggaraan` `p` ON (`p`.`idpenyelenggaraan` = `pp`.`idpenyelenggaraan`)
                                      JOIN `matakuliah` `m` ON `m`.`kmatkul` = `p`.`kmatkul`
                                      JOIN `dosen` `d` ON `pp`.`iddosen` = `d`.`iddosen`   
                                      JOIN `jabatan_akademik` `ja` ON `d`.`idjabatan`= `ja`.`idjabatan`;                                    

-- --------------------------------------------------------

--
-- Structure for view `v_penyelenggaraan`
--
DROP TABLE IF EXISTS `v_penyelenggaraan`;

CREATE VIEW `v_penyelenggaraan`  AS SELECT 
                                      `p`.`idpenyelenggaraan` AS `idpenyelenggaraan`,
                                      `p`.`kjur` AS `kjur`,
                                      `p`.`idsmt` AS `idsmt`,
                                      `p`.`tahun` AS `tahun`,
                                      `p`.`kmatkul` AS `kmatkul`,
                                      `m`.`idkur` AS `idkur`,
                                      `m`.`nmatkul` AS `nmatkul`,
                                      `m`.`sks` AS `sks`,
                                      `m`.`semester` AS `semester`,
                                      `m`.`aktif` AS `aktif`,
                                      `p`.`iddosen` AS `iddosen`,
                                      `d`.`nidn` AS `nidn`,
                                      CONCAT(`d`.`gelar_depan`,_latin1' ',`d`.`nama_dosen`,_latin1' ',`d`.`gelar_belakang`) AS `nama_dosen`,
                                      `d`.`idjabatan`,
                                      `ja`.`nama_jabatan`
                                    FROM 
                                    `penyelenggaraan` `p` JOIN `matakuliah` `m` ON (`p`.`kmatkul` = `m`.`kmatkul`)
                                    JOIN `dosen` `d` ON `p`.`iddosen` = `d`.`iddosen`           
                                    JOIN `jabatan_akademik` `ja` ON `d`.`idjabatan`= `ja`.`idjabatan`;                        
                                   

-- --------------------------------------------------------

--
-- Structure for view `v_transaksi`
--
DROP TABLE IF EXISTS `v_transaksi`;

CREATE VIEW `v_transaksi`  AS  SELECT `td`.`idtransaksi_detail` AS `idtransaksi_detail`,`td`.`no_transaksi` AS `no_transaksi`,`td`.`idkombi` AS `idkombi`,`k`.`nama_kombi` AS `nama_kombi`,`td`.`dibayarkan` AS `dibayarkan`,`t`.`no_faktur` AS `no_faktur`,`t`.`kjur` AS `kjur`,`t`.`tahun` AS `tahun`,`t`.`idsmt` AS `idsmt`,CONCAT(`t`.`tahun`,'',`t`.`idsmt`) AS `tasmt`,`t`.`idkelas` AS `idkelas`,`t`.`nim` AS `nim`,`t`.`no_formulir` AS `no_formulir`,`t`.`tanggal` AS `tanggal`,`t`.`commited` AS `commited` FROM ((`transaksi_detail` `td` join `transaksi` `t`) join `kombi` `k`) WHERE ((`t`.`no_transaksi` = `td`.`no_transaksi`) and (`k`.`idkombi` = `td`.`idkombi`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agama`
--
ALTER TABLE `agama`
  ADD PRIMARY KEY (`idagama`);

--
-- Indexes for table `backup_log`
--
ALTER TABLE `backup_log`
  ADD PRIMARY KEY (`backup_log_id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`idberita`),
  ADD KEY `idcat_berita` (`idcat_berita`);

--
-- Indexes for table `bipend`
--
ALTER TABLE `bipend`
  ADD PRIMARY KEY (`idbipend`),
  ADD UNIQUE KEY `no_formulir` (`no_formulir`),
  ADD UNIQUE KEY `no_faktur` (`no_faktur`);

--
-- Indexes for table `cat_berita`
--
ALTER TABLE `cat_berita`
  ADD PRIMARY KEY (`idcat_berita`);

--
-- Indexes for table `data_konversi`
--
ALTER TABLE `data_konversi`
  ADD PRIMARY KEY (`idkonversi`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `iddata_konversi` (`iddata_konversi`);

--
-- Indexes for table `data_konversi2`
--
ALTER TABLE `data_konversi2`
  ADD PRIMARY KEY (`iddata_konversi`),
  ADD KEY `kjur` (`kjur`),
  ADD KEY `idkur` (`idkur`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`iddosen`);

--
-- Indexes for table `dosen_wali`
--
ALTER TABLE `dosen_wali`
  ADD PRIMARY KEY (`iddosen_wali`),
  ADD KEY `iddosen` (`iddosen`);

--
-- Indexes for table `dulang`
--
ALTER TABLE `dulang`
  ADD PRIMARY KEY (`iddulang`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `formulir_pendaftaran`
--
ALTER TABLE `formulir_pendaftaran`
  ADD PRIMARY KEY (`no_formulir`),
  ADD KEY `formulir_pendaftaran_ibfk_1` (`kjur1`),
  ADD KEY `formulir_pendaftaran_ibfk_2` (`kjur2`);

--
-- Indexes for table `formulir_pendaftaran_temp`
--
ALTER TABLE `formulir_pendaftaran_temp`
  ADD PRIMARY KEY (`no_pendaftaran`),
  ADD KEY `formulir_pendaftaran_ibfk_1` (`kjur1`),
  ADD KEY `formulir_pendaftaran_ibfk_2` (`kjur2`),
  ADD KEY `no_formulir` (`no_formulir`) USING BTREE;

--
-- Indexes for table `forumkategori`
--
ALTER TABLE `forumkategori`
  ADD PRIMARY KEY (`idkategori`);

--
-- Indexes for table `forumposts`
--
ALTER TABLE `forumposts`
  ADD PRIMARY KEY (`idpost`);
ALTER TABLE `forumposts` ADD FULLTEXT KEY `title` (`title`,`content`);

--
-- Indexes for table `gantinim`
--
ALTER TABLE `gantinim`
  ADD PRIMARY KEY (`idgantinim`);

--
-- Indexes for table `gantinirm`
--
ALTER TABLE `gantinirm`
  ADD PRIMARY KEY (`idgantinirm`);

--
-- Indexes for table `group_access`
--
ALTER TABLE `group_access`
  ADD PRIMARY KEY (`group_id`,`module_id`);

--
-- Indexes for table `jabatan_akademik`
--
ALTER TABLE `jabatan_akademik`
  ADD PRIMARY KEY (`idjabatan`);

--
-- Indexes for table `jadwalsidang`
--
ALTER TABLE `jadwalsidang`
  ADD PRIMARY KEY (`idjadwalsidang`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `jadwal_ujian_pmb`
--
ALTER TABLE `jadwal_ujian_pmb`
  ADD PRIMARY KEY (`idjadwal_ujian`),
  ADD KEY `idruangkelas` (`idruangkelas`);

--
-- Indexes for table `jawaban`
--
ALTER TABLE `jawaban`
  ADD PRIMARY KEY (`idjawaban`),
  ADD KEY `idsoal` (`idsoal`);

--
-- Indexes for table `jawaban_ujian`
--
ALTER TABLE `jawaban_ujian`
  ADD PRIMARY KEY (`idjawabanujian`),
  ADD KEY `idsoal` (`idsoal`),
  ADD KEY `no_formulir` (`no_formulir`);

--
-- Indexes for table `jenis_pekerjaan`
--
ALTER TABLE `jenis_pekerjaan`
  ADD PRIMARY KEY (`idjp`);

--
-- Indexes for table `jenjang_studi`
--
ALTER TABLE `jenjang_studi`
  ADD PRIMARY KEY (`kjenjang`);

--
-- Indexes for table `kartu_ujian`
--
ALTER TABLE `kartu_ujian`
  ADD PRIMARY KEY (`no_formulir`);

--
-- Indexes for table `kbm`
--
ALTER TABLE `kbm`
  ADD PRIMARY KEY (`idkbm`);

--
-- Indexes for table `kbm_detail`
--
ALTER TABLE `kbm_detail`
  ADD PRIMARY KEY (`idkbm_detail`),
  ADD KEY `idkbm` (`idkbm`),
  ADD KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`idkelas`);

--
-- Indexes for table `kelas_mhs`
--
ALTER TABLE `kelas_mhs`
  ADD PRIMARY KEY (`idkelas_mhs`),
  ADD KEY `iddosen` (`idpengampu_penyelenggaraan`);

--
-- Indexes for table `kelas_mhs_detail`
--
ALTER TABLE `kelas_mhs_detail`
  ADD PRIMARY KEY (`idkelas_mhs_detail`),
  ADD UNIQUE KEY `idkrsmatkul` (`idkrsmatkul`),
  ADD KEY `idkelas_mhs` (`idkelas_mhs`);

--
-- Indexes for table `kelompok_pertanyaan`
--
ALTER TABLE `kelompok_pertanyaan`
  ADD PRIMARY KEY (`idkelompok_pertanyaan`);

--
-- Indexes for table `kombi`
--
ALTER TABLE `kombi`
  ADD PRIMARY KEY (`idkombi`),
  ADD UNIQUE KEY `nama_kombi` (`nama_kombi`);

--
-- Indexes for table `kombi_konfirmasi_pembayaran`
--
ALTER TABLE `kombi_konfirmasi_pembayaran`
  ADD KEY `idkonfirmasi` (`idkonfirmasi`);

--
-- Indexes for table `kombi_per_ta`
--
ALTER TABLE `kombi_per_ta`
  ADD PRIMARY KEY (`idkombi_per_ta`),
  ADD KEY `idkombi` (`idkombi`);

--
-- Indexes for table `konfirmasi_pembayaran`
--
ALTER TABLE `konfirmasi_pembayaran`
  ADD PRIMARY KEY (`idkonfirmasi`),
  ADD KEY `no_formulir` (`no_formulir`);

--
-- Indexes for table `konsentrasi`
--
ALTER TABLE `konsentrasi`
  ADD PRIMARY KEY (`idkonsentrasi`);

--
-- Indexes for table `kontrak_matakuliah`
--
ALTER TABLE `kontrak_matakuliah`
  ADD PRIMARY KEY (`idkelas_mhs`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`idkrs`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `krsmatkul`
--
ALTER TABLE `krsmatkul`
  ADD PRIMARY KEY (`idkrsmatkul`),
  ADD KEY `idkrs` (`idkrs`),
  ADD KEY `idpenyelenggaraan` (`idpenyelenggaraan`);

--
-- Indexes for table `kuesioner`
--
ALTER TABLE `kuesioner`
  ADD PRIMARY KEY (`idkuesioner`),
  ADD KEY `old_idkuesioner` (`old_idkuesioner`),
  ADD KEY `idkelompok_pertanyaan` (`idkelompok_pertanyaan`);

--
-- Indexes for table `kuesioner_hasil`
--
ALTER TABLE `kuesioner_hasil`
  ADD PRIMARY KEY (`idpengampu_penyelenggaraan`);

--
-- Indexes for table `kuesioner_indikator`
--
ALTER TABLE `kuesioner_indikator`
  ADD PRIMARY KEY (`idindikator`),
  ADD KEY `idkuesioner` (`idkuesioner`);

--
-- Indexes for table `kuesioner_jawaban`
--
ALTER TABLE `kuesioner_jawaban`
  ADD PRIMARY KEY (`idkuesioner_jawaban`),
  ADD KEY `idkuesioner` (`idkuesioner`),
  ADD KEY `idpengampu_penyelenggaraan` (`idpengampu_penyelenggaraan`),
  ADD KEY `idindikator` (`idindikator`);

--
-- Indexes for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`idkur`);

--
-- Indexes for table `log_master`
--
ALTER TABLE `log_master`
  ADD PRIMARY KEY (`idlog_master`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Indexes for table `log_nilai_matakuliah`
--
ALTER TABLE `log_nilai_matakuliah`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `idlog_master` (`idlog_master`,`nim`);

--
-- Indexes for table `log_transkrip_asli`
--
ALTER TABLE `log_transkrip_asli`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `idlog_master` (`idlog_master`,`nim`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`kmatkul`),
  ADD KEY `idkur` (`idkur`);

--
-- Indexes for table `matakuliah_syarat`
--
ALTER TABLE `matakuliah_syarat`
  ADD PRIMARY KEY (`idsyarat_kmatkul`),
  ADD KEY `kmatkul` (`kmatkul`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`idmetode_pembayaran`);

--
-- Indexes for table `nilai_absensi`
--
ALTER TABLE `nilai_absensi`
  ADD PRIMARY KEY (`idnilai_absensi`),
  ADD UNIQUE KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `nilai_imported`
--
ALTER TABLE `nilai_imported`
  ADD PRIMARY KEY (`idkrsmatkul`) USING BTREE,
  ADD KEY `idkelas_mhs` (`idkelas_mhs`);

--
-- Indexes for table `nilai_konversi2`
--
ALTER TABLE `nilai_konversi2`
  ADD PRIMARY KEY (`idnilai_konversi`),
  ADD KEY `iddata_konversi` (`iddata_konversi`),
  ADD KEY `kmatkul` (`kmatkul`);

--
-- Indexes for table `nilai_matakuliah`
--
ALTER TABLE `nilai_matakuliah`
  ADD PRIMARY KEY (`idnilai`),
  ADD UNIQUE KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `nilai_quiz`
--
ALTER TABLE `nilai_quiz`
  ADD PRIMARY KEY (`idnilai_quiz`),
  ADD KEY `idquiz_mk` (`idquiz_mk`),
  ADD KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  ADD PRIMARY KEY (`idnilai_tugas`),
  ADD KEY `idtugas_mk` (`idtugas_mk`),
  ADD KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `nilai_uas`
--
ALTER TABLE `nilai_uas`
  ADD PRIMARY KEY (`idnilai_uas`),
  ADD UNIQUE KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `nilai_ujian_masuk`
--
ALTER TABLE `nilai_ujian_masuk`
  ADD PRIMARY KEY (`idnilai_ujian_masuk`),
  ADD UNIQUE KEY `no_formulir` (`no_formulir`);

--
-- Indexes for table `nilai_uts`
--
ALTER TABLE `nilai_uts`
  ADD PRIMARY KEY (`idnilai_uts`),
  ADD UNIQUE KEY `idkrsmatkul` (`idkrsmatkul`);

--
-- Indexes for table `passinggrade`
--
ALTER TABLE `passinggrade`
  ADD PRIMARY KEY (`idpassing_grade`),
  ADD KEY `idjadwal_ujian` (`idjadwal_ujian`);

--
-- Indexes for table `pendaftaran_konsentrasi`
--
ALTER TABLE `pendaftaran_konsentrasi`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `pendaftaran_kampus_merdeka`
--
ALTER TABLE `pendaftaran_kampus_merdeka`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `pengampu_penyelenggaraan`
--
ALTER TABLE `pengampu_penyelenggaraan`
  ADD PRIMARY KEY (`idpengampu_penyelenggaraan`),
  ADD KEY `idpenyelenggaraan` (`idpenyelenggaraan`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`idpost`);

--
-- Indexes for table `penyelenggaraan`
--
ALTER TABLE `penyelenggaraan`
  ADD PRIMARY KEY (`idpenyelenggaraan`),
  ADD KEY `iddosen` (`iddosen`),
  ADD KEY `kmatkul` (`kmatkul`);

--
-- Indexes for table `perpanjangan_studi`
--
ALTER TABLE `perpanjangan_studi`
  ADD PRIMARY KEY (`idperpanjangan`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `peserta_ujian_pmb`
--
ALTER TABLE `peserta_ujian_pmb`
  ADD PRIMARY KEY (`idpeserta_ujian`),
  ADD KEY `no_formulir` (`no_formulir`),
  ADD KEY `idjadwal_ujian` (`idjadwal_ujian`) USING BTREE;

--
-- Indexes for table `pin`
--
ALTER TABLE `pin`
  ADD PRIMARY KEY (`no_pin`),
  ADD KEY `no_formulir` (`no_formulir`);

--
-- Indexes for table `pindahkelas`
--
ALTER TABLE `pindahkelas`
  ADD PRIMARY KEY (`idpindahkelas`),
  ADD UNIQUE KEY `no_surat` (`no_surat`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `pkrs`
--
ALTER TABLE `pkrs`
  ADD PRIMARY KEY (`idpkrs`),
  ADD KEY `nim` (`nim`),
  ADD KEY `idpenyelenggaraan` (`idpenyelenggaraan`);


--
-- Indexes for table `profiles_mahasiswa`
--
ALTER TABLE `profiles_mahasiswa`
  ADD PRIMARY KEY (`idprofile`),
  ADD UNIQUE KEY `no_formulir` (`no_formulir`);

--
-- Indexes for table `profiles_ortu`
--
ALTER TABLE `profiles_ortu`
  ADD PRIMARY KEY (`idprofile`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`kjur`),
  ADD KEY `iddosen` (`iddosen`);

--
-- Indexes for table `quiz_mk`
--
ALTER TABLE `quiz_mk`
  ADD PRIMARY KEY (`idquiz_mk`),
  ADD KEY `idkelas_mhs` (`idkelas_mhs`);

--
-- Indexes for table `register_mahasiswa`
--
ALTER TABLE `register_mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `kjur` (`kjur`),
  ADD KEY `no_formulir` (`no_formulir`);

--
-- Indexes for table `rekap_laporan_pembayaran_per_semester`
--
ALTER TABLE `rekap_laporan_pembayaran_per_semester`
  ADD PRIMARY KEY (`idrekap`),
  ADD KEY `kjur` (`kjur`),
  ADD KEY `idsmt` (`idsmt`),
  ADD KEY `tahun` (`tahun`),
  ADD KEY `nim` (`nim`),
  ADD KEY `nama_mhs` (`nama_mhs`),
  ADD KEY `tahun_masuk` (`tahun_masuk`),
  ADD KEY `semester_masuk` (`semester_masuk`),
  ADD KEY `idkelas` (`idkelas`);

--
-- Indexes for table `rekap_status_mahasiswa`
--
ALTER TABLE `rekap_status_mahasiswa`
  ADD PRIMARY KEY (`idrekap`);

--
-- Indexes for table `rekening_institusi`
--
ALTER TABLE `rekening_institusi`
  ADD PRIMARY KEY (`idrekening_institusi`);

--
-- Indexes for table `ruangkelas`
--
ALTER TABLE `ruangkelas`
  ADD PRIMARY KEY (`idruangkelas`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`idsoal`);

--
-- Indexes for table `status_mhs`
--
ALTER TABLE `status_mhs`
  ADD PRIMARY KEY (`k_status`);

--
-- Indexes for table `skripsi`
--
ALTER TABLE `skripsi`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `iddosen_pembimbing` (`iddosen_pembimbing`);

--
-- Indexes for table `ta`
--
ALTER TABLE `ta`
  ADD PRIMARY KEY (`tahun`);

--
-- Indexes for table `tempat_spmb`
--
ALTER TABLE `tempat_spmb`
  ADD PRIMARY KEY (`idtempat_spmb`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`no_transaksi`),
  ADD UNIQUE KEY `no_faktur` (`no_faktur`),
  ADD KEY `no_formulir` (`no_formulir`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `transaksi_cuti`
--
ALTER TABLE `transaksi_cuti`
  ADD PRIMARY KEY (`no_transaksi`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`idtransaksi_detail`),
  ADD KEY `no_transaksi` (`no_transaksi`),
  ADD KEY `idkombi` (`idkombi`);

--
-- Indexes for table `transaksi_sp`
--
ALTER TABLE `transaksi_sp`
  ADD PRIMARY KEY (`no_transaksi`),
  ADD UNIQUE KEY `no_faktur` (`no_faktur`),
  ADD KEY `nim` (`nim`);

--
-- Indexes for table `transkrip_asli`
--
ALTER TABLE `transkrip_asli`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `iddosen_pembimbing` (`iddosen_pembimbing`),
  ADD KEY `iddosen_ketua` (`iddosen_ketua`),
  ADD KEY `iddosen_pemket` (`iddosen_pemket`);

--
-- Indexes for table `transkrip_asli_detail`
--
ALTER TABLE `transkrip_asli_detail`
  ADD PRIMARY KEY (`idtranskrip_detail`),
  ADD KEY `nim` (`nim`),
  ADD KEY `kmatkul` (`kmatkul`);

--
-- Indexes for table `tugas_mk`
--
ALTER TABLE `tugas_mk`
  ADD PRIMARY KEY (`idtugas_mk`),
  ADD KEY `idkelas_mhs` (`idkelas_mhs`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tweetscomment`
--
ALTER TABLE `tweetscomment`
  ADD PRIMARY KEY (`idcomment`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `idpuskesmas` (`idbank`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `backup_log`
--
ALTER TABLE `backup_log`
  MODIFY `backup_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `idberita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bipend`
--
ALTER TABLE `bipend`
  MODIFY `idbipend` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_konversi`
--
ALTER TABLE `data_konversi`
  MODIFY `idkonversi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_konversi2`
--
ALTER TABLE `data_konversi2`
  MODIFY `iddata_konversi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `iddosen` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_wali`
--
ALTER TABLE `dosen_wali`
  MODIFY `iddosen_wali` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dulang`
--
ALTER TABLE `dulang`
  MODIFY `iddulang` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forumkategori`
--
ALTER TABLE `forumkategori`
  MODIFY `idkategori` smallint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forumposts`
--
ALTER TABLE `forumposts`
  MODIFY `idpost` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gantinim`
--
ALTER TABLE `gantinim`
  MODIFY `idgantinim` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gantinirm`
--
ALTER TABLE `gantinirm`
  MODIFY `idgantinirm` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwalsidang`
--
ALTER TABLE `jadwalsidang`
  MODIFY `idjadwalsidang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_ujian_pmb`
--
ALTER TABLE `jadwal_ujian_pmb`
  MODIFY `idjadwal_ujian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban`
--
ALTER TABLE `jawaban`
  MODIFY `idjawaban` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban_ujian`
--
ALTER TABLE `jawaban_ujian`
  MODIFY `idjawabanujian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kbm`
--
ALTER TABLE `kbm`
  MODIFY `idkbm` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kbm_detail`
--
ALTER TABLE `kbm_detail`
  MODIFY `idkbm_detail` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas_mhs`
--
ALTER TABLE `kelas_mhs`
  MODIFY `idkelas_mhs` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas_mhs_detail`
--
ALTER TABLE `kelas_mhs_detail`
  MODIFY `idkelas_mhs_detail` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelompok_pertanyaan`
--
ALTER TABLE `kelompok_pertanyaan`
  MODIFY `idkelompok_pertanyaan` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kombi_per_ta`
--
ALTER TABLE `kombi_per_ta`
  MODIFY `idkombi_per_ta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konfirmasi_pembayaran`
--
ALTER TABLE `konfirmasi_pembayaran`
  MODIFY `idkonfirmasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `idkrs` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krsmatkul`
--
ALTER TABLE `krsmatkul`
  MODIFY `idkrsmatkul` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuesioner`
--
ALTER TABLE `kuesioner`
  MODIFY `idkuesioner` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuesioner_indikator`
--
ALTER TABLE `kuesioner_indikator`
  MODIFY `idindikator` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuesioner_jawaban`
--
ALTER TABLE `kuesioner_jawaban`
  MODIFY `idkuesioner_jawaban` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `idkur` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_master`
--
ALTER TABLE `log_master`
  MODIFY `idlog_master` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_nilai_matakuliah`
--
ALTER TABLE `log_nilai_matakuliah`
  MODIFY `idlog` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_transkrip_asli`
--
ALTER TABLE `log_transkrip_asli`
  MODIFY `idlog` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matakuliah_syarat`
--
ALTER TABLE `matakuliah_syarat`
  MODIFY `idsyarat_kmatkul` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `idmetode_pembayaran` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_absensi`
--
ALTER TABLE `nilai_absensi`
  MODIFY `idnilai_absensi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_konversi2`
--
ALTER TABLE `nilai_konversi2`
  MODIFY `idnilai_konversi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_matakuliah`
--
ALTER TABLE `nilai_matakuliah`
  MODIFY `idnilai` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_quiz`
--
ALTER TABLE `nilai_quiz`
  MODIFY `idnilai_quiz` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  MODIFY `idnilai_tugas` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_uas`
--
ALTER TABLE `nilai_uas`
  MODIFY `idnilai_uas` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_ujian_masuk`
--
ALTER TABLE `nilai_ujian_masuk`
  MODIFY `idnilai_ujian_masuk` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_uts`
--
ALTER TABLE `nilai_uts`
  MODIFY `idnilai_uts` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passinggrade`
--
ALTER TABLE `passinggrade`
  MODIFY `idpassing_grade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengampu_penyelenggaraan`
--
ALTER TABLE `pengampu_penyelenggaraan`
  MODIFY `idpengampu_penyelenggaraan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `idpost` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penyelenggaraan`
--
ALTER TABLE `penyelenggaraan`
  MODIFY `idpenyelenggaraan` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perpanjangan_studi`
--
ALTER TABLE `perpanjangan_studi`
  MODIFY `idperpanjangan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peserta_ujian_pmb`
--
ALTER TABLE `peserta_ujian_pmb`
  MODIFY `idpeserta_ujian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pindahkelas`
--
ALTER TABLE `pindahkelas`
  MODIFY `idpindahkelas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pkrs`
--
ALTER TABLE `pkrs`
  MODIFY `idpkrs` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles_mahasiswa`
--
ALTER TABLE `profiles_mahasiswa`
  MODIFY `idprofile` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles_ortu`
--
ALTER TABLE `profiles_ortu`
  MODIFY `idprofile` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_mk`
--
ALTER TABLE `quiz_mk`
  MODIFY `idquiz_mk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_laporan_pembayaran_per_semester`
--
ALTER TABLE `rekap_laporan_pembayaran_per_semester`
  MODIFY `idrekap` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_status_mahasiswa`
--
ALTER TABLE `rekap_status_mahasiswa`
  MODIFY `idrekap` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekening_institusi`
--
ALTER TABLE `rekening_institusi`
  MODIFY `idrekening_institusi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruangkelas`
--
ALTER TABLE `ruangkelas`
  MODIFY `idruangkelas` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soal`
--
ALTER TABLE `soal`
  MODIFY `idsoal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tempat_spmb`
--
ALTER TABLE `tempat_spmb`
  MODIFY `idtempat_spmb` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `no_transaksi` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `transaksi_api`
--
ALTER TABLE `transaksi_api`
  ADD PRIMARY KEY (`no_transaksi`),
  ADD UNIQUE KEY `no_faktur` (`no_faktur`),
  ADD KEY `no_formulir` (`no_formulir`),
  ADD KEY `nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transaksi_api`
--
ALTER TABLE `transaksi_api`
  MODIFY `no_transaksi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_cuti`
--
ALTER TABLE `transaksi_cuti`
  MODIFY `no_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `idtransaksi_detail` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_sp`
--
ALTER TABLE `transaksi_sp`
  MODIFY `no_transaksi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transkrip_asli_detail`
--
ALTER TABLE `transkrip_asli_detail`
  MODIFY `idtranskrip_detail` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas_mk`
--
ALTER TABLE `tugas_mk`
  MODIFY `idtugas_mk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tweetscomment`
--
ALTER TABLE `tweetscomment`
  MODIFY `idcomment` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;


--
-- Indexes for table `log_aktivitas_user`
--
ALTER TABLE `log_aktivitas_user`
  ADD PRIMARY KEY (`idlog`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_aktivitas_user`
--
ALTER TABLE `log_aktivitas_user`
  MODIFY `idlog` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`idcat_berita`) REFERENCES `cat_berita` (`idcat_berita`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bipend`
--
ALTER TABLE `bipend`
  ADD CONSTRAINT `bipend_ibfk_1` FOREIGN KEY (`no_formulir`) REFERENCES `formulir_pendaftaran` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `data_konversi`
--
ALTER TABLE `data_konversi`
  ADD CONSTRAINT `data_konversi_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `data_konversi_ibfk_3` FOREIGN KEY (`iddata_konversi`) REFERENCES `data_konversi2` (`iddata_konversi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `data_konversi2`
--
ALTER TABLE `data_konversi2`
  ADD CONSTRAINT `data_konversi2_ibfk_8` FOREIGN KEY (`kjur`) REFERENCES `program_studi` (`kjur`) ON UPDATE CASCADE,
  ADD CONSTRAINT `data_konversi2_ibfk_9` FOREIGN KEY (`idkur`) REFERENCES `kurikulum` (`idkur`) ON UPDATE CASCADE;

--
-- Constraints for table `dosen_wali`
--
ALTER TABLE `dosen_wali`
  ADD CONSTRAINT `dosen_wali_ibfk_1` FOREIGN KEY (`iddosen`) REFERENCES `dosen` (`iddosen`);

--
-- Constraints for table `dulang`
--
ALTER TABLE `dulang`
  ADD CONSTRAINT `dulang_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `formulir_pendaftaran`
--
ALTER TABLE `formulir_pendaftaran`
  ADD CONSTRAINT `formulir_pendaftaran_ibfk_1` FOREIGN KEY (`kjur1`) REFERENCES `program_studi` (`kjur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formulir_pendaftaran_ibfk_2` FOREIGN KEY (`kjur2`) REFERENCES `program_studi` (`kjur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jawaban`
--
ALTER TABLE `jawaban`
  ADD CONSTRAINT `jawaban_ibfk_1` FOREIGN KEY (`idsoal`) REFERENCES `soal` (`idsoal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jawaban_ujian`
--
ALTER TABLE `jawaban_ujian`
  ADD CONSTRAINT `jawaban_ujian_ibfk_1` FOREIGN KEY (`idsoal`) REFERENCES `soal` (`idsoal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jawaban_ujian_ibfk_2` FOREIGN KEY (`no_formulir`) REFERENCES `kartu_ujian` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kartu_ujian`
--
ALTER TABLE `kartu_ujian`
  ADD CONSTRAINT `kartu_ujian_ibfk_1` FOREIGN KEY (`no_formulir`) REFERENCES `formulir_pendaftaran` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kbm_detail`
--
ALTER TABLE `kbm_detail`
  ADD CONSTRAINT `kbm_detail_ibfk_1` FOREIGN KEY (`idkbm`) REFERENCES `kbm` (`idkbm`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kbm_detail_ibfk_2` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_mhs`
--
ALTER TABLE `kelas_mhs`
  ADD CONSTRAINT `kelas_mhs_ibfk_1` FOREIGN KEY (`idpengampu_penyelenggaraan`) REFERENCES `pengampu_penyelenggaraan` (`idpengampu_penyelenggaraan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_mhs_detail`
--
ALTER TABLE `kelas_mhs_detail`
  ADD CONSTRAINT `kelas_mhs_detail_ibfk_1` FOREIGN KEY (`idkelas_mhs`) REFERENCES `kelas_mhs` (`idkelas_mhs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelas_mhs_detail_ibfk_2` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kombi_konfirmasi_pembayaran`
--
ALTER TABLE `kombi_konfirmasi_pembayaran`
  ADD CONSTRAINT `kombi_konfirmasi_pembayaran_ibfk_1` FOREIGN KEY (`idkonfirmasi`) REFERENCES `konfirmasi_pembayaran` (`idkonfirmasi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kombi_per_ta`
--
ALTER TABLE `kombi_per_ta`
  ADD CONSTRAINT `kombi_per_ta_ibfk_3` FOREIGN KEY (`idkombi`) REFERENCES `kombi` (`idkombi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `konfirmasi_pembayaran`
--
ALTER TABLE `konfirmasi_pembayaran`
  ADD CONSTRAINT `konfirmasi_pembayaran_ibfk_1` FOREIGN KEY (`no_formulir`) REFERENCES `formulir_pendaftaran` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kontrak_matakuliah`
--
ALTER TABLE `kontrak_matakuliah`
  ADD CONSTRAINT `kontrak_matakuliah_ibfk_1` FOREIGN KEY (`idkelas_mhs`) REFERENCES `kelas_mhs` (`idkelas_mhs`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `krsmatkul`
--
ALTER TABLE `krsmatkul`
  ADD CONSTRAINT `krsmatkul_ibfk_1` FOREIGN KEY (`idkrs`) REFERENCES `krs` (`idkrs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `krsmatkul_ibfk_2` FOREIGN KEY (`idpenyelenggaraan`) REFERENCES `penyelenggaraan` (`idpenyelenggaraan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuesioner`
--
ALTER TABLE `kuesioner`
  ADD CONSTRAINT `kuesioner_ibfk_1` FOREIGN KEY (`idkelompok_pertanyaan`) REFERENCES `kelompok_pertanyaan` (`idkelompok_pertanyaan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuesioner_hasil`
--
ALTER TABLE `kuesioner_hasil`
  ADD CONSTRAINT `kuesioner_hasil_ibfk_1` FOREIGN KEY (`idpengampu_penyelenggaraan`) REFERENCES `pengampu_penyelenggaraan` (`idpengampu_penyelenggaraan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuesioner_indikator`
--
ALTER TABLE `kuesioner_indikator`
  ADD CONSTRAINT `kuesioner_indikator_ibfk_1` FOREIGN KEY (`idkuesioner`) REFERENCES `kuesioner` (`idkuesioner`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kuesioner_jawaban`
--
ALTER TABLE `kuesioner_jawaban`
  ADD CONSTRAINT `kuesioner_jawaban_ibfk_2` FOREIGN KEY (`idkuesioner`) REFERENCES `kuesioner` (`idkuesioner`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kuesioner_jawaban_ibfk_3` FOREIGN KEY (`idpengampu_penyelenggaraan`) REFERENCES `pengampu_penyelenggaraan` (`idpengampu_penyelenggaraan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kuesioner_jawaban_ibfk_4` FOREIGN KEY (`idindikator`) REFERENCES `kuesioner_indikator` (`idindikator`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_nilai_matakuliah`
--
ALTER TABLE `log_nilai_matakuliah`
  ADD CONSTRAINT `log_nilai_matakuliah_ibfk_1` FOREIGN KEY (`idlog_master`) REFERENCES `log_master` (`idlog_master`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_transkrip_asli`
--
ALTER TABLE `log_transkrip_asli`
  ADD CONSTRAINT `log_transkrip_asli_ibfk_1` FOREIGN KEY (`idlog_master`) REFERENCES `log_master` (`idlog_master`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD CONSTRAINT `matakuliah_ibfk_1` FOREIGN KEY (`idkur`) REFERENCES `kurikulum` (`idkur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `matakuliah_syarat`
--
ALTER TABLE `matakuliah_syarat`
  ADD CONSTRAINT `matakuliah_syarat_ibfk_1` FOREIGN KEY (`kmatkul`) REFERENCES `matakuliah` (`kmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_absensi`
--
ALTER TABLE `nilai_absensi`
  ADD CONSTRAINT `nilai_absensi_ibfk_1` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_imported`
--
ALTER TABLE `nilai_imported`
  ADD CONSTRAINT `nilai_imported_ibfk_1` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_imported_ibfk_2` FOREIGN KEY (`idkelas_mhs`) REFERENCES `kelas_mhs` (`idkelas_mhs`);

--
-- Constraints for table `nilai_konversi2`
--
ALTER TABLE `nilai_konversi2`
  ADD CONSTRAINT `nilai_konversi2_ibfk_2` FOREIGN KEY (`kmatkul`) REFERENCES `matakuliah` (`kmatkul`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_konversi2_ibfk_3` FOREIGN KEY (`iddata_konversi`) REFERENCES `data_konversi2` (`iddata_konversi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_matakuliah`
--
ALTER TABLE `nilai_matakuliah`
  ADD CONSTRAINT `nilai_matakuliah_ibfk_1` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_quiz`
--
ALTER TABLE `nilai_quiz`
  ADD CONSTRAINT `nilai_quiz_ibfk_1` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_quiz_ibfk_2` FOREIGN KEY (`idquiz_mk`) REFERENCES `quiz_mk` (`idquiz_mk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  ADD CONSTRAINT `nilai_tugas_ibfk_2` FOREIGN KEY (`idtugas_mk`) REFERENCES `tugas_mk` (`idtugas_mk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_tugas_ibfk_3` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_uas`
--
ALTER TABLE `nilai_uas`
  ADD CONSTRAINT `nilai_uas_ibfk_1` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_ujian_masuk`
--
ALTER TABLE `nilai_ujian_masuk`
  ADD CONSTRAINT `nilai_ujian_masuk_ibfk_1` FOREIGN KEY (`no_formulir`) REFERENCES `formulir_pendaftaran` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_uts`
--
ALTER TABLE `nilai_uts`
  ADD CONSTRAINT `nilai_uts_ibfk_1` FOREIGN KEY (`idkrsmatkul`) REFERENCES `krsmatkul` (`idkrsmatkul`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `passinggrade`
--
ALTER TABLE `passinggrade`
  ADD CONSTRAINT `passinggrade_ibfk_1` FOREIGN KEY (`idjadwal_ujian`) REFERENCES `jadwal_ujian_pmb` (`idjadwal_ujian`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pendaftaran_konsentrasi`
--
ALTER TABLE `pendaftaran_konsentrasi`
  ADD CONSTRAINT `pendaftaran_konsentrasi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pendaftaran_kampus_merdeka`
--
ALTER TABLE `pendaftaran_kampus_merdeka`
  ADD CONSTRAINT `pendaftaran_kampus_merdeka` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengampu_penyelenggaraan`
--
ALTER TABLE `pengampu_penyelenggaraan`
  ADD CONSTRAINT `pengampu_penyelenggaraan_ibfk_1` FOREIGN KEY (`idpenyelenggaraan`) REFERENCES `penyelenggaraan` (`idpenyelenggaraan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penyelenggaraan`
--
ALTER TABLE `penyelenggaraan`
  ADD CONSTRAINT `penyelenggaraan_ibfk_1` FOREIGN KEY (`kmatkul`) REFERENCES `matakuliah` (`kmatkul`) ON UPDATE CASCADE,
  ADD CONSTRAINT `penyelenggaraan_ibfk_2` FOREIGN KEY (`iddosen`) REFERENCES `dosen` (`iddosen`) ON UPDATE CASCADE;

--
-- Constraints for table `perpanjangan_studi`
--
ALTER TABLE `perpanjangan_studi`
  ADD CONSTRAINT `perpanjangan_studi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peserta_ujian_pmb`
--
ALTER TABLE `peserta_ujian_pmb`
  ADD CONSTRAINT `peserta_ujian_pmb_ibfk_2` FOREIGN KEY (`no_formulir`) REFERENCES `pin` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peserta_ujian_pmb_ibfk_3` FOREIGN KEY (`idjadwal_ujian`) REFERENCES `jadwal_ujian_pmb` (`idjadwal_ujian`);

--
-- Constraints for table `pindahkelas`
--
ALTER TABLE `pindahkelas`
  ADD CONSTRAINT `pindahkelas_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pkrs`
--
ALTER TABLE `pkrs`
  ADD CONSTRAINT `pkrs_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pkrs_ibfk_2` FOREIGN KEY (`idpenyelenggaraan`) REFERENCES `penyelenggaraan` (`idpenyelenggaraan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles_mahasiswa`
--
ALTER TABLE `profiles_mahasiswa`
  ADD CONSTRAINT `profiles_mahasiswa_ibfk_1` FOREIGN KEY (`no_formulir`) REFERENCES `formulir_pendaftaran` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_mk`
--
ALTER TABLE `quiz_mk`
  ADD CONSTRAINT `quiz_mk_ibfk_1` FOREIGN KEY (`idkelas_mhs`) REFERENCES `kelas_mhs` (`idkelas_mhs`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `register_mahasiswa`
--
ALTER TABLE `register_mahasiswa`
  ADD CONSTRAINT `register_mahasiswa_ibfk_1` FOREIGN KEY (`kjur`) REFERENCES `program_studi` (`kjur`),
  ADD CONSTRAINT `register_mahasiswa_ibfk_2` FOREIGN KEY (`no_formulir`) REFERENCES `formulir_pendaftaran` (`no_formulir`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skripsi`
--
ALTER TABLE `skripsi`
  ADD CONSTRAINT `skripsi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `skripsi_ibfk_2` FOREIGN KEY (`iddosen_pembimbing`) REFERENCES `dosen` (`iddosen`) ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_cuti`
--
ALTER TABLE `transaksi_cuti`
  ADD CONSTRAINT `transaksi_cuti_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`idkombi`) REFERENCES `kombi` (`idkombi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_detail_ibfk_3` FOREIGN KEY (`no_transaksi`) REFERENCES `transaksi` (`no_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_sp`
--
ALTER TABLE `transaksi_sp`
  ADD CONSTRAINT `transaksi_sp_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transkrip_asli`
--
ALTER TABLE `transkrip_asli`
  ADD CONSTRAINT `transkrip_asli_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `register_mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transkrip_asli_ibfk_2` FOREIGN KEY (`iddosen_pembimbing`) REFERENCES `dosen` (`iddosen`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transkrip_asli_ibfk_3` FOREIGN KEY (`iddosen_ketua`) REFERENCES `dosen` (`iddosen`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transkrip_asli_ibfk_4` FOREIGN KEY (`iddosen_pemket`) REFERENCES `dosen` (`iddosen`) ON UPDATE CASCADE;

--
-- Constraints for table `transkrip_asli_detail`
--
ALTER TABLE `transkrip_asli_detail`
  ADD CONSTRAINT `transkrip_asli_detail_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `transkrip_asli` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tugas_mk`
--
ALTER TABLE `tugas_mk`
  ADD CONSTRAINT `tugas_mk_ibfk_1` FOREIGN KEY (`idkelas_mhs`) REFERENCES `kelas_mhs` (`idkelas_mhs`) ON DELETE CASCADE ON UPDATE CASCADE;
