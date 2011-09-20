SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_patrullaje`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_patrullaje` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_patrullaje` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NOT NULL ,
  `id_spa` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_patrullaje_usuario` (`id_spa` ASC) ,
  CONSTRAINT `fk_patrullaje_usuario`
    FOREIGN KEY (`id_spa` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_visita_barrio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_visita_barrio` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_visita_barrio` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_barrio` INT(10) UNSIGNED NOT NULL ,
  `id_patrullaje` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_visita_barrio_barrio` (`id_barrio` ASC) ,
  INDEX `fk_visita_barrio_patrullaje` (`id_patrullaje` ASC) ,
  CONSTRAINT `fk_visita_barrio_barrio`
    FOREIGN KEY (`id_barrio` )
    REFERENCES `app_saludmascotas`.`sm_barrio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_visita_barrio_patrullaje`
    FOREIGN KEY (`id_patrullaje` )
    REFERENCES `app_saludmascotas`.`sm_patrullaje` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
