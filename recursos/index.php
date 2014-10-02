<?
	if ( !empty($_POST) ){
		$fecha_inicio = $_POST["fecha_inicio"];
		$tipo_reporte = $_POST["tipo_reporte"];

		// $fecha_inicio = date(, strtotime($_POST["fecha_inicio"]) );
		// echo "[$fecha_inicio]\n[$tipo_reporte]";
		require_once("funciones.php");
		$funciones = new funciones();

		$mes = $funciones->periodo_mes($tipo_reporte, $fecha_inicio);
		$periodo = $funciones->periodo_reporte($tipo_reporte, $fecha_inicio);
		$fecha_cierre = $periodo[1];
		$periodo = $funciones->periodo_to_string( $periodo );
		$numero_dias_reporte = $funciones->numero_dias_reporte($tipo_reporte, $fecha_inicio, $fecha_cierre);

		$salida = '{ '.
			'"mes" : "'.$mes.'", '.
			'"periodo" : ['.$periodo.'], '.
			'"numero_dias_reporte" : '.$numero_dias_reporte.' '.
		'}';
		echo $salida;
	} else {
		echo 'Error 500';
	}
?>