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
		$numero_mes = intval( date("m", strtotime($fecha) ) );
		$mes     = date("m", strtotime($fecha) );
		$anio    = date("Y", strtotime($fecha) );
		$periodo = array();

		if ( !strcmp($tipo_reporte, 'pm') ){

			$limite_mes = date("t", strtotime($fecha) );

			$periodo[0] = '01-'.$mes.'-'.$anio;
			$periodo[1] = $limite_mes.'-'.$mes.'-'.$anio;
		} else {

			$nuevo_mes = ($mes < 12) ? $mes+1 : 1;
			$nuevo_mes = ($nuevo_mes < 10) ? '0'.$nuevo_mes : "$nuevo_mes";

			$periodo[0] = '16-'.$mes.'-'.$anio;
			$periodo[1] = '15-'.$nuevo_mes.'-'.$anio;
		}
		return $periodo;
	} //periodo_reporte
	function numero_dias_reporte ($tipo_reporte, $fecha_inicio, $fecha_final){
		// ndr = numero dias x reporte
		// nsd = numero sábados y domingos
		// df  = días festivos
		//     Previamente guardados

		// ndr = (fp - ip) - df - nsd
	} //numero_dias_reporte
} //funciones
?>