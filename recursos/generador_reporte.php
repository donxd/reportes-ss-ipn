<?php

require_once('configuracion.php');
require_once('log.php');
// require_once( '/../lib/PHPExcel/Classes/PHPExcel.php' );
require_once( '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php' );

$reporte = new reporte();
$reporte->genera_reporte();

class reporte {

	private $log;
	private $objetoPHPExcel;

	function __construct (){
		$this->log = new log();
	}

	function genera_reporte (){
		try {
			if ( self::parametros_validos() ){
				self::crea_archivo_reporte();
			}
		} catch ( Exception $error ){
			self::procesa_error( $error );
		}
	}

	private function parametros_validos (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- parametros_validos ---');
		if ( isset( $_POST ) ){
			$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- parametros : %s', json_encode( $_POST ) ) );
			return TRUE;
		} else {
			throw new Exception( '', ERROR_PARAMETROS );
		}
	}

	private function crea_archivo_reporte (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- crea_archivo_reporte ---');
		$objPHPExcel = self::leer_plantilla_reporte();
		self::rellena_plantilla_reporte( $objPHPExcel );
		self::envia_archivo( $objPHPExcel );
	}

	private function leer_plantilla_reporte (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- leer_plantilla_reporte ---');
		$ruta_archivo = './formatos/upiicsa.xlsx';
		if ( file_exists( $ruta_archivo ) ){
			return PHPExcel_IOFactory::load( $ruta_archivo );
		} else {
			throw new Exception( $ruta_archivo, ERROR_ARCHIVO );
		}
	}

	private function rellena_plantilla_reporte ( &$objPHPExcel ){
		$this->objetoPHPExcel = $objPHPExcel;
        self::agrega_mes_reporte();
        self::agrega_periodos_reporte();
        self::agrega_hora_entrada_reporte();
        self::agrega_hora_salida_reporte();
        self::agrega_fechas_dias_reporte();
        self::agrega_horas_dia_reporte();
		self::agrega_total_horas_reporte();
		self::agrega_numero_reporte_reporte();
		self::agrega_carrera_reporte();
		self::agrega_nombre_alumno_reporte();
		self::agrega_boleta_reporte();
		self::agrega_correo_reporte();
		self::agrega_telefono_reporte();
		self::agrega_dependencia_reporte();
		self::agrega_responsable_nombre_reporte();
		self::agrega_responsable_puesto_reporte();
		self::agrega_fecha_emision_reporte();
		self::agrega_total_horas_acumuladas_reporte();
		self::agrega_actividades_reporte();
	}

	private function agrega_mes_reporte (){
		self::agrega_valor_celda( 'AC7', $_POST['mes'] );
	}

	private function agrega_periodos_reporte (){
		self::agrega_periodo_inicio_reporte();
		self::agrega_periodo_cierre_reporte();
	}

	private function agrega_periodo_inicio_reporte (){
		$tiempo_periodo_inicio = strtotime( $_POST['fecha_inicio'] );	
		$columna_periodo = 'J';
		$posicion = 10;
		self::agrega_periodo_reporte( $tiempo_periodo_inicio, $columna_periodo, $posicion );
	}

	private function agrega_periodo_reporte ( $tiempo_periodo, $columna_periodo, $posicion ){
		self::agrega_valor_celda( sprintf('%s%s', $columna_periodo, $posicion), date( 'd', $tiempo_periodo ) );
		$columna_periodo = self::get_siguiente_columna( $columna_periodo );
		self::agrega_valor_celda( sprintf('%s%s', $columna_periodo, $posicion), self::get_nombre_mes( intval( date( 'm', $tiempo_periodo ) ) ) );
		$columna_periodo = self::get_siguiente_columna( $columna_periodo );
		self::agrega_valor_celda( sprintf('%s%s', $columna_periodo, $posicion), date( 'Y', $tiempo_periodo ) );
	}

	private function get_siguiente_columna ( $columna ){
		return chr( ord( $columna ) + 1 );
	}

	private function get_nombre_mes ( $numero_mes ){
		$nombre_meses = self::get_nombre_meses();
		return $nombre_meses[ $numero_mes - 1 ];
	}

	private function get_nombre_meses (){
		return array( 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'NOV', 'DIC');
	}

	private function agrega_periodo_cierre_reporte (){
		$tiempo_periodo_cierre = strtotime( $_POST['fecha_cierre'] );
		$columna_periodo = 'N';
		$posicion = 10;
		self::agrega_periodo_reporte( $tiempo_periodo_cierre, $columna_periodo, $posicion );
	}

