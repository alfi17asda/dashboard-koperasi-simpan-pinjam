-- sql/koperasi.sql
-- Buat database schema untuk dashboard koperasi.

CREATE DATABASE IF NOT EXISTS koperasi
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE koperasi;

-- =====================
-- Tabel: anggota
-- =====================
CREATE TABLE IF NOT EXISTS anggota (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(120) NOT NULL,
  no_ktp VARCHAR(30) UNIQUE NULL,
  alamat VARCHAR(255) NULL,
  no_hp VARCHAR(30) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- Tabel: simpanan
-- =====================
CREATE TABLE IF NOT EXISTS simpanan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  anggota_id INT NOT NULL,
  jenis ENUM('pokok','wajib','sukarela') NOT NULL,
  jumlah DECIMAL(14,2) NOT NULL,
  tanggal DATE NOT NULL,
  keterangan VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_simpanan_anggota FOREIGN KEY (anggota_id) REFERENCES anggota(id)
);

-- =====================
-- Tabel: pinjaman
-- =====================
CREATE TABLE IF NOT EXISTS pinjaman (
  id INT AUTO_INCREMENT PRIMARY KEY,
  anggota_id INT NOT NULL,
  jenis ENUM('konsumtif','produktif') NOT NULL,
  pokok DECIMAL(14,2) NOT NULL,
  tenor_bulan INT NOT NULL,
  bunga_percent DECIMAL(6,2) NOT NULL DEFAULT 0,
  tanggal_pengajuan DATE NOT NULL,
  status ENUM('diajukan','disetujui','ditolak','dilunasi','aktif') NOT NULL DEFAULT 'diajukan',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pinjaman_anggota FOREIGN KEY (anggota_id) REFERENCES anggota(id)
);

-- =====================
-- Tabel: kasir_transaksi
-- =====================
CREATE TABLE IF NOT EXISTS kasir_transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipe ENUM('setoran','penarikan','pengeluaran','pemasukan') NOT NULL,
  metode ENUM('tunai','transfer') NOT NULL DEFAULT 'tunai',
  nominal DECIMAL(14,2) NOT NULL,
  keterangan VARCHAR(255) NULL,
  tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  anggota_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_kasir_anggota FOREIGN KEY (anggota_id) REFERENCES anggota(id)
);

-- =====================
-- Tabel: akunting_jurnal (jurnal sederhana)
-- =====================
CREATE TABLE IF NOT EXISTS akunting_jurnal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  akun VARCHAR(80) NOT NULL,
  debet DECIMAL(14,2) NOT NULL DEFAULT 0,
  kredit DECIMAL(14,2) NOT NULL DEFAULT 0,
  keterangan VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- Tabel: transaksi_pulsa (transaksi menu transaksi)
-- =====================
CREATE TABLE IF NOT EXISTS transaksi_pulsa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jenis ENUM('pulsa_hp','pulsa_listrik','bayar_listrik','paket_data','lainnya') NOT NULL,
  pelanggan VARCHAR(120) NULL,
  nomor VARCHAR(40) NOT NULL,
  nominal DECIMAL(14,2) NOT NULL,
  biaya_admin DECIMAL(14,2) NOT NULL DEFAULT 0,
  total DECIMAL(14,2) NOT NULL,
  tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- Tabel: laporan_cache (opsional)
-- =====================
CREATE TABLE IF NOT EXISTS laporan_cache (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jenis ENUM('kas_harian','ringkasan_bulanan') NOT NULL,
  tanggal DATE NOT NULL,
  data JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes tambahan
CREATE INDEX idx_simpanan_anggota_tanggal ON simpanan(anggota_id, tanggal);
CREATE INDEX idx_pinjaman_anggota_status ON pinjaman(anggota_id, status);
CREATE INDEX idx_kasir_tanggal ON kasir_transaksi(tanggal);
CREATE INDEX idx_pulsa_tanggal ON transaksi_pulsa(tanggal);

