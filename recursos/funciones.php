<?

require_once("log.php");

class funciones {

	private $log;

	// $tipo_reporte
	// 		pm -> principio de mes
	// 		mm -> mitad de mes

	function periodo_mes ($tipo_reporte, $fecha){

		// /**/ $this->log = new log();
		// /**/ $this->log->registrar(1,"periodo_mes ($tipo_reporte, $fecha)");

		$meses = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
						"Julio", "Agosto", "Septiembre", "Octubre","Noviembre", "Diciembre");

		$numero_mes = intval( date("m", strtotime($fecha) ) );
		$mes_reporte = "Error";

		if ($numero_mes < 13){
			$mes_reporte = $meses[$numero_mes-1];
			if ( !strcmp($tipo_reporte, 'mm') ){
				$mes_reporte .= "-".$meses[$numero_mes];
			}
		}
		return $mes_reporte;
	} //periodo_mes

	function periodo_reporte ($tipo_reporte, $fecha){

		// /**/ $this->log = new log();
		// /**/ $this->log->registrar(1,"periodo_reporte ($tipo_reporte, $fecha)");

		$numero_mes = intval( date("m", strtotime($fecha) ) );
		$dia     = date("d", strtotime($fecha) );
		$mes     = date("m", strtotime($fecha) );
		$anio    = date("Y", strtotime($fecha) );
		$periodo = array();

		$separador = '-';

		$periodo[0] = $anio.$separador.$mes.$separador.$dia;
		if ( !strcmp($tipo_reporte, 'pm') ){
			//pm
			$limite_mes = date("t", strtotime($fecha) );
			$periodo[1] = $anio.$separador.$mes.$separador.$limite_mes;
		} else {
			//mm
			$nuevo_mes = ($mes < 12) ? $mes+1 : 1;
			$nuevo_mes = ($nuevo_mes < 10) ? '0'.$nuevo_mes : "$nuevo_mes";

			$periodo[1] = $anio.$separador.$nuevo_mes.$separador.'15';
		}
		return $periodo;
	} //periodo_reporte

	function periodo_to_string ($periodo){
		$cadena = '"'.$periodo[0].'" , "'.$periodo[1].'"';
		return $cadena;
	} //periodo_to_string

	function numero_dias_reporte ($tipo_reporte, $fecha_inicio, $fecha_cierre){
		// /**/ $this->log = new log();
		// /**/ $this->log->registrar(1,"numero_dias_reporte ($tipo_reporte, $fecha_inicio, $fecha_cierre)");

		// ndr = numero dias x reporte
		// nsd = numero sábados y domingos
		// df  = días festivos
		//     Previamente guardados

		// ndr = (fp - ip) - df - nsd
		$numero_dias_reporte;
		if ( !strcmp($tipo_reporte, 'pm') ){ 
			$dias_mes = date("d", strtotime($fecha_cierre) );
			//pm
			$dias_recorrer = date("d", strtotime($fecha_inicio) ) - 1;
			$numero_dias_reporte = date("d", strtotime($fecha_cierre) ) - $dias_recorrer;
			$dia_semana = date("N", strtotime($fecha_inicio) ) - 1 ;

			$dias_ajuste = 0;
			if ($dia_semana != 0){
				$dias_ajuste = 7 - $dia_semana;
			}
			$numero_dias_reporte -= $dias_ajuste;

			$semanas = intval($numero_dias_reporte / 7);
			$dias_residuo = $numero_dias_reporte % 7;

			$numero_dias_reporte -= ( $semanas * 2 );

			// /**/ $this->log->registrar(1,"\n--------\ndia semana : $dias_recorrer\ndias mes : $dias_mes\ndia semana inicio : $dia_semana\nsemanas : $semanas\nresiduo : $dias_residuo\nresultado 1 : $numero_dias_reporte\n--------");

			if ($dia_semana < 5 && $dia_semana != 0){
				$numero_dias_reporte += (5-$dia_semana);
			}
			if ($dias_residuo > 5){
				$numero_dias_reporte -= ($dias_residuo-5);	
			}

		} else {
			//mm
			$periodo = self::periodo_reporte('pm',$fecha_inicio);
			$numero_dias_reporte = self::numero_dias_reporte('pm', $fecha_inicio, $periodo[1]);
			$numero_dias_reporte += self::numero_dias_reporte('pm', '01'.date("-m-Y", strtotime($fecha_cierre) ), $fecha_cierre);
			// /**/ $this->log->registrar(1,"\n*********\n$fecha_inicio\n".$periodo[1]."\n$fecha_cierre\nres : $numero_dias_reporte\n*********");
		}
		return $numero_dias_reporte;
	} //numero_dias_reporte
} //funciones
?>