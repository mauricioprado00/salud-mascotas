SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_etiqueta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_etiqueta` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_etiqueta` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(255) NOT NULL ,
  `id_parent` INT(10) UNSIGNED NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_etiqueta_etiqueta` (`id_parent` ASC) ,
  INDEX `fk_etiqueta_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `fk_etiqueta_etiqueta`
    FOREIGN KEY (`id_parent` )
    REFERENCES `app_saludmascotas`.`sm_etiqueta` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_etiqueta_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_etiqueta_mascota`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_etiqueta_mascota` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_etiqueta_mascota` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_etiqueta` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_etiqueta_mascota_etiqueta` (`id_etiqueta` ASC) ,
  INDEX `fk_etiqueta_mascota_mascota` (`id_mascota` ASC) ,
  CONSTRAINT `fk_etiqueta_mascota_etiqueta`
    FOREIGN KEY (`id_etiqueta` )
    REFERENCES `app_saludmascotas`.`sm_etiqueta` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_etiqueta_mascota_mascota`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `app_saludmascotas`.`sm_mascota` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