	private function agrega_hora_entrada_reporte (){
		$columna = 'U';
		$posicion_inicio = 10;
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $_POST['hora_entrada']
			);
			$posicion_inicio++;
		}
	}

	private function agrega_hora_salida_reporte (){
		$columna = 'X';
		$posicion_inicio = 10;
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $_POST['hora_salida']
			);
			$posicion_inicio++;
		}
	}

	private function agrega_fechas_dias_reporte (){
		$columna = 'R';
		$posicion_inicio = 10;
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			// $this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- dia : %s ', json_encode( $dia ) ) );
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, str_replace( '-', '/', $dia->fecha )
			);
			$posicion_inicio++;
		}
	}

	private function agrega_horas_dia_reporte (){
		$columna = 'AA';
		$numero_dias = count( json_decode( $_POST['dias'] ) );

		for ( $contador = 0, $posicion_inicio = 10; $contador < $numero_dias; $contador++, $posicion_inicio++ ){
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $_POST['horas_dia']
			);
		}
	}

	private function agrega_total_horas_reporte (){
		self::agrega_valor_celda( 'AA35', $_POST['total_horas'] );
	}

	private function agrega_valor_celda ( $celda, $valor ){
		$this->objetoPHPExcel->getActiveSheet()->setCellValue( $celda, $valor );
	}

	private function agrega_numero_reporte_reporte (){
		self::agrega_valor_celda( 'O7', $_POST['numero_reporte'] );
	}

	private function agrega_carrera_reporte (){
		self::agrega_valor_celda( 'D10', $_POST['carrera'] );
	}

	private function agrega_nombre_alumno_reporte (){
		self::agrega_valor_celda( 'E14', $_POST['nombre_alumno'] );
		self::agrega_valor_celda( 'Q7', $_POST['nombre_alumno'] );
	}

	private function agrega_boleta_reporte (){
		self::agrega_valor_celda( 'N14', $_POST['boleta'] );
	}

	private function agrega_correo_reporte (){
		self::agrega_valor_celda( 'H16', $_POST['correo'] );
	}

	private function agrega_telefono_reporte (){
		self::agrega_valor_celda( 'D12', $_POST['telefono'] );
	}

	private function agrega_dependencia_reporte (){
		self::agrega_valor_celda( 'A20', $_POST['dependencia'] );
	}

	private function agrega_responsable_nombre_reporte (){
		self::agrega_valor_celda( 'B25', $_POST['responsable_nombre'] );
		self::agrega_valor_celda( 'A42', $_POST['responsable_nombre'] );
		self::agrega_valor_celda( 'Q42', $_POST['responsable_nombre'] );
	}

	private function agrega_responsable_puesto_reporte (){
		self::agrega_valor_celda( 'J25', $_POST['responsable_puesto'] );
		self::agrega_valor_celda( 'A43', $_POST['responsable_puesto'] );
		self::agrega_valor_celda( 'Q43', $_POST['responsable_puesto'] );
	}

	private function agrega_fecha_emision_reporte (){
		$fecha_emision_desglozada = self::get_fecha_emision_desglozada( $_POST['fecha_emision'] );
		self::agrega_valor_celda( 'J37', $fecha_emision_desglozada['dia'] );
		self::agrega_valor_celda( 'L37', $fecha_emision_desglozada['mes'] );
		self::agrega_valor_celda( 'P37', $fecha_emision_desglozada['anio'] );
	}

	private function get_fecha_emision_desglozada ( $fecha_emision ){
		$tiempo_fecha_emision = strtotime( $fecha_emision );
		return array(
			'dia' => intval( date( 'd', $tiempo_fecha_emision ) )
			, 'mes' => intval( date( 'm', $tiempo_fecha_emision ) )
			, 'anio' => intval( date( 'Y', $tiempo_fecha_emision ) )
		);
	}

	private function agrega_total_horas_acumuladas_reporte (){
		self::agrega_valor_celda( 'AA36', self::get_total_horas_acumuladas_reporte() );
	}

	private function get_total_horas_acumuladas_reporte (){
		return intval( $_POST['total_horas_acumuladas_anterior'] ) + intval( $_POST['total_horas'] );
	}

	private function agrega_actividades_reporte (){
		$columna = 'B';
		$numero_elementos = count( $_POST['actividad'] );

		for ( $contador = 0, $posicion_inicio = 31; $contador < $numero_elementos; $contador++, $posicion_inicio++ ){
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $_POST['actividad'][ $contador ]
			);
		}
	}

	private function envia_archivo ( &$objPHPExcel ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- envia_archivo ---');
		self::crea_cabeceras_archivo();
		self::descarga_archivo( $objPHPExcel );
	}

	private function crea_cabeceras_archivo (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- crea_archivo_reporte ---');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporte.xlsx"');
		header('Cache-Control: max-age=0');
	}

	private function descarga_archivo ( &$objPHPExcel ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- descarga_archivo ---');
		$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	private function procesa_error ( $error ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- procesa_error ---');
		$mensaje = self::get_mensaje_error( $error );
		$this->log->registrar( LOG_MENSAJE_ERROR, sprintf( '--- descripcion : %s ', $mensaje ) );
		self::imprime_salida( $mensaje );
	}

	private function get_mensaje_error ( $error ){
		switch( $error->getCode() ){
			case ERROR_PARAMETROS:
				return 'Hay un error en los parametros';
			case ERROR_ARCHIVO:
				return sprintf( 'No se encuentra el archivo : %s', $error->getMessage() );
			default :
				return $error->getMessage();
		}
	}

	private function imprime_salida ( $mensaje ){
		header( 'Content-Type: application/json' );
		$salida = sprintf( '{ "accion" : false, "mensaje" : "%s" }', $mensaje );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- salida : %s ', json_encode( json_decode( $salida ) ) ) );
		echo json_encode( json_decode( $salida ) );
	}

}

?>