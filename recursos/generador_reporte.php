<?php

require_once('configuracion.php');
require_once('log.php');
require_once('funciones.php');
// require_once( '/../lib/PHPExcel/Classes/PHPExcel.php' );
require_once( '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php' );

$reporte = new reporte();
$reporte->genera_reporte();

class reporte {

	private $log;
	private $objetoPHPExcel;
	private $configuracion_plantilla;

	function __construct (){
		$this->log = new log();
	}

	function genera_reporte (){
		try {
			if ( self::parametros_validos() ){
				$funciones = new funciones();
				$funciones->guardar_datos( $_POST );
				self::selecciona_plantilla();
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

	private function selecciona_plantilla (){
		switch ( $_POST['plantilla'] ){
			case 'upiicsa':
				self::inicializa_plantilla_upiicsa();
				break;
			case 'generica':
				self::inicializa_plantilla_generica();
				break;
		}
	}

	private function inicializa_plantilla_upiicsa (){
		$this->configuracion_plantilla = self::get_configuracion_plantilla_upiicsa();
	}

	private function get_configuracion_plantilla_upiicsa (){
		return array(
			  'nombre_archivo' => 'upiicsa.xlsx'
			, 'mes'            => array( 'celda' => 'AC7' )
			, 'total_horas'    => array( 'celda' => 'AA35' )
			, 'numero_reporte' => array( 'celda' => 'O7' )
			, 'carrera'        => array( 'celda' => 'D10' )
			, 'boleta'         => array( 'celda' => 'N14' )
			, 'correo'         => array( 'celda' => 'H16' )
			, 'telefono'       => array( 'celda' => 'D12' )
			, 'dependencia'    => array( 'celda' => 'A20' )
			, 'periodo_inicio' => array( 'columna' => 'J',  'posicion' => 10 )
			, 'periodo_cierre' => array( 'columna' => 'N',  'posicion' => 10 )
			, 'hora_entrada'   => array( 'columna' => 'U',  'posicion' => 10 )
			, 'hora_salida'    => array( 'columna' => 'X',  'posicion' => 10 )
			, 'fechas_dias'    => array( 'columna' => 'R',  'posicion' => 10 )
			, 'horas_dia'      => array( 'columna' => 'AA', 'posicion' => 10 )
			, 'actividades'    => array( 'columna' => 'B',  'posicion' => 31 )
			, 'fecha_emision'  => array( 'columnas' => array( 'J', 'L', 'P' ),  'posicion' => 37 )
			, 'nombre_alumno'       => array( 'celdas' => array( 'E14', 'Q7' ) )
			, 'responsable_nombre'  => array( 'celdas' => array( 'B25', 'A42', 'Q42' ) )
			, 'responsable_puesto'  => array( 'celdas' => array( 'J25', 'A43', 'Q43' ) )
			, 'total_horas_acumuladas' => array( 'celda' => 'AA36' )
		);
	}

	private function inicializa_plantilla_generica (){
		$this->configuracion_plantilla = self::get_configuracion_plantilla_generica();
	}

	private function get_configuracion_plantilla_generica (){
		return array(
			  'nombre_archivo' => 'generica.xlsx'
			, 'mes'            => array( 'celda' => 'AC7' )
			, 'total_horas'    => array( 'celda' => 'AA35' )
			, 'numero_reporte' => array( 'celda' => 'O7' )
			, 'carrera'        => array( 'celda' => 'D10' )
			, 'boleta'         => array( 'celda' => 'N14' )
			, 'correo'         => array( 'celda' => 'H16' )
			, 'telefono'       => array( 'celda' => 'D12' )
			, 'dependencia'    => array( 'celda' => 'A20' )
			, 'periodo_inicio' => array( 'columna' => 'J',  'posicion' => 10 )
			, 'periodo_cierre' => array( 'columna' => 'N',  'posicion' => 10 )
			, 'hora_entrada'   => array( 'columna' => 'U',  'posicion' => 10 )
			, 'hora_salida'    => array( 'columna' => 'X',  'posicion' => 10 )
			, 'fechas_dias'    => array( 'columna' => 'R',  'posicion' => 10 )
			, 'horas_dia'      => array( 'columna' => 'AA', 'posicion' => 10 )
			, 'actividades'    => array( 'columna' => 'B',  'posicion' => 31 )
			, 'fecha_emision'  => array( 'columnas' => array( 'J', 'L', 'P' ),  'posicion' => 37 )
			, 'nombre_alumno'       => array( 'celdas' => array( 'E14', 'Q7' ) )
			, 'responsable_nombre'  => array( 'celdas' => array( 'B25', 'A42', 'Q42' ) )
			, 'responsable_puesto'  => array( 'celdas' => array( 'J25', 'A43', 'Q43' ) )
			, 'total_horas_acumuladas' => array( 'celda' => 'AA36' )
		);
	}

	private function crea_archivo_reporte (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- crea_archivo_reporte ---');
		$objPHPExcel = self::leer_plantilla_reporte();
		self::rellena_plantilla_reporte( $objPHPExcel );
		self::envia_archivo( $objPHPExcel );
	}

	private function leer_plantilla_reporte (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- leer_plantilla_reporte ---');
		$ruta_archivo = self::get_ruta_archivo_plantilla();
		if ( file_exists( $ruta_archivo ) ){
			return PHPExcel_IOFactory::load( $ruta_archivo );
		} else {
			throw new Exception( $ruta_archivo, ERROR_ARCHIVO );
		}
	}

	private function get_ruta_archivo_plantilla (){
		return sprintf( './formatos/%s', $this->configuracion_plantilla['nombre_archivo'] );
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
		self::agrega_valor_celda( self::get_celda_mes(), $_POST['mes'] );
	}

	private function get_celda_mes (){
		return $this->configuracion_plantilla['mes']['celda'];
	}

	private function agrega_periodos_reporte (){
		self::agrega_periodo_inicio_reporte();
		self::agrega_periodo_cierre_reporte();
	}

	private function agrega_periodo_inicio_reporte (){
		$fecha_periodo_inicio = $_POST['fecha_inicio'];
		$periodo_inicio = $this->configuracion_plantilla['periodo_inicio'];
		$columna = $periodo_inicio['columna'];
		$posicion = $periodo_inicio['posicion'];
		self::agrega_periodo_reporte( $fecha_periodo_inicio, $columna, $posicion );
	}

	private function agrega_periodo_reporte ( $fecha_periodo, $columna, $posicion ){
		$fecha_desglozada = explode( ' ', $fecha_periodo );
		list( $dia, $mes, $anio ) = $fecha_desglozada;
		self::agrega_valor_celda( sprintf( '%s%d', $columna, $posicion ), $dia );
		$columna = self::get_siguiente_columna( $columna );
		self::agrega_valor_celda( sprintf( '%s%d', $columna, $posicion ), $mes );
		$columna = self::get_siguiente_columna( $columna );
		self::agrega_valor_celda( sprintf( '%s%d', $columna, $posicion ), $anio );
	}

	private function get_siguiente_columna ( $columna ){
		return chr( ord( $columna ) + 1 );
	}

	private function get_nombre_mes ( $numero_mes ){ //
		$nombre_meses = self::get_nombre_meses();
		return $nombre_meses[ $numero_mes - 1 ];
	}

	private function get_nombre_meses (){ //
		return array( 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'NOV', 'DIC');
	}

	private function agrega_periodo_cierre_reporte (){
		$fecha_periodo_cierre = $_POST['fecha_cierre'];
		$periodo_cierre = $this->configuracion_plantilla['periodo_cierre'];
		$columna_periodo = $periodo_cierre['columna'];
		$posicion = $periodo_cierre['posicion'];
		self::agrega_periodo_reporte( $fecha_periodo_cierre, $columna_periodo, $posicion );
	}

	private function agrega_hora_entrada_reporte (){
		$hora_entrada = $this->configuracion_plantilla['hora_entrada'];
		$columna = $hora_entrada['columna'];
		$posicion_inicio = $hora_entrada['posicion'];
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			$contenido_celda = $_POST['hora_entrada'];
			if ( $dia->festivo ){
				$contenido_celda = 'DIA';
			}
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $contenido_celda
			);
			$posicion_inicio++;
		}
	}

	private function agrega_hora_salida_reporte (){
		$hora_salida = $this->configuracion_plantilla['hora_salida'];
		$columna = $hora_salida['columna'];
		$posicion_inicio = $hora_salida['posicion'];
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			$contenido_celda = $_POST['hora_salida'];
			if ( $dia->festivo ){
				$contenido_celda = 'FESTIVO';
			}
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $contenido_celda
			);
			$posicion_inicio++;
		}
	}

	private function agrega_fechas_dias_reporte (){
		$fechas_dias = $this->configuracion_plantilla['fechas_dias'];
		$columna = $fechas_dias['columna'];
		$posicion_inicio = $fechas_dias['posicion'];
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $dia->fecha
			);
			$posicion_inicio++;
		}
	}

	private function agrega_horas_dia_reporte (){
		$horas_dia = $this->configuracion_plantilla['horas_dia'];
		$columna = $horas_dia['columna'];
		$posicion_inicio = $horas_dia['posicion'];
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			if ( !$dia->festivo ){
				$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
					sprintf( '%s%d', $columna, $posicion_inicio )
					, $_POST['horas_dia']
				);
			}
			$posicion_inicio++;
		}
	}

	private function agrega_total_horas_reporte (){
		self::agrega_valor_celda( self::get_celda_total_horas(), $_POST['total_horas'] );
	}

	private function get_celda_total_horas (){
		return $this->configuracion_plantilla['total_horas']['celda'];
	}

	private function agrega_valor_celda ( $celda, $valor ){
		$this->objetoPHPExcel->getActiveSheet()->setCellValue( $celda, $valor );
	}

	private function agrega_numero_reporte_reporte (){
		self::agrega_valor_celda( self::get_celda_numero_reporte(), $_POST['numero_reporte'] );
	}

	private function get_celda_numero_reporte (){
		return $this->configuracion_plantilla['numero_reporte']['celda'];
	}	

	private function agrega_carrera_reporte (){
		self::agrega_valor_celda( self::get_celda_carrera_reporte(), $_POST['carrera'] );
	}

	private function get_celda_carrera_reporte (){
		return $this->configuracion_plantilla['carrera']['celda'];
	}

	private function agrega_nombre_alumno_reporte (){
		$celdas_nombre_alumno = self::get_celdas_nombre_alumno();
		self::agrega_valor_celda( $celdas_nombre_alumno[ 0 ], $_POST['nombre_alumno'] );
		self::agrega_valor_celda( $celdas_nombre_alumno[ 1 ], $_POST['nombre_alumno'] );
	}

	private function get_celdas_nombre_alumno (){
		return $this->configuracion_plantilla['nombre_alumno']['celdas'];
	}

	private function agrega_boleta_reporte (){
		self::agrega_valor_celda( self::get_celda_boleta(), $_POST['boleta'] );
	}

	private function get_celda_boleta (){
		return $this->configuracion_plantilla['boleta']['celda'];
	}

	private function agrega_correo_reporte (){
		self::agrega_valor_celda( self::get_celda_correo(), $_POST['correo'] );
	}

	private function get_celda_correo (){
		return $this->configuracion_plantilla['correo']['celda'];
	}

	private function agrega_telefono_reporte (){
		self::agrega_valor_celda( self::get_celda_telefono(), $_POST['telefono'] );
	}

	private function get_celda_telefono (){
		return $this->configuracion_plantilla['telefono']['celda'];
	}

	private function agrega_dependencia_reporte (){
		self::agrega_valor_celda( self::get_celda_dependencia(), $_POST['dependencia'] );
	}

	private function get_celda_dependencia (){
		return $this->configuracion_plantilla['dependencia']['celda'];
	}

	private function agrega_responsable_nombre_reporte (){
		$celdas_responsable_nombre = self::get_celdas_responsable_nombre();
		self::agrega_valor_celda( $celdas_responsable_nombre[ 0 ], $_POST['responsable_nombre'] );
		self::agrega_valor_celda( $celdas_responsable_nombre[ 1 ], $_POST['responsable_nombre'] );
		self::agrega_valor_celda( $celdas_responsable_nombre[ 2 ], $_POST['responsable_nombre'] );
	}

	private function get_celdas_responsable_nombre (){
		return $this->configuracion_plantilla['responsable_nombre']['celdas'];
	}

	private function agrega_responsable_puesto_reporte (){
		$celdas_responsable_puesto = self::get_celdas_responsable_puesto();
		self::agrega_valor_celda( $celdas_responsable_puesto[ 0 ], $_POST['responsable_puesto'] );
		self::agrega_valor_celda( $celdas_responsable_puesto[ 1 ], $_POST['responsable_puesto'] );
		self::agrega_valor_celda( $celdas_responsable_puesto[ 2 ], $_POST['responsable_puesto'] );
	}

	private function get_celdas_responsable_puesto (){
		return $this->configuracion_plantilla['responsable_puesto']['celdas'];
	}

	private function agrega_fecha_emision_reporte (){
		$fecha_emision_desglozada = explode( ' ', $_POST['fecha_emision'] );
		list( $dia, $mes, $anio ) = $fecha_emision_desglozada;

		$columnas = $this->configuracion_plantilla['fecha_emision']['columnas'];
		$posicion = $this->configuracion_plantilla['fecha_emision']['posicion'];

		self::agrega_valor_celda( sprintf( '%s%d', $columnas[ 0 ], $posicion ), $dia );
		self::agrega_valor_celda( sprintf( '%s%d', $columnas[ 1 ], $posicion ), $mes );
		self::agrega_valor_celda( sprintf( '%s%d', $columnas[ 2 ], $posicion ), $anio );
	}

	private function get_fecha_emision_desglozada ( $fecha_emision ){ //
		$tiempo_fecha_emision = strtotime( $fecha_emision );
		return array(
			  'dia'  => intval( date( 'd', $tiempo_fecha_emision ) )
			, 'mes'  => intval( date( 'm', $tiempo_fecha_emision ) )
			, 'anio' => intval( date( 'Y', $tiempo_fecha_emision ) )
		);
	}

	private function agrega_total_horas_acumuladas_reporte (){
		self::agrega_valor_celda( self::get_celda_total_horas_acumuladas(), self::get_total_horas_acumuladas_reporte() );
	}

	private function get_celda_total_horas_acumuladas (){
		return $this->configuracion_plantilla['total_horas_acumuladas']['celda'];
	}

	private function get_total_horas_acumuladas_reporte (){
		return intval( $_POST['total_horas_acumuladas_anterior'] ) + intval( $_POST['total_horas'] );
	}

	private function agrega_actividades_reporte (){
		$actividades = $this->configuracion_plantilla['actividades'];
		$columna = $actividades['columna'];
		$posicion = $actividades['posicion'];
		$numero_elementos = count( $_POST['actividad'] );

		for ( $contador = 0; $contador < $numero_elementos; $contador++, $posicion++ ){
			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion )
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
			case ERROR_CONSULTA:
				return sprintf( 'Problema en la consulta : %s', $error->getMessage() );
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