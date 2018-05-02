SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_consulta`
-- -----------------------------------------------------
alter TABLE `app_saludmascotas`.`sm_consulta`
add column
  `visibilidad` ENUM('publica','privada') NULL DEFAULT 'publica' ,
add column
  `permiso_respuesta` ENUM('publico','privado','veterinario') NULL DEFAULT 'publico';


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_consulta_destinatario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_consulta_destinatario` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_consulta_destinatario` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_consulta` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_consulta_destinatario_usuario` (`id_usuario` ASC) ,
  INDEX `fk_consulta_destinatario_comentario` (`id_consulta` ASC) ,
  CONSTRAINT `fk_consulta_destinatario_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consulta_destinatario_comentario`
    FOREIGN KEY (`id_consulta` )
    REFERENCES `app_saludmascotas`.`sm_consulta` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
