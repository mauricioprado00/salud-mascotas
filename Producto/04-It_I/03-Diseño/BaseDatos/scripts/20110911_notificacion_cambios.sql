SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_notificacion`
-- -----------------------------------------------------

alter  TABLE `app_saludmascotas`.`sm_notificacion`
add column
  `id_adopcion_oferta` INT(10) UNSIGNED NULL ,
add column
  `id_adopcion_solicitud` INT(10) UNSIGNED NULL ,
add  INDEX `fk_notificacion_id_adopcion_oferta` (`id_adopcion_oferta` ASC) ,
add  INDEX `fk_notificacion_id_adopcion_solicitud` (`id_adopcion_solicitud` ASC) ,
add
  CONSTRAINT `fk_notificacion_id_adopcion_oferta`
    FOREIGN KEY (`id_adopcion_oferta` )
    REFERENCES `app_saludmascotas`.`sm_adopcion_oferta` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
add
  CONSTRAINT `fk_notificacion_id_adopcion_solicitud`
    FOREIGN KEY (`id_adopcion_solicitud` )
    REFERENCES `app_saludmascotas`.`sm_adopcion_solicitud` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION
;

alter  TABLE `app_saludmascotas`.`sm_notificacion`
add column
  `id_adopcion_conciliacion` INT(10) UNSIGNED NULL ,
add  INDEX `fk_notificacion_id_adopcion_conciliacion` (`id_adopcion_conciliacion` ASC) ,
add
  CONSTRAINT `fk_notificacion_id_adopcion_conciliacion`
    FOREIGN KEY (`id_adopcion_conciliacion` )
    REFERENCES `app_saludmascotas`.`sm_adopcion_conciliacion` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION
;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
