SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_mascota`
-- -----------------------------------------------------

alter  TABLE  `app_saludmascotas`.`sm_mascota`
add column
  `estado_castracion` ENUM('no','solicitada','asignada','realizada','cancelada') not null default 'no';


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_castracion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_castracion` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_castracion` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `activo` ENUM('si','no') NOT NULL DEFAULT 'si' ,
  `fecha_solicitud` DATETIME NOT NULL ,
  `fecha_asignacion` DATETIME NULL ,
  `id_veterinario` INT(10) NULL ,
  `veterinario` VARCHAR(255) NULL ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_usuario_pa` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_castracion_id_mascota` (`id_mascota` ASC) ,
  INDEX `fk_castracion_id_usuario_pa` (`id_usuario_pa` ASC) ,
  CONSTRAINT `fk_castracion_id_mascota`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `app_saludmascotas`.`sm_mascota` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_castracion_id_usuario_pa`
    FOREIGN KEY (`id_usuario_pa` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
