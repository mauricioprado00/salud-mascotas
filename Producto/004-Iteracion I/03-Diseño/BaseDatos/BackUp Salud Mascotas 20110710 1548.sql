-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.3-m3-community


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema saludmascotas
--

CREATE DATABASE IF NOT EXISTS saludmascotas;
USE saludmascotas;

--
-- Definition of table `barrio`
--

DROP TABLE IF EXISTS `barrio`;
CREATE TABLE `barrio` (
  `idBarrio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `idLocalidad` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idBarrio`),
  KEY `FK_idLocalidad` (`idLocalidad`),
  CONSTRAINT `FK_idLocalidad` FOREIGN KEY (`idLocalidad`) REFERENCES `localidad` (`idLocalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barrio`
--

/*!40000 ALTER TABLE `barrio` DISABLE KEYS */;
/*!40000 ALTER TABLE `barrio` ENABLE KEYS */;


--
-- Definition of table `domicilio`
--

DROP TABLE IF EXISTS `domicilio`;
CREATE TABLE `domicilio` (
  `idDomicilio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calle` varchar(45) NOT NULL,
  `numero` varchar(45) NOT NULL,
  `piso` varchar(45) NOT NULL,
  `depto` varchar(45) NOT NULL,
  `idBarrio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idDomicilio`),
  KEY `FK_idBarrio` (`idBarrio`),
  CONSTRAINT `FK_idBarrio` FOREIGN KEY (`idBarrio`) REFERENCES `barrio` (`idBarrio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `domicilio`
--

/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
/*!40000 ALTER TABLE `domicilio` ENABLE KEYS */;


--
-- Definition of table `encuentro`
--

DROP TABLE IF EXISTS `encuentro`;
CREATE TABLE `encuentro` (
  `idEncuentro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fechaEncuentro` date NOT NULL,
  `horaEncuentro` time NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `idMascot` int(10) unsigned NOT NULL,
  `idBar` int(10) unsigned NOT NULL,
  `idUser` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idEncuentro`),
  KEY `FK_idMascot` (`idMascot`),
  KEY `FK_idBar` (`idBar`),
  KEY `FK_isUser` (`idUser`),
  CONSTRAINT `FK_isUser` FOREIGN KEY (`idUser`) REFERENCES `usuario` (`idUsuario`),
  CONSTRAINT `FK_idBar` FOREIGN KEY (`idBar`) REFERENCES `barrio` (`idBarrio`),
  CONSTRAINT `FK_idMascot` FOREIGN KEY (`idMascot`) REFERENCES `mascota` (`idMascota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `encuentro`
--

/*!40000 ALTER TABLE `encuentro` DISABLE KEYS */;
/*!40000 ALTER TABLE `encuentro` ENABLE KEYS */;


--
-- Definition of table `estadomascota`
--

DROP TABLE IF EXISTS `estadomascota`;
CREATE TABLE `estadomascota` (
  `idEstado` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `estadomascota`
--

/*!40000 ALTER TABLE `estadomascota` DISABLE KEYS */;
/*!40000 ALTER TABLE `estadomascota` ENABLE KEYS */;


--
-- Definition of table `estadoreclamo`
--

DROP TABLE IF EXISTS `estadoreclamo`;
CREATE TABLE `estadoreclamo` (
  `idEstadoReclamo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idEstadoReclamo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `estadoreclamo`
--

/*!40000 ALTER TABLE `estadoreclamo` DISABLE KEYS */;
/*!40000 ALTER TABLE `estadoreclamo` ENABLE KEYS */;


--
-- Definition of table `localidad`
--

DROP TABLE IF EXISTS `localidad`;
CREATE TABLE `localidad` (
  `idLocalidad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `codigoPostal` varchar(45) NOT NULL,
  `idProvincia` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idLocalidad`),
  KEY `FK_idProvincia` (`idProvincia`),
  CONSTRAINT `FK_idProvincia` FOREIGN KEY (`idProvincia`) REFERENCES `provincia` (`idProvincia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `localidad`
--

/*!40000 ALTER TABLE `localidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `localidad` ENABLE KEYS */;


--
-- Definition of table `mascota`
--

DROP TABLE IF EXISTS `mascota`;
CREATE TABLE `mascota` (
  `idMascota` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `edad` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `tamaño` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `idDomicilio` int(10) unsigned NOT NULL,
  `idEstado` int(10) unsigned NOT NULL,
  `idTipoMasc` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idMascota`),
  KEY `FK_idDomicilio` (`idDomicilio`),
  KEY `FK_idEstado` (`idEstado`),
  KEY `FK_idTipoMasc` (`idTipoMasc`),
  CONSTRAINT `FK_idTipoMasc` FOREIGN KEY (`idTipoMasc`) REFERENCES `tipomascota` (`idTipoMascota`),
  CONSTRAINT `FK_idDomicilio` FOREIGN KEY (`idDomicilio`) REFERENCES `domicilio` (`idDomicilio`),
  CONSTRAINT `FK_idEstado` FOREIGN KEY (`idEstado`) REFERENCES `estadomascota` (`idEstado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mascota`
--

/*!40000 ALTER TABLE `mascota` DISABLE KEYS */;
/*!40000 ALTER TABLE `mascota` ENABLE KEYS */;


--
-- Definition of table `pais`
--

DROP TABLE IF EXISTS `pais`;
CREATE TABLE `pais` (
  `idPais` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idPais`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pais`
--

/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;


--
-- Definition of table `perdida`
--

DROP TABLE IF EXISTS `perdida`;
CREATE TABLE `perdida` (
  `idPerdida` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fechaExtravio` date NOT NULL,
  `horaExtravio` time NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `idBarri` int(10) unsigned NOT NULL,
  `idMascota` int(10) unsigned NOT NULL,
  `idUser` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idPerdida`),
  KEY `FK_idMascota` (`idMascota`),
  KEY `FK_idBarri` (`idBarri`),
  KEY `FK_idUser` (`idUser`),
  CONSTRAINT `FK_idUser` FOREIGN KEY (`idUser`) REFERENCES `usuario` (`idUsuario`),
  CONSTRAINT `FK_idBarri` FOREIGN KEY (`idBarri`) REFERENCES `barrio` (`idBarrio`),
  CONSTRAINT `FK_idMascota` FOREIGN KEY (`idMascota`) REFERENCES `mascota` (`idMascota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `perdida`
--

/*!40000 ALTER TABLE `perdida` DISABLE KEYS */;
/*!40000 ALTER TABLE `perdida` ENABLE KEYS */;


--
-- Definition of table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
  `idPerfil` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`idPerfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `perfil`
--

/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;


--
-- Definition of table `provincia`
--

DROP TABLE IF EXISTS `provincia`;
CREATE TABLE `provincia` (
  `idProvincia` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `idPais` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idProvincia`),
  KEY `FK_idPais` (`idPais`),
  CONSTRAINT `FK_idPais` FOREIGN KEY (`idPais`) REFERENCES `pais` (`idPais`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provincia`
--

/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;


--
-- Definition of table `raza`
--

DROP TABLE IF EXISTS `raza`;
CREATE TABLE `raza` (
  `idRaza` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `idTipoMascota` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idRaza`),
  KEY `FK_idTipoMascota` (`idTipoMascota`),
  CONSTRAINT `FK_idTipoMascota` FOREIGN KEY (`idTipoMascota`) REFERENCES `tipomascota` (`idTipoMascota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `raza`
--

/*!40000 ALTER TABLE `raza` DISABLE KEYS */;
/*!40000 ALTER TABLE `raza` ENABLE KEYS */;


--
-- Definition of table `reclamomascota`
--

DROP TABLE IF EXISTS `reclamomascota`;
CREATE TABLE `reclamomascota` (
  `idReclamo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fechaReclamo` date NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `idEstadoReclamo` int(10) unsigned NOT NULL,
  `idUsuari` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idReclamo`),
  KEY `FK_idEstadoReclamo` (`idEstadoReclamo`),
  KEY `FK_idUsuari` (`idUsuari`),
  CONSTRAINT `FK_idUsuari` FOREIGN KEY (`idUsuari`) REFERENCES `usuario` (`idUsuario`),
  CONSTRAINT `FK_idEstadoReclamo` FOREIGN KEY (`idEstadoReclamo`) REFERENCES `estadoreclamo` (`idEstadoReclamo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reclamomascota`
--

/*!40000 ALTER TABLE `reclamomascota` DISABLE KEYS */;
/*!40000 ALTER TABLE `reclamomascota` ENABLE KEYS */;


--
-- Definition of table `reencuentro`
--

DROP TABLE IF EXISTS `reencuentro`;
CREATE TABLE `reencuentro` (
  `idReencuentro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fechaReencuentro` date NOT NULL,
  `horaReencuentro` time NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `idMasc` int(10) unsigned NOT NULL,
  `idUsuar` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idReencuentro`),
  KEY `FK_idMasc` (`idMasc`),
  KEY `FK_idUsuar` (`idUsuar`),
  CONSTRAINT `FK_idUsuar` FOREIGN KEY (`idUsuar`) REFERENCES `usuario` (`idUsuario`),
  CONSTRAINT `FK_idMasc` FOREIGN KEY (`idMasc`) REFERENCES `mascota` (`idMascota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reencuentro`
--

/*!40000 ALTER TABLE `reencuentro` DISABLE KEYS */;
/*!40000 ALTER TABLE `reencuentro` ENABLE KEYS */;


--
-- Definition of table `sesion`
--

DROP TABLE IF EXISTS `sesion`;
CREATE TABLE `sesion` (
  `idSesion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `idUsuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idSesion`),
  KEY `FK_idUsuario` (`idUsuario`),
  CONSTRAINT `FK_idUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sesion`
--

/*!40000 ALTER TABLE `sesion` DISABLE KEYS */;
/*!40000 ALTER TABLE `sesion` ENABLE KEYS */;


--
-- Definition of table `tipomascota`
--

DROP TABLE IF EXISTS `tipomascota`;
CREATE TABLE `tipomascota` (
  `idTipoMascota` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`idTipoMascota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipomascota`
--

/*!40000 ALTER TABLE `tipomascota` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipomascota` ENABLE KEYS */;


--
-- Definition of table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `idUsuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `tipoDocumento` varchar(45) NOT NULL,
  `nroDocumento` int(10) unsigned NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `celular` varchar(45) NOT NULL,
  `eMail` varchar(45) NOT NULL,
  `fechaAlta` datetime NOT NULL,
  `userName` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `idDomic` int(10) unsigned NOT NULL,
  `idPerfil` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `FK_idDomic` (`idDomic`),
  KEY `FK_idPerfil` (`idPerfil`),
  CONSTRAINT `FK_idPerfil` FOREIGN KEY (`idPerfil`) REFERENCES `perfil` (`idPerfil`),
  CONSTRAINT `FK_idDomic` FOREIGN KEY (`idDomic`) REFERENCES `domicilio` (`idDomicilio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
