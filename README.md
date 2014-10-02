# Generador de reportes Ver. 0.1

Esta aplicación esta pensada para que los alumnos puedan crear fácilmente sus reportes del servicio social.

***

### Entradas 

* Hora de entrada
* Horas x día
* Fecha de inicio del periodo[^1]

[^1]: Fecha del reporte

### Salidas

1. [Periodo del reporte (mes)](#periodo-del-reporte-mes)
1. [Periodo del reporte (rango de fechas)](#periodo-del-reporte-rango-de-fechas)
1. [Tabla de fechas](#tabla-de-fechas)
1. [Total de horas x mes](#total-de-horas-x-mes)
1. [Total de horas acumuladas](#total-de-horas-acumuladas)

### UXI

* Calendario para selección de la fecha de inicio
* Calendario para selección de la fecha de termino
* Cuadros de texto para las entradas

### Formatos

* Fecha periodo : `dd/mm/aaaa`[^2]
* Hora de entrada : `hh:mm`

[^2]: Verificar o personalizable (lista simbolos)

### Validaciones


* `Fecha de inicio < Fecha de termino`
* `Horas x día > 0` 

***

## Procesos de cualcular...

1. #### Periodo del reporte (mes)

	Apartir del número del mes obtener el nombre 
	`[ 1 : Enero, 2 : Febrero, ... , 12 : Diciembre ]`

1. #### Periodo del reporte (rango de fechas)
	
		ip = inicio del período
		fp = final del período

		Caso A - Principio de mes
			ip < 16 
			fp = [28, 30, 31] 		**Dependiendo del mes

		Caso B - Mitad de mes
			ip > 15
			fp = 15

									**Estar al tanto del cambio de año


1. #### Tabla de fechas

	* Quitar fines de semana
	* Identificar días festivos
	* Almacenar el calculo ????



			ndr = numero dias x reporte
			nsd = numero sábados y domingos
			df 	= días festivos
				Previamente guardados

			ndr = (fp - ip) - df - nsd
		

	* Columnas


				hd = horas x día 		[constante]
				he = hora de entrada 	[constante]
				hs = hora de salida

				hs = he + hd 			[formato de horas]


		* Fecha
		* Hora de entrada
		* Hora de salida

1. #### Total de horas x mes

		thm = Total de horas x mes
		
		thm = nhr * hd	

1. #### Total de horas acumuladas

		tha = Total de horas acumuladas
		a   = acumulado

		tha = a + thm


http://joedicastro.com/pages/markdown.html#mark7
https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet


***

## Ajustes

#### Cambio en el tipo de reporte

	principio mes -> mover calendario

***

## Conflictos

#### Selectores

	selectores jquery vs. selector js 
		jquery 	-> objetos
		js 		-> array objetos

		función jquery attr()