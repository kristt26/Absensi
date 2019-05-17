# Host: localhost  (Version 5.5.5-10.1.35-MariaDB)
# Date: 2019-05-18 00:41:43
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "pegawai"
#

DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `IdPegawai` int(11) NOT NULL AUTO_INCREMENT,
  `NIP` varchar(30) NOT NULL,
  `Nama` varchar(50) NOT NULL,
  `JK` enum('Pria','Wanita') NOT NULL DEFAULT 'Pria',
  `Kontak` varchar(15) DEFAULT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Jabatan` varchar(50) NOT NULL,
  PRIMARY KEY (`IdPegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Structure for table "absen"
#

DROP TABLE IF EXISTS `absen`;
CREATE TABLE `absen` (
  `IdAbsen` int(11) NOT NULL AUTO_INCREMENT,
  `IdPegawai` int(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `JamDatang` time NOT NULL,
  `JamPulang` time DEFAULT NULL,
  `Terlambat` time NOT NULL,
  `Keterangan` enum('H','A','I','S') NOT NULL DEFAULT 'H',
  PRIMARY KEY (`IdAbsen`),
  KEY `fk_absen_pegawai_idx` (`IdPegawai`),
  CONSTRAINT `fk_absen_pegawai` FOREIGN KEY (`IdPegawai`) REFERENCES `pegawai` (`IdPegawai`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
