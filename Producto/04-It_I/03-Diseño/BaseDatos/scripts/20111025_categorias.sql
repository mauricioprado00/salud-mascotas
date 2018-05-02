SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `app_saludmascotas` ;
USE `app_saludmascotas` ;

-- -----------------------------------------------------
-- Table `app_saludmascotas`.`sm_producto_categoria`
-- -----------------------------------------------------
insert into `app_saludmascotas`.`sm_producto_categoria` values
(	1, 'si', 'accesorios', '', null	)	,
(	2, 'si', 'indumentaria', '', null	)	,
(	3, 'si', 'juguetes', '', null	)	,
(	4, 'si', 'salud y belleza', '', null	)	,
(	5, 'si', 'alimentos', '', null	)	,
(	6, 'si', 'correas', '', 1	)	,
(	7, 'si', 'pretales', '', 1	)	,
(	8, 'si', 'cuchas y almoadones', '', 1	)	,
(	9, 'si', 'collares', '', 1	)	,
(	10, 'si', 'remeras', '', 2	)	,
(	11, 'si', 'buzos', '', 2	)	,
(	12, 'si', 'zapatos', '', 2	)	,
(	13, 'si', 'capas', '', 2	)	,
(	14, 'si', 'pelotas', '', 3	)	,
(	15, 'si', 'otros juguetes', '', 3	)	,
(	16, 'si', 'frisbee', '', 3	)	,
(	17, 'si', 'shampoos', '', 4	)	,
(	18, 'si', 'funguicidas', '', 4	)	,
(	19, 'si', 'pulguicidas', '', 4	)	,
(	20, 'si', 'otros', '', 4	)	
;








SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


