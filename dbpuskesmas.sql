SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `dbpuskesmas` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `dbpuskesmas` ;

-- -----------------------------------------------------
-- Table `dbpuskesmas`.`kelurahan`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `dbpuskesmas`.`kelurahan` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nama` VARCHAR(45) NULL ,
  `kec_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbpuskesmas`.`pasien`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `dbpuskesmas`.`pasien` (
  `id` INT NOT NULL ,
  `kode` VARCHAR(10) NULL ,
  `nama` VARCHAR(45) NULL ,
  `tmp_lahir` VARCHAR(30) NULL ,
  `tgl_lahir` DATE NULL ,
  `gender` CHAR(1) NULL ,
  `email` VARCHAR(50) NULL ,
  `alamat` VARCHAR(100) NULL ,
  `kelurahan_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_pasien_kelurahan1` (`kelurahan_id` ASC) ,
  CONSTRAINT `fk_pasien_kelurahan1`
    FOREIGN KEY (`kelurahan_id` )
    REFERENCES `dbpuskesmas`.`kelurahan` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbpuskesmas`.`unit_kerja`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `dbpuskesmas`.`unit_kerja` (
  `id` INT NOT NULL ,
  `nama` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbpuskesmas`.`paramedik`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `dbpuskesmas`.`paramedik` (
  `id` INT NOT NULL ,
  `nama` VARCHAR(45) NULL ,
  `gender` CHAR(1) NULL ,
  `tmp_lahir` VARCHAR(30) NULL ,
  `tgl_lahir` DATE NULL ,
  `kategori` VARCHAR(45) NULL ,
  `telepon` VARCHAR(20) NULL ,
  `alamat` VARCHAR(100) NULL ,
  `unit_kerja_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_paramedik_unit_kerja1` (`unit_kerja_id` ASC) ,
  CONSTRAINT `fk_paramedik_unit_kerja1`
    FOREIGN KEY (`unit_kerja_id` )
    REFERENCES `dbpuskesmas`.`unit_kerja` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbpuskesmas`.`periksa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `dbpuskesmas`.`periksa` (
  `id` INT NOT NULL ,
  `tanggal` DATE NULL ,
  `berat` DOUBLE NULL ,
  `tinggi` DOUBLE NULL ,
  `tensi` VARCHAR(20) NULL ,
  `keterangan` VARCHAR(100) NULL ,
  `pasien_id` INT NOT NULL ,
  `dokter_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_periksa_pasien1` (`pasien_id` ASC) ,
  INDEX `fk_periksa_paramedik1` (`dokter_id` ASC) ,
  CONSTRAINT `fk_periksa_pasien1`
    FOREIGN KEY (`pasien_id` )
    REFERENCES `dbpuskesmas`.`pasien` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_periksa_paramedik1`
    FOREIGN KEY (`dokter_id` )
    REFERENCES `dbpuskesmas`.`paramedik` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
