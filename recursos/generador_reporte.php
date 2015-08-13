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
        self::agrega_hora_entrada_reporte();
        self::agrega_hora_salida_reporte();
        self::agrega_fechas_dias_reporte();
        self::agrega_horas_dia_reporte();
		self::agrega_total_horas_reporte();
	}

	private function agrega_mes_reporte (){
		self::agrega_valor_celda( 'AC7', $_POST['mes'] );
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