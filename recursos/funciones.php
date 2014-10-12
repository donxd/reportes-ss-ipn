<?

require_once("log.php");
require_once("conexion.php");

class funciones {

	private $log;

	// $tipo_reporte
	// 		pm -> principio de mes
	// 		mm -> mitad de mes

	function periodo_mes ($tipo_reporte, $fecha_inicio, $fecha_cierre){

		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("periodo_mes (%s, %s, %s)", $tipo_reporte, date("d-m-Y", $fecha_inicio), date("d-m-Y", $fecha_cierre) ) );

		$meses = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
						"Julio", "Agosto", "Septiembre", "Octubre","Noviembre", "Diciembre");

		$numero_mes_inicio = intval( date("m", $fecha_inicio) );
		$mes_reporte = "Error";

		if ($numero_mes_inicio < 13){
			$mes_reporte = $meses[$numero_mes_inicio-1];
			if ( !strcmp($tipo_reporte, 'mm') ){
				//mm
				$numero_mes_cierre = intval( date("m", $fecha_cierre) );
				$mes_reporte .= "-".$meses[$numero_mes_cierre-1];
			}
		}
		return $mes_reporte;
	} //periodo_mes

	function periodo_reporte ($tipo_reporte, $fecha_inicio, $fecha_cierre){

		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("periodo_reporte (%s, %s, %s)",$tipo_reporte, date("d-m-Y", $fecha_inicio), date("d-m-Y", $fecha_cierre) ) );

		$periodo = array();

		$dia  = date("d", $fecha_inicio);
		$mes  = date("m", $fecha_inicio);
		$anio = date("Y", $fecha_inicio);
		$separador = '-';

		$periodo[0] = strtotime($anio.$separador.$mes.$separador.$dia);
		/**/ $this->log->registrar(1, sprintf("periodo_reporte >> periodo inicio : %s", date("d-m-Y", $periodo[0]) ) );
		/**/ $this->log->registrar(1, sprintf("periodo_reporte >> tipo periodo cierre : %s [%s] ", gettype($fecha_cierre), ($fecha_cierre) ? 'true' : 'false' ) );

		if (!$fecha_cierre){
			switch ($tipo_reporte){
				case 'pm':
					$limite_mes = date("t", $fecha_inicio);
					$periodo[1] = strtotime($anio.$separador.$mes.$separador.$limite_mes);
					break;
				case 'mm':
					$nuevo_mes;
					$nuevo_anio;
					if ($mes < 12){
						$nuevo_mes = sprintf("%02d",$mes+1);
						$nuevo_anio = $anio;
					} else {
						$nuevo_mes = "01";
						$nuevo_anio = intval($anio+1);
					}
					/**/ $this->log->registrar(1, sprintf("\t\tmes %s -> %s", $mes, $nuevo_mes) );
					/**/ $this->log->registrar(1, sprintf("\t\taño %s -> %s", $anio, $nuevo_anio) );

					/**/ $this->log->registrar(1, sprintf("periodo_reporte >> periodo cierre TEXTO : %s", $nuevo_anio.$separador.$nuevo_mes.$separador.'15') );
					$periodo[1] = strtotime($nuevo_anio.$separador.$nuevo_mes.$separador.'15');
					break;
			}
		} else {
			$dia  = date("d", $fecha_cierre);
			$mes  = date("m", $fecha_cierre);
			$anio = date("Y", $fecha_cierre);
			$separador = '-';
			$periodo[1] = strtotime($anio.$separador.$mes.$separador.$dia);
		}
		/**/ $this->log->registrar(1, sprintf("periodo_reporte >> periodo cierre : %s", date("d-m-Y", $periodo[1]) ) );
		return $periodo;
	} //periodo_reporte

	function periodo_to_string ($periodo){
		$cadena = '"'.date("d-m-Y", $periodo[0]).'" , "'.date("d-m-Y", $periodo[1]).'"';
		return $cadena;
	} //periodo_to_string

	function numero_dias_reporte ($tipo_reporte, $fecha_inicio, $fecha_cierre){
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("numero_dias_reporte (%s, %s, %s)", $tipo_reporte, date("d-m-Y", $fecha_inicio), date("d-m-Y", $fecha_cierre) ) );

		// ndr = numero dias x reporte
		// nsd = numero sábados y domingos
		// df  = días festivos
		//     Previamente guardados

		// ndr = (fp - ip) - df - nsd
		$numero_dias_reporte;
		if ( !strcmp($tipo_reporte, 'pm') ){ 
			//pm
			// *** $dias_mes = date("d", $fecha_cierre );
			$dia_semana    = date("N", $fecha_inicio) - 1;
			$dias_recorrer = date("d", $fecha_inicio) - 1;

			//$this->log->registrar(2,"numero_dias_reporte [$fecha_cierre] date['d', ".date("d", $fecha_cierre)."] int[".intval( date("d", $fecha_cierre) )."]");
			$this->log->registrar(2, sprintf("numero_dias_reporte [%s] date['d', %s] int[%d]", date("d-m-Y", $fecha_cierre), date("d", $fecha_cierre), intval( date("d", $fecha_cierre) ) ) );
			//$this->log->registrar(2,"numero_dias_reporte [$fecha_cierre] date['d', ".date("d", $fecha_cierre)."] int[".intval( date("d", $fecha_cierre) )."]");

			$numero_dias_reporte = intval( date("d", $fecha_cierre) ) - $dias_recorrer;

			$dias_ajuste = 0;
			if ($dia_semana != 0){
				$dias_ajuste = 7 - $dia_semana;
			}
			$numero_dias_reporte -= $dias_ajuste;

			$semanas = intval($numero_dias_reporte / 7);
			$dias_residuo = $numero_dias_reporte % 7;

			$numero_dias_reporte -= ( $semanas * 2 );

			// /**/ $this->log->registrar(1,"\n--------\ndia semana : $dias_recorrer\ndias mes : $dias_recorrer\ndia semana inicio : $dia_semana\nsemanas : $semanas\nresiduo : $dias_residuo\nresultado 1 : $numero_dias_reporte\n--------");

			if ($dia_semana < 5 && $dia_semana != 0){
				$numero_dias_reporte += (5-$dia_semana);
			}
			if ($dias_residuo > 5){
				$numero_dias_reporte -= ($dias_residuo-5);	
			}

		} else {
			//mm
			$periodo = self::periodo_reporte('pm',$fecha_inicio, false);
			$numero_dias_reporte =  self::numero_dias_reporte('pm', $fecha_inicio, $periodo[1]);
			$numero_dias_reporte += self::numero_dias_reporte('pm', strtotime('01'.date("-m-Y", $fecha_cierre)), $fecha_cierre);
			/**/ $this->log->registrar(1, sprintf("\n*********\n[%s , %s]\n[%s , %s]\nres : %d\n*********", 
				date("d-m-Y", $fecha_inicio),
				date("d-m-Y", $periodo[1]),
				date("d-m-Y", strtotime('01'.date("-m-Y", $fecha_cierre) ) ),
				date("d-m-Y", $fecha_cierre),
				$numero_dias_reporte)
			);
		}
		return $numero_dias_reporte;
	} //numero_dias_reporte

	function dias_periodo ($tipo_dias, $fecha_inicio, $fecha_cierre){
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("dias_periodo (%s, %s, %s)", $tipo_dias, date("d-m-Y", $fecha_inicio), date("d-m-Y", $fecha_cierre) ) );

		$dias = array();
		$dia_inicio = intval( date("d", $fecha_inicio) );
		/**/ $this->log->registrar(1, sprintf("valores ??? dia_limite : %d - %d = %d", intval( date("d", $fecha_cierre) ), ($dia_inicio - 1), ( intval( date("d", $fecha_cierre) )-($dia_inicio - 1) ) ) );
		
		$numero_dias_periodo = intval( date("d", $fecha_cierre) ) - ($dia_inicio - 1);
		$dia_semana = intval( date("N", $fecha_inicio) )- 1;

		$mes_anio = date("-m-Y", $fecha_inicio);

		// /**/ $this->log->registrar(1,"\ndia_inicio : $dia_inicio\nnumero_dias_periodo : $numero_dias_periodo\ndia_semana : $dia_semana\nmes_anio : $mes_anio");

		for ($i = 0; $i < $numero_dias_periodo; $i++, $dia_semana++){
			// /**/ $this->log->registrar(1,"i : $i -> ".sprintf("%02d",$dia_inicio + $i)."\ndia_semana : $dia_semana");
			switch ($tipo_dias){
				case 'es': //entre semana
					if ($dia_semana < 5){
						// /**/ $this->log->registrar(1,"a[".sprintf("%02d",$dia_inicio + $i).$mes_anio."]");
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
		/**/ $this->log->registrar(1, sprintf("dias_periodo : dias [%d]", count($dias) ) );
		return $dias;
	} //dias_periodo

	function dias_reporte ($tipo_reporte, $tipo_dias, $fecha_inicio, $fecha_cierre, $numero_dias_reporte){
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("dias_reporte (%s, %s, %s, %s, %d)", $tipo_reporte, $tipo_dias, date("d-m-Y", $fecha_inicio), date("d-m-Y", $fecha_cierre), $numero_dias_reporte) );

		$dias = array();
		$dia_semana = date("N", $fecha_inicio) - 1;
		$dias_festivos = self::get_dias_festivos($fecha_inicio, $fecha_cierre);
		
		if ( !strcmp($tipo_reporte, 'pm') ){ 
			//pm
			$dias = self::dias_periodo($tipo_dias, $fecha_inicio, $fecha_cierre);
		} else {
			//mm
			$periodo = self::periodo_reporte('pm',$fecha_inicio, FALSE);
			$dias = self::dias_periodo($tipo_dias, $fecha_inicio, $periodo[1]);
			// array_push($dias, self::dias_periodo($tipo_dias, '01'.date("-m-Y", strtotime($fecha_cierre) ), $fecha_cierre) );
			$dias = array_merge($dias, self::dias_periodo($tipo_dias, strtotime('01'.date("-m-Y", $fecha_cierre)), $fecha_cierre) );
		}

		for ($i = 0; $i < count($dias_festivos); $i++){
			for ($j = 0; $j < count($dias); $j++){
				/**/ $this->log->registrar(1, sprintf("comparando festivos : %d - %d", $dias_festivos[$i], $dias[$j][0]) );
				if ( !strcmp($dias_festivos[$i], $dias[$j][0]) ){
					$dias[$j][1] = 1;
				} //if
			} //for
		} //for
		/**/ $this->log->registrar(1,sprintf("dias_reporte : dias [%d]", count($dias) ) );
		return $dias;
	} //dias_reporte

	function dias_reporte_to_string ($dias){
		$cadena = "";
		$numero_registros = count($dias);
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("dias_reporte_to_string [%d]", $numero_registros) );
		for ($i = 0; $i < $numero_registros; $i++){
			$cadena .= '[ "'.$dias[$i][0].'", '.$dias[$i][1].' ]';
			if ( ($i+1) != $numero_registros){
				$cadena .= ', ';
			}
		}
		/**/ $this->log->registrar(1, sprintf("dias_reporte_to_string : cadena -> %s", $cadena) );
		return $cadena;
	} //dias_reporte_to_string

	function get_dias_festivos ($fecha_inicio, $fecha_cierre){
		$datos = array();

		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("get_dias_festivos (%s, %s)", date("d-m-Y", $fecha_inicio), date("d-m-Y", $fecha_cierre) ) );

		$baseDeDatos = new conexion();	
		$conexion    = $baseDeDatos->ConectarBD(true); //consulta

		$consulta 	= "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha FROM dia_festivo WHERE fecha BETWEEN '".date("Y-m-d", $fecha_inicio)."' AND '".date("Y-m-d", $fecha_cierre)."' ";
		/**/ $this->log->registrar(1, sprintf("get_dias_festivos -> consulta : %s", $consulta) );

		$query = mysql_query($consulta, $conexion);
		$num   = mysql_num_rows($query);

		if ($num != 0){
			while($registros = mysql_fetch_array($query)){
				array_push($datos, $registros["fecha"]);
			}
		}
		$baseDeDatos->CerrarBD($conexion);
		/**/ $this->log->registrar(1, sprintf("get_dias_festivos : datos [%d]", count($datos) ) );
		return $datos;
	} //get_dias_festivos

	function mensaje_prueba ($mensaje){
		/**/ $this->log = new log();
		/**/ $this->log->registrar(1, sprintf("mensaje_prueba (%s)", $mensaje) );
	}
} //funciones
?>