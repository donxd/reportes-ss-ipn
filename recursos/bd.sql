CREATE DATABASE reportess;

USE reportess;

CREATE TABLE `reportess`.`dia_festivo` (
  `id_dia_festivo` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `descripcion` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_dia_festivo`));

INSERT INTO 
	dia_festivo
VALUES
   ( NULL, '02-02-2015', 'Día de la constitución')
,  ( NULL, '16-03-2015', 'Natalicio de Benito Juárez')
,  ( NULL, '01-05-2015', 'Día del trabajo')
,  ( NULL, '16-09-2015', 'Día de la independencia')
,  ( NULL, '16-11-2015', 'Conmemoración del inicio de la revolución mexicana')
,  ( NULL, '25-12-2015', 'Navidad');

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
  `actividades` TINYTEXT NOT NULL,
  PRIMARY KEY (`id_informacion`));
