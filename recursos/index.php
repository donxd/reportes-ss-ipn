<?php
	
	require_once('funciones.php');

	global $log;
	$GLOBALS[ 'log' ] = new log();

	procesa_peticion();
	
	function procesa_peticion (){
		try {
			if ( valida_parametros() ){

				$tipo_dias = $_POST['tipo_dias'];

				$tiempo_inicio = strtotime( $_POST['fecha_inicio'] );
				$tiempo_cierre = strtotime( $_POST['fecha_cierre'] );

				// $tiempo_inicio = strtotime( $_POST['fecha_inicio'] . '00:00:00' );
				// $tiempo_cierre = strtotime( $_POST['fecha_cierre'] . '00:00:00' );
				// $tiempo_inicio = date_timestamp_get( date_create_from_format( 'd-m-Y', $_POST['fecha_inicio'] ) );
				// $tiempo_cierre = date_timestamp_get( date_create_from_format( 'd-m-Y', $_POST['fecha_cierre'] ) );
				// $tiempo_inicio = get_tiempo_fecha_texto( $_POST['fecha_inicio'] );
				// $tiempo_cierre = get_tiempo_fecha_texto( $_POST['fecha_cierre'] );

				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, '--- procesa_peticion ---');
				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha_inicio : %s ', $_POST['fecha_inicio'] ) );
				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha_cierre : %s ', $_POST['fecha_cierre'] ) );
				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- tiempo_inicio : %d ', $tiempo_inicio ) );
				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- tiempo_cierre : %d ', $tiempo_cierre ) );
				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha tiempo_inicio : %s ', date( 'Y-m-d H:i:s', $tiempo_inicio ) ) );
				// $GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha tiempo_cierre : %s ', date( 'Y-m-d H:i:s', $tiempo_cierre ) ) );


				$periodo_tiempo = get_periodo_tiempo( $tiempo_inicio, $tiempo_cierre );

				$dias_reporte = get_dias_reporte( $periodo_tiempo, $tipo_dias );
				genera_salida( $periodo_tiempo, $dias_reporte );

			} else {
				throw new Exception( NULL, ERROR_PARAMETROS );
			}
		} catch ( Exception $error  ){
			procesa_error( $error );
		}
	}

	function get_tiempo_fecha_texto ( $fecha_texto ){
		$fecha_desglozada = explode( '-', $fecha_texto );
		list( $dia, $mes, $anio ) = $fecha_desglozada;

		return mktime( 0, 0, 0, intval( $mes ), intval( $dia ), intval( $anio ) );
	}

	function valida_parametros (){
		return ( !empty( $_POST ) && array_key_exists( 'fecha_inicio', $_POST ) > 0 
					&& array_key_exists( 'fecha_cierre', $_POST ) && array_key_exists( 'tipo_dias', $_POST ) 
						&& strlen( $_POST['fecha_inicio'] ) > 0 && strlen( $_POST['fecha_cierre'] ) > 0
							&& strlen( $_POST['tipo_dias'] ) > 0 );
	}

	function get_dias_reporte ( $periodo_tiempo, $tipo_dias ){
		$funciones = new funciones();

		return $funciones->dias_reporte( $tipo_dias, $periodo_tiempo );
	}

	function get_periodo_tiempo ( $tiempo_inicio, $tiempo_cierre ){
		$GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, '--- get_periodo_tiempo ---' );
		return array(
			  'inicio' => $tiempo_inicio
			, 'cierre' => $tiempo_cierre
			, 'fecha_inicio' => date( 'd-m-Y', $tiempo_inicio )
			, 'fecha_cierre' => date( 'd-m-Y', $tiempo_cierre )
		);
	}

	function genera_salida ( $periodo_tiempo, $dias_reporte ){
		$salida = sprintf( 
			'{ 
				  "consulta" : true
				, "mes"      : "%s"
				, "periodo"  : %s
				, "dias"     : %s
			}'
			, get_mes_periodo( $periodo_tiempo )
			, get_periodo_fechas( $periodo_tiempo )
			, json_encode( $dias_reporte )
		);
		imprime_salida( $salida );
	}

	function get_periodo_fechas ( $periodo ){
		unset( $periodo['inicio'] );
		unset( $periodo['cierre'] );
		
		return json_encode( $periodo );
	}

	function get_mes_periodo ( $periodo_tiempo ){
		$meses = get_opciones_meses();

		$numero_mes_inicio = intval( date('m', $periodo_tiempo['inicio'] ) );
		$numero_mes_cierre = intval( date('m', $periodo_tiempo['cierre'] ) );
		$numero_mes_inicio--;
		$numero_mes_cierre--;
		
		if ( $numero_mes_inicio == $numero_mes_cierre ){
			return $meses[ $numero_mes_inicio ];
		} else {
			return sprintf(
				'%s-%s'
				, $meses[ $numero_mes_inicio ]
				, $meses[ $numero_mes_cierre ]
			);
		}
	}

	function get_opciones_meses (){
		return array( 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
						'Julio', 'Agosto', 'Septiembre', 'Octubre','Noviembre', 'Diciembre');
	}

	function procesa_error ( $error ){
		$mensaje = get_mensaje_error( $error );
		$salida = get_salida_error( $mensaje );
		imprime_salida( $salida );
	}

	function get_mensaje_error ( $error ){
		switch ( $error->getCode() ){
			case ERROR_PARAMETROS:
				return 'Hay un error en los parametros : '.$error->getMessage();
			case ERROR_PERIODO_DIAS:
				return 'Hay un error en el periodo de los días : '.$error->getMessage();
			default:
				return $error->getMessage();
		}
	}

	function get_salida_error ( $mensaje ){
		return sprintf( 
			'{ "consulta" : false, "mensaje" : "%s" }'
			, $mensaje
		);
	}

	function imprime_salida ( $salida ){
		header( 'Content-Type: application/json' );
		$GLOBALS[ 'log' ]->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- salida : %s ', json_encode( json_decode( $salida ) ) ) );
		echo json_encode( json_decode( $salida ) );
	}
?>