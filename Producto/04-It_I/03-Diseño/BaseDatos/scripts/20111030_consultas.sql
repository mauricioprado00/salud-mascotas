SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_imagen_perfil`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_imagen_perfil` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_imagen_perfil` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ruta` TEXT NOT NULL ,
  `fecha_carga` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_usuario`
-- -----------------------------------------------------
ALTER TABLE `app_saludmascotas`.`sm_usuario`
add column
  `id_imagen_perfil` INT(10) UNSIGNED NULL,
add CONSTRAINT
	`fk_usuario_imagen_perfil`
    FOREIGN KEY (`id_imagen_perfil` )
    REFERENCES `app_saludmascotas`.`sm_imagen_perfil` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION  ;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_consulta`
-- -----------------------------------------------------
ALTER TABLE `app_saludmascotas`.`sm_consulta`
change column `id_usuario`
  `id_usuario` INT(10) UNSIGNED NULL ,
add column
  `nombre` VARCHAR(100) NULL ,
add column
  `email` VARCHAR(100) NULL ,
add column
  `id_imagen_perfil` INT(10) UNSIGNED NULL,
add CONSTRAINT
   `fk_consulta_imagen_perfil`
    FOREIGN KEY (`id_imagen_perfil` )
    REFERENCES `app_saludmascotas`.`sm_imagen_perfil` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
  ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
