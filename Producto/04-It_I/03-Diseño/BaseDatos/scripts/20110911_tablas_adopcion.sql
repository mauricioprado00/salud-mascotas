SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_adopcion_oferta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_adopcion_oferta` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_adopcion_oferta` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activo` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `destacado` ENUM('si','no') NOT NULL COMMENT 'este solo el administrador podrá cambiarlo, lo hara luego de cobrarle.\n' ,
  `descripcion` TEXT NOT NULL ,
  `notificacion_email` ENUM('si','no') NOT NULL ,
  `republicar_automaticamente` ENUM('si','no') NOT NULL ,
  `quiere_destacar` ENUM('si','no') NOT NULL COMMENT 'este esta en si cuando el usuario quiere destacar' ,
  `fecha_publicacion` DATETIME NOT NULL ,
  `fecha_expiracion` DATETIME NOT NULL ,
  `mostrar_telefono` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `id_domicilio` INT(10) UNSIGNED NULL ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_perdida_id_mascota` (`id_mascota` ASC) ,
  INDEX `FK_perdida_id_domicilio` (`id_domicilio` ASC) ,
  INDEX `FK_perdida_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_perdida_id_usuario0`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` ),
  CONSTRAINT `FK_perdida_id_domicilio0`
    FOREIGN KEY (`id_domicilio` )
    REFERENCES `app_saludmascotas`.`sm_domicilio` (`id` )
    ON DELETE RESTRICT,
  CONSTRAINT `FK_perdida_id_mascota0`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `app_saludmascotas`.`sm_mascota` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_adopcion_solicitud`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_adopcion_solicitud` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_adopcion_solicitud` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activo` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `descripcion` TEXT NOT NULL ,
  `notificacion_email` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `republicar_automaticamente` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `fecha_publicacion` DATETIME NOT NULL ,
  `fecha_expiracion` DATETIME NOT NULL ,
  `estado_mascota` ENUM('perdida','abandonada','no se') NOT NULL DEFAULT 'no se' ,
  `mostrar_telefono` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_domicilio` INT(10) UNSIGNED NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_encuentro_id_mascota` (`id_mascota` ASC) ,
  INDEX `FK_encuentro_id_domicilio` (`id_domicilio` ASC) ,
  INDEX `FK_encuentro_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_encuentro_id_usuario0`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` ),
  CONSTRAINT `FK_encuentro_id_domicilio0`
    FOREIGN KEY (`id_domicilio` )
    REFERENCES `app_saludmascotas`.`sm_domicilio` (`id` )
    ON DELETE SET NULL,
  CONSTRAINT `FK_encuentro_id_mascota0`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `app_saludmascotas`.`sm_mascota` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_adopcion_conciliacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_adopcion_conciliacion` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_adopcion_conciliacion` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activo` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `hora_adopcion_conciliacion` DATETIME NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `confirmado` ENUM('si','no') NOT NULL DEFAULT 'no' COMMENT 'indica si el dueño ya determinó que es su mascota la que reencontró.' ,
  `iniciado_por` ENUM('adopcion_oferta','adopcion_solicitud') default NULL;
  `email` VARCHAR(100) NULL ,
  `nombre` VARCHAR(100) NULL ,
  `id_adopcion_oferta` INT(10) UNSIGNED NULL COMMENT 'si esta perdida' ,
  `id_usuario` INT(10) UNSIGNED NULL COMMENT 'es el usuario que encontro la mascota, puede ser el mismo dueño u otra persona' ,
  `id_adopcion_solicitud` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_reencuentro_id_perdida` (`id_adopcion_oferta` ASC) ,
  INDEX `FK_reencuentro_id_usuario` (`id_usuario` ASC) ,
  INDEX `FK_reencuentro_id_encuentro` (`id_adopcion_solicitud` ASC) ,
  CONSTRAINT `FK_reencuentro_id_usuario0`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` ),
  CONSTRAINT `FK_reencuentro_id_adopcion_oferta`
    FOREIGN KEY (`id_adopcion_oferta` )
    REFERENCES `app_saludmascotas`.`sm_adopcion_oferta` (`id` ),
  CONSTRAINT `FK_reencuentro_id_adopcion_solicitud`
    FOREIGN KEY (`id_adopcion_solicitud` )
    REFERENCES `app_saludmascotas`.`sm_adopcion_solicitud` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
