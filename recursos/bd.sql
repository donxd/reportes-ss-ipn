CREATE DATABASE reportess;

USE reportess;

CREATE TABLE `reportess`.`dia_festivo` (
  `id_dia_festivo` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `descripcion` TEXT NOT NULL,
  PRIMARY KEY (`id_dia_festivo`));

INSERT INTO 
	dia_festivo
VALUES
   ( NULL, '2015-02-02', 'Día de la constitución')
,  ( NULL, '2015-03-16', 'Natalicio de Benito Juárez')
,  ( NULL, '2015-05-01', 'Día del trabajo')
,  ( NULL, '2015-09-16', 'Día de la independencia')
,  ( NULL, '2015-11-16', 'Conmemoración del inicio de la revolución mexicana')
,  ( NULL, '2015-12-25', 'Navidad');


CREATE TABLE `reportess`.`informacion` (
  `id_informacion` INT NOT NULL AUTO_INCREMENT,
  `tipo_reporte` TINYTEXT NOT NULL,
  `tipo_dias` TINYTEXT NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_cierre` DATE NOT NULL,
  `total_horas` INT(3) NOT NULL,
  `horas_dia` INT(2) NOT NULL,
  `hora_entrada` TIME NOT NULL,
  `hora_salida` TIME NOT NULL,
  `numero_reporte` INT(2) NOT NULL,
  `carrera` TINYTEXT NOT NULL,
  `nombre_alumno` TINYTEXT NOT NULL,
  `boleta` TINYTEXT NOT NULL,
  `correo` TINYTEXT NOT NULL,
  `telefono` TINYTEXT NOT NULL,
  `dependencia` TINYTEXT NOT NULL,
  `responsable_nombre` TINYTEXT NOT NULL,
  `responsable_puesto` TINYTEXT NOT NULL,
  `fecha_emision` DATE NOT NULL,
  `total_horas_acumuladas_anterior` INT(3) NOT NULL,
  `plantilla` TINYTEXT NOT NULL,
  `actividades` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id_informacion`));
