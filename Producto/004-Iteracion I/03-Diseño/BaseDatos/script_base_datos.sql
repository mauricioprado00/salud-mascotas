SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `saludmascotas` ;
USE `saludmascotas` ;

-- -----------------------------------------------------
-- Table `saludmascotas`.`pais`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`pais` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`provincia`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`provincia` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `id_pais` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_pais` (`id_pais` ASC) ,
  CONSTRAINT `FK_id_pais`
    FOREIGN KEY (`id_pais` )
    REFERENCES `saludmascotas`.`pais` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`localidad`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`localidad` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `codigo_postal` VARCHAR(45) NOT NULL ,
  `id_provincia` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_provincia` (`id_provincia` ASC) ,
  CONSTRAINT `FK_id_provincia`
    FOREIGN KEY (`id_provincia` )
    REFERENCES `saludmascotas`.`provincia` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`barrio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`barrio` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `id_localidad` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_localidad` (`id_localidad` ASC) ,
  CONSTRAINT `FK_id_localidad`
    FOREIGN KEY (`id_localidad` )
    REFERENCES `saludmascotas`.`localidad` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`domicilio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`domicilio` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `calle` VARCHAR(45) NOT NULL ,
  `numero` VARCHAR(45) NOT NULL ,
  `piso` VARCHAR(45) NOT NULL ,
  `depto` VARCHAR(45) NOT NULL ,
  `id_barrio` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_barrio` (`id_barrio` ASC) ,
  CONSTRAINT `FK_id_barrio`
    FOREIGN KEY (`id_barrio` )
    REFERENCES `saludmascotas`.`barrio` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`perfil`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`perfil` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`usuario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`usuario` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `apellido` VARCHAR(45) NOT NULL ,
  `tipo_documento` VARCHAR(45) NOT NULL ,
  `nro_documento` INT(10) UNSIGNED NOT NULL ,
  `telefono` VARCHAR(45) NOT NULL ,
  `celular` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `fecha_alta` DATETIME NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `id_domicilio` INT(10) UNSIGNED NULL ,
  `id_perfil` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_domicilio` (`id_domicilio` ASC) ,
  INDEX `FK_id_perfil` (`id_perfil` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) ,
  CONSTRAINT `FK_id_perfil`
    FOREIGN KEY (`id_perfil` )
    REFERENCES `saludmascotas`.`perfil` (`id` ),
  CONSTRAINT `FK_id_domicilio`
    FOREIGN KEY (`id_domicilio` )
    REFERENCES `saludmascotas`.`domicilio` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`especie`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`especie` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`raza`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`raza` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `id_especie` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_especie` (`id_especie` ASC) ,
  CONSTRAINT `FK_id_especie`
    FOREIGN KEY (`id_especie` )
    REFERENCES `saludmascotas`.`especie` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`estadomascota`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`estadomascota` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`mascota`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`mascota` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `edad` VARCHAR(45) NOT NULL ,
  `color` VARCHAR(45) NOT NULL ,
  `tamaño` VARCHAR(45) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `id_domicilio` INT(10) UNSIGNED NULL ,
  `id_estadomascota` INT(10) UNSIGNED NOT NULL ,
  `id_raza` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_domicilio` (`id_domicilio` ASC) ,
  INDEX `FK_id_estadomascota` (`id_estadomascota` ASC) ,
  INDEX `FK_id_raza` (`id_raza` ASC) ,
  INDEX `FK_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_id_raza`
    FOREIGN KEY (`id_raza` )
    REFERENCES `saludmascotas`.`raza` (`id` ),
  CONSTRAINT `FK_id_domicilio`
    FOREIGN KEY (`id_domicilio` )
    REFERENCES `saludmascotas`.`domicilio` (`id` ),
  CONSTRAINT `FK_id_estadomascota`
    FOREIGN KEY (`id_estadomascota` )
    REFERENCES `saludmascotas`.`estadomascota` (`id` ),
  CONSTRAINT `FK_id_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `saludmascotas`.`usuario` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`encuentro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`encuentro` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fecha_encuentro` DATE NOT NULL ,
  `hora_encuentro` TIME NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_barrio` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_mascota` (`id_mascota` ASC) ,
  INDEX `FK_id_barrio` (`id_barrio` ASC) ,
  INDEX `FK_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_id_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `saludmascotas`.`usuario` (`id` ),
  CONSTRAINT `FK_id_barrio`
    FOREIGN KEY (`id_barrio` )
    REFERENCES `saludmascotas`.`barrio` (`id` ),
  CONSTRAINT `FK_id_mascota`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `saludmascotas`.`mascota` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`estadoreclamo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`estadoreclamo` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`perdida`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`perdida` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `hora_extravio` DATETIME NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `id_barrio` INT(10) UNSIGNED NOT NULL ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_mascota` (`id_mascota` ASC) ,
  INDEX `FK_id_barrio` (`id_barrio` ASC) ,
  INDEX `FK_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_id_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `saludmascotas`.`usuario` (`id` ),
  CONSTRAINT `FK_id_barrio`
    FOREIGN KEY (`id_barrio` )
    REFERENCES `saludmascotas`.`barrio` (`id` ),
  CONSTRAINT `FK_id_mascota`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `saludmascotas`.`mascota` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`reclamomascota`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`reclamomascota` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fecha_reclamo` DATE NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `id_estadoreclamo` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_estadoreclamo` (`id_estadoreclamo` ASC) ,
  INDEX `FK_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_id_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `saludmascotas`.`usuario` (`id` ),
  CONSTRAINT `FK_id_estado_reclamo`
    FOREIGN KEY (`id_estadoreclamo` )
    REFERENCES `saludmascotas`.`estadoreclamo` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`reencuentro`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`reencuentro` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `hora_reencuentro` DATETIME NOT NULL ,
  `hora_reencuentro` TIME NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `id_mascota` INT(10) UNSIGNED NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_mascota` (`id_mascota` ASC) ,
  INDEX `FK_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_id_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `saludmascotas`.`usuario` (`id` ),
  CONSTRAINT `FK_id_mascota`
    FOREIGN KEY (`id_mascota` )
    REFERENCES `saludmascotas`.`mascota` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`sesion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`sesion` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NOT NULL ,
  `hora_inicio` TIME NOT NULL ,
  `hora_fin` TIME NOT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `FK_id_usuario` (`id_usuario` ASC) ,
  CONSTRAINT `FK_id_usuario`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `saludmascotas`.`usuario` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`administrador`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`administrador` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `apellido` VARCHAR(45) NOT NULL ,
  `telefono` VARCHAR(45) NOT NULL ,
  `celular` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `saludmascotas`.`config`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `saludmascotas`.`config` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(255) NOT NULL ,
  `valor` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
