<?

require_once("log.php");
require_once("conexion.php");

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
				// $tipo_reporte == 'mm'
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
			$nuevo_mes = sprintf("%02d",$nuevo_mes);
			// $nuevo_mes = ($nuevo_mes < 10) ? '0'.$nuevo_mes : "$nuevo_mes";

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
			//pm
			// *** $dias_mes = date("d", strtotime($fecha_cierre) );
			$dias_recorrer = date("d", strtotime($fecha_inicio) ) - 1;
			$numero_dias_reporte = date("d", strtotime($fecha_cierre) ) - $dias_recorrer;
			$dia_semana = date("N", strtotime($fecha_inicio) ) - 1;

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

	function dias_periodo ($tipo_dias, $fecha_inicio, $fecha_cierre){
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1,"dias_periodo ($tipo_dias, $fecha_inicio, $fecha_cierre)");

		$dias = array();
		$dia_inicio = intval( date("d", strtotime($fecha_inicio) ) );
		/**/ $this->log->registrar(1,"valores ??? dia_limite : ".intval( date("d", strtotime($fecha_cierre) ) )." - ".($dia_inicio - 1)." = ".(intval( date("d", strtotime($fecha_cierre) ) ) - ($dia_inicio - 1)) );
		
		$numero_dias_periodo = intval( date("d", strtotime($fecha_cierre) ) ) - ($dia_inicio - 1);
		$dia_semana = intval( date("N", strtotime($fecha_inicio) ) )- 1;

		$mes_anio = date("-m-Y", strtotime($fecha_inicio) );

		// /**/ $this->log->registrar(1,"\ndia_inicio : $dia_inicio\nnumero_dias_periodo : $numero_dias_periodo\ndia_semana : $dia_semana\nmes_anio : $mes_anio");

		for ($i = 0; $i < $numero_dias_periodo; $i++, $dia_semana++){
			// /**/ $this->log->registrar(1,"i : $i -> ".sprintf("%02d",$dia_inicio + $i)."\ndia_semana : $dia_semana");
			switch ($tipo_dias){
				case 'es': //entre semana
					if ($dia_semana < 5){
						/**/ $this->log->registrar(1,"a[".sprintf("%02d",$dia_inicio + $i).$mes_anio."]");
						array_push($dias, array(sprintf("%02d",$dia_inicio + $i).$mes_anio, 0) );
					}
					break;
				case 'fs': //fines de semana
					if ($dia_semana > 4){
						/**/ $this->log->registrar(1,"b[".sprintf("%02d",$dia_inicio + $i).$mes_anio."]");
						array_push($dias, array(sprintf("%02d",$dia_inicio + $i).$mes_anio, 0) );
					}
					break;
			}
			if ($dia_semana == 6){
				$dia_semana = -1;
			}
		} //for
		/**/ $this->log->registrar(1, "dias_periodo : dias [".count($dias)."]");
		return $dias;
	} //dias_periodo

	function dias_reporte ($tipo_reporte, $tipo_dias, $fecha_inicio, $fecha_cierre, $numero_dias_reporte){
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1,"dias_reporte ($tipo_reporte, $tipo_dias, $fecha_inicio, $fecha_cierre, $numero_dias_reporte)");

		$dias = array();
		$dia_semana = date("N", strtotime($fecha_inicio) ) - 1;
		$dias_festivos = self::get_dias_festivos ($fecha_inicio, $fecha_cierre);
		
		if ( !strcmp($tipo_reporte, 'pm') ){ 
			//pm
			$dias = self::dias_periodo($tipo_dias, $fecha_inicio, $fecha_cierre);
		} else {
			//mm
			$periodo = self::periodo_reporte('pm',$fecha_inicio);
			$dias = self::dias_periodo($tipo_dias, $fecha_inicio, $periodo[1]);
			// array_push($dias, self::dias_periodo($tipo_dias, '01'.date("-m-Y", strtotime($fecha_cierre) ), $fecha_cierre) );
			$dias = array_merge($dias, self::dias_periodo($tipo_dias, '01'.date("-m-Y", strtotime($fecha_cierre) ), $fecha_cierre) );
		}

		for ($i = 0; $i < count($dias_festivos); $i++){
			for ($j = 0; $j < count($dias); $j++){
				/**/ $this->log->registrar(1,"comparando festivos : ".$dias_festivos[$i]." - ".$dias[$j][0]);
				if ( !strcmp($dias_festivos[$i], $dias[$j][0]) ){
					$dias[$j][1] = 1;
				} //if
			} //for
		} //for
		/**/ $this->log->registrar(1,"dias_reporte : dias [".count($dias)."]");
		return $dias;
	} //dias_reporte

	function dias_reporte_to_string ($dias){
		$cadena = "";
		$numero_registros = count($dias);
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1,"dias_reporte_to_string [$numero_registros]");
		for ($i = 0; $i < $numero_registros; $i++){
			$cadena .= '[ "'.$dias[$i][0].'", '.$dias[$i][1].' ]';
			if ( ($i+1) != $numero_registros){
				$cadena .= ', ';
			}
		}
		/**/ $this->log->registrar(1,"dias_reporte_to_string : cadena -> $cadena");
		return $cadena;
	} //dias_reporte_to_string

	function get_dias_festivos ($fecha_inicio, $fecha_cierre){
		$datos = array();

		/**/ $this->log = new log();
		/**/ $this->log->registrar(1,"get_dias_festivos ($fecha_inicio, $fecha_cierre)");

		$baseDeDatos = new conexion();	
		$conexion    = $baseDeDatos->ConectarBD(true); //consulta

		$consulta 	= "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha FROM dia_festivo WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_cierre' ";
		/**/ $this->log->registrar(1,"get_dias_festivos -> consulta : $consulta");

		$query = mysql_query($consulta, $conexion);
		$num   = mysql_num_rows($query);

		if ($num != 0){
			while($registros = mysql_fetch_array($query)){
				array_push($datos, $registros["fecha"]);
			}
		}
		$baseDeDatos->CerrarBD($conexion);
		/**/ $this->log->registrar(1,"get_dias_festivos : datos [".count($datos)."]");
		return $datos;
	} //get_dias_festivos
} //funciones
?>