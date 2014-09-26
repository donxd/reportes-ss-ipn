<?
	if ( !empty($_POST) ){
		$fecha_inicio = $_POST["fecha_inicio"];
		$tipo_reporte = $_POST["tipo_reporte"];
		//echo "[$fecha_inicio]\n[$tipo_reporte]";
	} else {
		echo 'Error 500';
	}
?>