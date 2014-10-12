<?
	if ( !empty($_POST) ){

		$fecha_inicio = strtotime($_POST["fecha_inicio"]);
		$fecha_cierre = strtotime($_POST["fecha_cierre"]);

		$tipo_reporte = $_POST["tipo_reporte"];
		$tipo_dias    = $_POST["tipo_dias"];

		require_once("funciones.php");
		$funciones = new funciones();

		$periodo = $funciones->periodo_reporte($tipo_reporte, $fecha_inicio, $fecha_cierre);
		if (!$fecha_cierre){
			// $funciones->mensaje_prueba( "periodo : ".date("d-m-Y", $periodo[1]) );
			$fecha_cierre = $periodo[1];
			// $funciones->mensaje_prueba( "fecha cierre : ".date("d-m-Y", $fecha_cierre) );
		}
		$mes                 = $funciones->periodo_mes($tipo_reporte, $fecha_inicio, $fecha_cierre);
		$periodo             = $funciones->periodo_to_string( $periodo );
		$numero_dias_reporte = $funciones->numero_dias_reporte($tipo_reporte, $fecha_inicio, $fecha_cierre);
		$dias_reporte        = $funciones->dias_reporte($tipo_reporte, $tipo_dias, $fecha_inicio, $fecha_cierre, $numero_dias_reporte);
		$dias_reporte        = $funciones->dias_reporte_to_string( $dias_reporte );

		$salida = '{ '.
			'"mes" : "'.$mes.'", '.
			'"periodo" : ['.$periodo.'], '.
			'"numero_dias_reporte" : '.$numero_dias_reporte.', '.
			'"dias" : ['.$dias_reporte.'] '.
		'}';
		echo $salida;
	} else {
		echo 'Error 500';
	}
?>