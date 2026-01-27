/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - survei
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`survei` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

/*Table structure for table `nilai_survei` */

CREATE TABLE `nilai_survei` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Penilaian',
  `petugas_id` int(11) NOT NULL COMMENT 'Merujuk ke petugas_id',
  `skor_ramah` int(11) NOT NULL COMMENT 'Nilai bintang: 1–5',
  `skor_puas` int(11) NOT NULL COMMENT 'Nilai bintang: 1–5',
  `hapus` tinyint(1) DEFAULT 0,
  `created_on` datetime NOT NULL COMMENT 'Tanggal dan waktu penilaian',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=361 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `petugas` */

CREATE TABLE `petugas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_id` int(11) NOT NULL COMMENT 'Id Pegawai dari tabel pegawai',
  `posisi_id` int(11) NOT NULL COMMENT 'ID posisi petugas',
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `hapus` tinyint(1) DEFAULT 0,
  `created_by` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` varchar(255) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `ref_posisi` */

CREATE TABLE `ref_posisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_posisi` varchar(255) NOT NULL,
  `hapus` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` varchar(255) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `v_nilai_petugas` */

DROP TABLE IF EXISTS `v_nilai_petugas`;

/*!50001 CREATE TABLE  `v_nilai_petugas`(
 `id` int(11) ,
 `petugas_id` int(11) ,
 `pegawai_id` int(11) ,
 `aktif` tinyint(1) ,
 `skor_ramah` int(11) ,
 `skor_puas` int(11) ,
 `tgl_nilai` datetime 
)*/;

/*Table structure for table `v_petugas` */

DROP TABLE IF EXISTS `v_petugas`;

/*!50001 CREATE TABLE  `v_petugas`(
 `id` int(11) ,
 `pegawai_id` int(11) ,
 `posisi_id` int(11) ,
 `aktif` tinyint(1) ,
 `hapus` tinyint(1) ,
 `created_by` varchar(255) ,
 `created_on` datetime ,
 `modified_by` varchar(255) ,
 `modified_on` datetime ,
 `nama_posisi` varchar(255) 
)*/;

/*View structure for view v_nilai_petugas */

/*!50001 DROP TABLE IF EXISTS `v_nilai_petugas` */;
/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_nilai_petugas` AS (select `n`.`id` AS `id`,`n`.`petugas_id` AS `petugas_id`,`p`.`pegawai_id` AS `pegawai_id`,`p`.`aktif` AS `aktif`,`n`.`skor_ramah` AS `skor_ramah`,`n`.`skor_puas` AS `skor_puas`,`n`.`created_on` AS `tgl_nilai` from (`nilai_survei` `n` left join `petugas` `p` on(`p`.`id` = `n`.`petugas_id`))) */;

/*View structure for view v_petugas */

/*!50001 DROP TABLE IF EXISTS `v_petugas` */;
/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_petugas` AS (select `p`.`id` AS `id`,`p`.`pegawai_id` AS `pegawai_id`,`p`.`posisi_id` AS `posisi_id`,`p`.`aktif` AS `aktif`,`p`.`hapus` AS `hapus`,`p`.`created_by` AS `created_by`,`p`.`created_on` AS `created_on`,`p`.`modified_by` AS `modified_by`,`p`.`modified_on` AS `modified_on`,`po`.`nama_posisi` AS `nama_posisi` from (`petugas` `p` left join `ref_posisi` `po` on(`p`.`posisi_id` = `po`.`id`)) order by `p`.`id`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
