<?
	if ( !empty($_POST) ){
		$fecha_inicio = $_POST["fecha_inicio"];
		$tipo_reporte = $_POST["tipo_reporte"];

		// $fecha_inicio = date(, strtotime($_POST["fecha_inicio"]) );
		// echo "[$fecha_inicio]\n[$tipo_reporte]";
		require_once("funciones.php");
		$funciones = new funciones();

		//echo "periodo mes ($tipo_reporte, $fecha_inicio) : ".$funciones->periodo_mes($tipo_reporte, $fecha_inicio);
		echo "periodo reporte ($tipo_reporte, $fecha_inicio) : ";
			print_r( $funciones->periodo_reporte($tipo_reporte, $fecha_inicio) );
	} else {
		echo 'Error 500';
	}
?>