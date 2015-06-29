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