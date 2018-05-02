SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_alerta_enfermedad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_alerta_enfermedad` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_alerta_enfermedad` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activo` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `fecha_inicio` DATETIME NOT NULL ,
  `fecha_fin` DATETIME NOT NULL ,
  `texto` TEXT NOT NULL ,
  `id_spa` INT(10) UNSIGNED NOT NULL ,
  `id_domicilio` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_vacunacion_id_spa` (`id_spa` ASC) ,
  INDEX `fk_vacunacion_id_domicilio` (`id_domicilio` ASC) ,
  CONSTRAINT `fk_vacunacion_id_spa0`
    FOREIGN KEY (`id_spa` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vacunacion_id_domicilio0`
    FOREIGN KEY (`id_domicilio` )
    REFERENCES `app_saludmascotas`.`sm_domicilio` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
