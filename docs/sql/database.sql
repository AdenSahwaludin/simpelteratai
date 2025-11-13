-- Membuat database
CREATE DATABASE simpelteratai;
USE simpelteratai;

-- Tabel Admin
CREATE TABLE admin (
    id_admin VARCHAR(3) PRIMARY KEY,
    nama VARCHAR(255),
    password VARCHAR(100),
    email VARCHAR(150),
    no_telpon VARCHAR(15)
);

-- Tabel Guru
CREATE TABLE guru (
    id_guru VARCHAR(3) PRIMARY KEY,
    nama VARCHAR(255),
    password VARCHAR(100),
    email VARCHAR(150),
    no_telpon VARCHAR(15)
);

-- Tabel Orang Tua
CREATE TABLE orang_tua (
    id_orang_tua VARCHAR(4) PRIMARY KEY,
    nama VARCHAR(255),
    password VARCHAR(100),
    email VARCHAR(150),
    no_telpon VARCHAR(15)
);

-- Tabel Siswa
CREATE TABLE siswa (
    id_siswa VARCHAR(4) PRIMARY KEY,
    nama VARCHAR(255),
    kelas VARCHAR(20),
    alamat VARCHAR(100),
    email VARCHAR(150),
    no_telpon VARCHAR(15),
    id_orang_tua VARCHAR(4),
    FOREIGN KEY (id_orang_tua) REFERENCES orang_tua(id_orang_tua)
);

-- Tabel Mata Pelajaran
CREATE TABLE mata_pelajaran (
    id_mata_pelajaran VARCHAR(3) PRIMARY KEY,
    nama_mapel VARCHAR(150)
);

-- Tabel Jadwal
CREATE TABLE jadwal (
    id_jadwal VARCHAR(3) PRIMARY KEY,
    id_guru VARCHAR(3),
    id_mata_pelajaran VARCHAR(3),
    ruang VARCHAR(50),
    waktu TIME,
    FOREIGN KEY (id_guru) REFERENCES guru(id_guru),
    FOREIGN KEY (id_mata_pelajaran) REFERENCES mata_pelajaran(id_mata_pelajaran)
);

-- Tabel Absensi
CREATE TABLE absensi (
    id_absensi VARCHAR(4) PRIMARY KEY,
    id_siswa VARCHAR(4),
    id_jadwal VARCHAR(3),
    tanggal DATE,
    status VARCHAR(20),
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa),
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal)
);

-- Tabel Laporan Perkembangan
CREATE TABLE laporan_perkembangan (
    id_laporan VARCHAR(3) PRIMARY KEY,
    id_siswa VARCHAR(4),
    id_mata_pelajaran VARCHAR(3),
    nilai INT,
    id_absensi VARCHAR(4),
    komentar TEXT,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa),
    FOREIGN KEY (id_mata_pelajaran) REFERENCES mata_pelajaran(id_mata_pelajaran),
    FOREIGN KEY (id_absensi) REFERENCES absensi(id_absensi)
);

-- Tabel Perilaku
CREATE TABLE perilaku (
    id_perilaku VARCHAR(3) PRIMARY KEY,
    id_siswa VARCHAR(4),
    catatan_perilaku TEXT,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa)
);

-- Tabel Pengumuman
CREATE TABLE pengumuman (
    id_pengumuman VARCHAR(3) PRIMARY KEY,
    judul VARCHAR(255),
    isi TEXT,
    tanggal DATE,
    id_admin VARCHAR(3),
    FOREIGN KEY (id_admin) REFERENCES admin(id_admin)
);

-- Tabel Komentar
CREATE TABLE komentar (
    id_komentar VARCHAR(4) PRIMARY KEY,
    id_orang_tua VARCHAR(4),
    komentar TEXT,
    FOREIGN KEY (id_orang_tua) REFERENCES orang_tua(id_orang_tua)
);
