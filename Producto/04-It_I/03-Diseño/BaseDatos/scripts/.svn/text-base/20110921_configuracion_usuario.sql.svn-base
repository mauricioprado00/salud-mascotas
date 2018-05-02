SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_configuracion_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_configuracion_usuario` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_configuracion_usuario` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` TEXT NOT NULL ,
  `valor` TEXT NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_configuracion_usuario_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `fk_configuracion_usuario_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
