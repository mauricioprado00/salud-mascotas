SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_producto_categoria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_producto_categoria` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_producto_categoria` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activa` ENUM('si','no') NULL DEFAULT 'no' ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` TEXT NULL ,
  `id_parent` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_producto_categoria_pc` (`id_parent` ASC) ,
  CONSTRAINT `fk_producto_categoria_pc`
    FOREIGN KEY (`id_parent` )
    REFERENCES `app_saludmascotas`.`sm_producto_categoria` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_producto_imagen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_producto_imagen` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_producto_imagen` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activa` ENUM('si','no') NULL DEFAULT 'no' ,
  `ruta` TEXT NOT NULL ,
  `fecha_carga` DATETIME NULL ,
  `id_producto` INT(10) UNSIGNED NULL ,
  `id_usuario` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_producto_imagen_producto` (`id_producto` ASC) ,
  INDEX `fk_producto_imagen_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `fk_producto_imagen_producto`
    FOREIGN KEY (`id_producto` )
    REFERENCES `app_saludmascotas`.`sm_producto` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_imagen_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_producto` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_producto` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `activo` ENUM('si','no') NULL DEFAULT 'no' ,
  `nombre` VARCHAR(100) NOT NULL ,
  `descripcion` TEXT NULL ,
  `fecha_alta` DATETIME NULL ,
  `fecha_expiracion` DATETIME NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  `id_categoria` INT(10) UNSIGNED NOT NULL ,
  `id_imagen_principal` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_sm_producto_producto_categoria` (`id_categoria` ASC) ,
  INDEX `fk_sm_producto_usuario` (`id_usuario` ASC) ,
  INDEX `fk_sm_producto_producto_imagen` (`id_imagen_principal` ASC) ,
  CONSTRAINT `fk_sm_producto_producto_categoria`
    FOREIGN KEY (`id_categoria` )
    REFERENCES `app_saludmascotas`.`sm_producto_categoria` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sm_producto_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sm_producto_producto_imagen`
    FOREIGN KEY (`id_imagen_principal` )
    REFERENCES `app_saludmascotas`.`sm_producto_imagen` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_consulta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_consulta` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_consulta` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_consulta` INT(10) UNSIGNED NULL ,
  `activa` ENUM('si','no') NULL DEFAULT 'no' ,
  `detalle` TEXT NOT NULL ,
  `fecha_alta` DATETIME NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  `id_producto` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_consulta_producto` (`id_producto` ASC) ,
  INDEX `fk_consulta_usuario` (`id_usuario` ASC) ,
  INDEX `fk_consulta_consulta` (`id_consulta` ASC) ,
  CONSTRAINT `fk_consulta_producto`
    FOREIGN KEY (`id_producto` )
    REFERENCES `app_saludmascotas`.`sm_producto` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consulta_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_consulta_consulta`
    FOREIGN KEY (`id_consulta` )
    REFERENCES `app_saludmascotas`.`sm_consulta` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_valoracion_consulta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `app_saludmascotas`.`sm_valoracion_consulta` ;

CREATE  TABLE IF NOT EXISTS `app_saludmascotas`.`sm_valoracion_consulta` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `puntaje` INT NOT NULL ,
  `fecha` DATETIME NOT NULL ,
  `id_consulta` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_valoracion_consulta_consulta` (`id_consulta` ASC) ,
  INDEX `fk_valoracion_consulta_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `fk_valoracion_consulta_consulta`
    FOREIGN KEY (`id_consulta` )
    REFERENCES `app_saludmascotas`.`sm_consulta` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_valoracion_consulta_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `app_saludmascotas`.`sm_usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
