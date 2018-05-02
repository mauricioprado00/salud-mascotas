SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_cruza_conciliacion`
-- -----------------------------------------------------
ALTER TABLE `app_saludmascotas`.`sm_cruza_conciliacion`
ADD COLUMN
  `id_cruza_oferta_seleccionada` INT(10) UNSIGNED NULL ,
ADD COLUMN
  `id_cruza_solicitud_seleccionada` INT(10) UNSIGNED NULL ,
ADD
  CONSTRAINT `FK_cruza_conciliacion_id_cruza_oferta_seleccionada`
    FOREIGN KEY (`id_cruza_oferta_seleccionada` )
    REFERENCES `app_saludmascotas`.`sm_cruza_oferta` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
ADD
  CONSTRAINT `FK_cruza_conciliacion_id_cruza_solicitud_seleccionada`
    FOREIGN KEY (`id_cruza_solicitud_seleccionada` )
    REFERENCES `app_saludmascotas`.`sm_cruza_solicitud` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
