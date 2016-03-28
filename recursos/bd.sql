CREATE DATABASE reportess;

USE reportess;

CREATE TABLE `reportess`.`dia_festivo` (
  `id_dia_festivo` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `descripcion` TEXT NOT NULL,
  `tipo_dia` varchar(1) NOT NULL COMMENT 'o - oficial, n - no oficial, v - vacaciones, e - escolares',
  PRIMARY KEY (`id_dia_festivo`));

INSERT INTO 
	dia_festivo
VALUES
   ( NULL, '2015-02-02', 'Día de la constitución', 'o')
,  ( NULL, '2015-03-16', 'Natalicio de Benito Juárez', 'o')
,  ( NULL, '2015-05-01', 'Día del trabajo', 'o')
,  ( NULL, '2015-09-16', 'Día de la independencia', 'o')
,  ( NULL, '2015-11-16', 'Conmemoración del inicio de la revolución mexicana', 'o')
,  ( NULL, '2015-12-25', 'Navidad'), 'o';

INSERT INTO 
  dia_festivo
VALUES
   ( NULL, '2016-01-01', 'Año nuevo', 'o')
,  ( NULL, '2016-02-01', 'Día de la Constitución', 'o')
,  ( NULL, '2016-03-21', 'Natalicio de Benito Juárez', 'o')
,  ( NULL, '2016-05-01', 'Día del trabajo', 'o')
,  ( NULL, '2016-09-16', 'Día de la Independencia de México', 'o')
,  ( NULL, '2016-11-21', 'Día de la Revolución Mexicana', 'o')
,  ( NULL, '2016-12-25', 'Navidad', 'o');

INSERT INTO 
  dia_festivo 
VALUES 
   ( NULL, '2016-03-24', 'Semana santa', 'n' )
,  ( NULL, '2016-03-25', 'Semana santa', 'n' )
,  ( NULL, '2016-03-22', 'Semana santa', 'v' )
,  ( NULL, '2016-03-23', 'Semana santa', 'v' )
,  ( NULL, '2016-03-24', 'Semana santa', 'v' )
,  ( NULL, '2016-03-25', 'Semana santa', 'v' )
,  ( NULL, '2016-05-05', 'Semana santa', 'e' )
,  ( NULL, '2016-11-02', 'Día de muertos', 'e' );


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
