SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_producto`
-- -----------------------------------------------------
alter  TABLE `app_saludmascotas`.`sm_producto`
add column
  `precio` DOUBLE NULL;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_producto_especie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_producto_especie` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_producto_especie` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_producto` INT(10) UNSIGNED NOT NULL ,
  `id_especie` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_producto_especie_producto` (`id_producto` ASC) ,
  INDEX `fk_producto_especie_especie` (`id_especie` ASC) ,
  CONSTRAINT `fk_producto_especie_producto`
    FOREIGN KEY (`id_producto` )
    REFERENCES `app_saludmascotas`.`sm_producto` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_especie_especie`
    FOREIGN KEY (`id_especie` )
    REFERENCES `app_saludmascotas`.`sm_especie` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
