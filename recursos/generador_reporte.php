<?php

require_once( 'configuracion.php' );
require_once( 'log.php' );
require_once( 'funciones.php' );

$reporte = new reporte();
$reporte->genera_reporte();

class reporte {

	private $log;
	private $objetoPHPExcel;
	private $configuracion_plantilla;
	private $TBS;
	private $LIMITE_VARIABLES = 30;
	private $LIMITE_VARIABLES_ACTIVIDADES = 5;

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
			case 'escom':
				self::inicializa_plantilla_escom();
				break;
		}
	}

	private function inicializa_plantilla_upiicsa (){
		$this->configuracion_plantilla = self::get_configuracion_plantilla_upiicsa();
	}

	private function get_configuracion_plantilla_upiicsa (){
		return array(
			  'nombre_archivo' => 'upiicsa.xlsx'
			, 'tipo_plantilla' => 'excel'
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
			, 'tipo_plantilla' => 'excel'
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

	private function inicializa_plantilla_escom (){
		$this->configuracion_plantilla = self::get_configuracion_plantilla_escom();
	}

	private function get_configuracion_plantilla_escom (){
		return array(
			  'nombre_archivo' => 'escom.docx'
			, 'tipo_plantilla' => 'word'
			, 'numero_reporte' => 'nuR'
			, 'boleta'         => 'nuB'
			, 'carrera'        => 'txCar'
			// , 'egresado'       => ''
			// , 'egresado_si'    => 'ckEY'
			// , 'egresado_no'    => 'ckEN'
			, 'semestre'       => 'nuS'
			, 'grupo'          => 'nuG'
			, 'dependencia'    => 'txRep'
			, 'nombre_alumno'  => 'nombreAlumno'
			, 'mes'            => 'fhMR'
			, 'responsable_nombre' => 'txJe'
			, 'responsable_puesto' => 'txRCar'
			, 'actividades' => ''
			, 'actividad_1' => 'txA1'
			, 'actividad_2' => 'txA2'
			, 'actividad_3' => 'txA3'
			, 'actividad_4' => 'txA4'
			, 'actividad_5' => 'txA5'
			, 'dia_emision'  => 'fhDE'
			, 'mes_emision'  => 'fhME'
			, 'anio_emision' => 'fhAE'			
			, 'total_horas'            => 'tHM'
			, 'total_horas_acumuladas' => 'tHA'
			, 'total_horas_final'      => 'tHF'
			, 'fechas_dias'  => ''
			, 'hora_entrada' => ''
			, 'hora_salida'  => ''
			, 'horas_dia'    => ''
		);
		// correo
		// telefono
	}

	private function crea_archivo_reporte (){
		switch ( $this->configuracion_plantilla[ 'tipo_plantilla' ] ){
			case 'excel' : self::reporte_excel(); break;
			case 'word'  : self::reporte_word(); break;
		}
	}

	private function reporte_excel (){
		require_once( '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php' );
		
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- crea_archivo_reporte ---');
		$objPHPExcel = self::leer_plantilla_reporte();
		self::rellena_plantilla_reporte( $objPHPExcel );
		self::envia_archivo( $objPHPExcel );
	}

	private function reporte_word (){
		require_once( '../lib/tbs/tbs_class.php' );
		require_once( '../lib/tbs/tbs_plugin_opentbs.php' );

		self::prepara_tbs();
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- crea_archivo_reporte ---');
		self::vacia_informacion_variables_reporte();
		self::envia_archivo_tbs();
	}

	private function vacia_informacion_variables_reporte (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- informacion reporte escom --- %s', json_encode( $_POST ) ) );

		self::inicializa_variables_plantilla();
		$dias_fechas = json_decode( $_POST['dias'] );
		$numero_fechas = count( $dias_fechas );

		foreach ( $this->configuracion_plantilla as $seccion => $variable_destino){
			switch ( $seccion ){

				case 'semestre'               :
				case 'grupo'                  :
				case 'nombre_alumno'          :
				case 'responsable_nombre'     :
				case 'mes'                    :
				case 'total_horas'            : 
				case 'numero_reporte'         : 
				case 'carrera'                : 
				case 'boleta'                 : 
				case 'dependencia'            : 
				case 'nombre_alumno'          : 
				case 'responsable_nombre'     : 
				case 'responsable_puesto'     : self::vacia_valor_variable( $seccion, $variable_destino ); break; 
				case 'total_horas_acumuladas' : self::vacia_total_horas_acumuladas(); break;
				case 'total_horas_final'      : self::vacia_total_horas_final();
				case 'fecha_emision'          : self::vacia_fecha_emision(); break;
				case 'actividades'            : self::vacia_actividades(); break;
				// case 'egresado'               : self::vacia_egresado(); break;
				case 'fechas_dias'            : self::vacia_fechas_dias_variables( $numero_fechas, $dias_fechas ); break;
				case 'hora_entrada'           : self::vacia_hora_entrada_variables( $numero_fechas, $dias_fechas ); break;
				case 'hora_salida'            : self::vacia_hora_salida_variables( $numero_fechas, $dias_fechas ); break;
				case 'horas_dia'              : self::vacia_horas_dia_variables( $numero_fechas, $dias_fechas ); break;
				default                       : self::informacion_extra( $seccion ); break;
			}
		}
	}

	private function inicializa_variables_plantilla (){
		
		$GLOBALS[ 'nuR' ]  = '';
		$GLOBALS[ 'nuB' ]  = '';
		$GLOBALS[ 'nuS' ]  = '';
		$GLOBALS[ 'nuG' ]  = '';
		$GLOBALS[ 'tHM' ]  = '';
		$GLOBALS[ 'tHA' ]  = '';
		$GLOBALS[ 'tHF' ]  = '';
		$GLOBALS[ 'ckEN' ] = '';
		$GLOBALS[ 'ckEY' ] = '';
		$GLOBALS[ 'txJe' ] = '';
		$GLOBALS[ 'fhDE' ] = '';
		$GLOBALS[ 'fhME' ] = '';
		$GLOBALS[ 'fhAE' ] = '';
		$GLOBALS[ 'fhMR' ] = '';
		$GLOBALS[ 'txRep' ] = '';
		$GLOBALS[ 'txCar' ] = '';		
		$GLOBALS[ 'nombreAlumno' ] = '';

		for ( $contador = 1; $contador <= $this->LIMITE_VARIABLES_ACTIVIDADES; $contador++ ){
			$GLOBALS[ 'txA' . $contador ]  = '';	
		}
		
		for ( $contador = 1; $contador <= $this->LIMITE_VARIABLES; $contador++ ){
			$GLOBALS[ 'fhRp' . $contador ]  = '';	
			$GLOBALS[ 'hERp' . $contador ]  = '';	
			$GLOBALS[ 'hSRp' . $contador ]  = '';	
			$GLOBALS[ 'hDRp' . $contador ]  = '';	
		}
		
	}

	private function informacion_extra ( $seccion ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- informacion_extra [ %s ] ', $seccion ) );
	}

	private function vacia_valor_variable ( $seccion, $variable_destino ){
		$GLOBALS[ $variable_destino ] = $_POST[ $seccion ];
	}

	private function vacia_total_horas_acumuladas (){
		$GLOBALS[ $this->configuracion_plantilla[ 'total_horas_acumuladas' ] ]  = $_POST[ 'total_horas_acumuladas_anterior' ];
	}

	private function vacia_total_horas_final (){
		$GLOBALS[ $this->configuracion_plantilla[ 'total_horas_final' ] ]  = $_POST[ 'total_horas_acumuladas_anterior' ] + $_POST[ 'total_horas' ];
	}

	private function vacia_fecha_emision (){

		$fecha_emision_desglozada = explode( ' ', $_POST['fecha_emision'] );
		list( $dia, $mes, $anio ) = $fecha_emision_desglozada;

		$GLOBALS[ $this->configuracion_plantilla[ 'dia_emision' ]  ] = $dia;
		$GLOBALS[ $this->configuracion_plantilla[ 'mes_emision' ]  ] = $mes;
		$GLOBALS[ $this->configuracion_plantilla[ 'anio_emision' ] ] = $anio;
	}

	private function vacia_actividades (){
		$contador = 1;
		foreach ( $_POST[ 'actividad' ] as $valor ){
			$GLOBALS[ $this->configuracion_plantilla[ 'actividad_' . $contador ] ] = $valor;
			$contador++;
		}
		// while ( $contador < 6 ){
		// 	$GLOBALS[ $this->configuracion_plantilla[ 'activdad_' . $contador ] ] = '';
		// 	$contador++;
		// }
	}

	private function vacia_egresado (){
		$valor_egresado = $_POST[ 'egresado' ];
		if ( $valor_egresado == 'y' ){
			// $this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- posicion egresado si [ %s ] ', $this->configuracion_plantilla[ 'egresado_si' ] ) );
			// $this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- posicion egresado no [ %s ] ', $this->configuracion_plantilla[ 'egresado_no' ] ) );

			$GLOBALS[ $this->configuracion_plantilla[ 'egresado_si' ] ] = 'X';
			$GLOBALS[ $this->configuracion_plantilla[ 'egresado_no' ] ] = '';
		} else {
			$GLOBALS[ $this->configuracion_plantilla[ 'egresado_si' ] ] = '';
			$GLOBALS[ $this->configuracion_plantilla[ 'egresado_no' ] ] = 'X';
		}
	}

	private function vacia_fechas_dias_variables ( $numero_fechas, $dias_fechas ){

		$variable = 'fhRp';
		for ( $i = 0; $i < $this->LIMITE_VARIABLES && $i < $numero_fechas; $i++ ){
			$GLOBALS[ $variable . ( $i + 1 ) ] = $dias_fechas[ $i ]->fecha;
		}

	}

	private function vacia_hora_entrada_variables ( $numero_fechas, $dias_fechas ){
		self::vacia_hora_entrada_salida_variables( $numero_fechas, $dias_fechas, 'hora_entrada', 'hERp' );
	}

	private function vacia_hora_entrada_salida_variables ( $numero_fechas, $dias_fechas, $seccion, $variable_destino ){

		$variable = $variable_destino;
		for ( $i = 0; $i < $this->LIMITE_VARIABLES && $i < $numero_fechas; $i++ ){
			
			$tiempo = $_POST[ $seccion ];
			if ( $dias_fechas[ $i ]->festivo != false ){
				$tiempo = self::determina_valor_hora_entrada_salida( $seccion, $dias_fechas[ $i ]->festivo );
			}

			$GLOBALS[ $variable . ( $i + 1 ) ] = $tiempo;
		}

	}

	private function determina_valor_hora_entrada_salida ( $seccion, $valor_dia_festivo ){
		switch ( $seccion ){
			case 'hora_entrada' : return self::determina_valor_hora_entrada( $valor_dia_festivo );
			case 'hora_salida'  : return self::determina_valor_hora_salida( $valor_dia_festivo );
		}
	}

	private function determina_valor_hora_entrada ( $valor_dia_festivo ){
		switch ( $valor_dia_festivo ){
			case 'DIA FESTIVO'           : return 'DIA';
			case 'SUSPENCION DE LABORES' : return 'SUSPENCION';
		}
	}

	private function determina_valor_hora_salida ( $valor_dia_festivo ){
		switch ( $valor_dia_festivo ){
			case 'DIA FESTIVO'           : return 'FESTIVO';
			case 'SUSPENCION DE LABORES' : return 'DE LABORES';
		}
	}

	private function vacia_hora_salida_variables ( $numero_fechas, $dias_fechas ){
		self::vacia_hora_entrada_salida_variables( $numero_fechas, $dias_fechas, 'hora_salida', 'hSRp');
	}

	private function vacia_horas_dia_variables ( $numero_fechas, $dias_fechas ){

		$variable = 'hDRp';
		for ( $i = 0; $i < $this->LIMITE_VARIABLES && $i < $numero_fechas; $i++ ){
			
			$horas_dia = $_POST['horas_dia'];
			if ( $dias_fechas[ $i ]->festivo != false ){
				$horas_dia = '';
			}

			$GLOBALS[ $variable . ( $i + 1 ) ] = $horas_dia;

		}

	}

	private function envia_archivo_tbs (){
		$plantilla = $this->configuracion_plantilla[ 'nombre_archivo' ];
		$this->TBS->LoadTemplate( self::get_ruta_archivo_plantilla(), OPENTBS_ALREADY_UTF8 );
		$this->TBS->Show( OPENTBS_DOWNLOAD, self::get_nombre_archivo_tbs( $plantilla ) );
	}

	private function get_nombre_archivo_tbs ( $plantilla ){
		return str_replace( '.', '_'.date('Y-m-d').'.', $plantilla );
	}

	private function prepara_tbs (){
		if ( version_compare( PHP_VERSION , '5.1.0' ) >= 0 ){
			if ( ini_get('date.timezone') == '' ) {
				date_default_timezone_set( 'UTC' );
			}
		}

		$this->TBS = new clsTinyButStrong;
		$this->TBS->Plugin( TBS_INSTALL, OPENTBS_PLUGIN );
	}

	private function leer_plantilla_reporte (){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- leer_plantilla_reporte ---');
		$ruta_archivo = self::get_ruta_archivo_plantilla();
		if ( file_exists( $ruta_archivo ) ){
			$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- archivo encontrado ---');
			
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
		self::aplica_configuracion_plantilla();
	}

	private function aplica_configuracion_plantilla (){
		
		foreach ( $this->configuracion_plantilla as $seccion => $valor ){
			switch ( $seccion ){

				case 'mes'                    : 
				case 'total_horas'            : 
				case 'numero_reporte'         : 
				case 'carrera'                : 
				case 'boleta'                 : 
				case 'correo'                 : 
				case 'telefono'               : 
				case 'dependencia'            : self::agrega_valor_celda_seccion( $seccion, $valor[ 'celda' ] ); break;
				case 'hora_entrada'           : self::agrega_hora_entrada_reporte(); break;
				case 'hora_salida'            : self::agrega_hora_salida_reporte(); break;
				case 'fechas_dias'            : self::agrega_fechas_dias_reporte(); break;
				case 'horas_dia'              : self::agrega_horas_dia_reporte(); break;
				case 'actividades'            : self::agrega_actividades_reporte(); break;
				case 'fecha_emision'          : self::agrega_fecha_emision_reporte(); break;
				case 'total_horas_acumuladas' : self::agrega_total_horas_acumuladas_reporte(); break; 
				case 'periodo_inicio'         : self::agrega_periodo_inicio_reporte(); break;
				case 'periodo_cierre'         : self::agrega_periodo_cierre_reporte(); break;
				case 'nombre_alumno'          : self::agrega_nombre_alumno_reporte(); break;
				case 'responsable_nombre'     : self::agrega_responsable_nombre_reporte(); break;
				case 'responsable_puesto'     : self::agrega_responsable_puesto_reporte(); break;


				// case 'mes'                    : self::agrega_mes_reporte(); break;
				// case 'total_horas'            : self::agrega_total_horas_reporte(); break;
				// case 'numero_reporte'         : self::agrega_numero_reporte_reporte(); break;
				// case 'carrera'                : self::agrega_carrera_reporte(); break;
				// case 'boleta'                 : self::agrega_boleta_reporte(); break;
				// case 'correo'                 : self::agrega_correo_reporte(); break;
				// case 'telefono'               : self::agrega_telefono_reporte(); break;
				// case 'dependencia'            : self::agrega_dependencia_reporte(); break;
				// case 'hora_entrada'           : self::agrega_hora_entrada_reporte(); break;
				// case 'hora_salida'            : self::agrega_hora_salida_reporte(); break;
				// case 'fechas_dias'            : self::agrega_fechas_dias_reporte(); break;
				// case 'horas_dia'              : self::agrega_horas_dia_reporte(); break;
				// case 'actividades'            : self::agrega_actividades_reporte(); break;
				// case 'fecha_emision'          : self::agrega_fecha_emision_reporte(); break;
				// case 'nombre_alumno'          : self::agrega_nombre_alumno_reporte(); break;
				// case 'responsable_nombre'     : self::agrega_responsable_nombre_reporte(); break;
				// case 'responsable_puesto'     : self::agrega_responsable_puesto_reporte(); break;
				// case 'total_horas_acumuladas' : self::agrega_total_horas_acumuladas_reporte(); break;
				// case 'periodo_inicio'         : self::agrega_periodo_inicio_reporte(); break;
				// case 'periodo_cierre'         : self::agrega_periodo_cierre_reporte(); break;
			}
		}
	}

	private function agrega_valor_celda_seccion ( $seccion, $celda ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- seccion [ %s ] = %s ', $seccion, $celda ) );
		self::agrega_valor_celda( $celda, $_POST[ $seccion ] );
	}

	private function agrega_mes_reporte (){
		self::agrega_valor_celda( self::get_celda_mes(), $_POST['mes'] );
	}

	private function get_celda_mes (){
		return $this->configuracion_plantilla['mes']['celda'];
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
		
		$hora_entrada    = $this->configuracion_plantilla['hora_entrada'];
		$columna         = $hora_entrada['columna'];
		$posicion_inicio = $hora_entrada['posicion'];
		
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){
			
			$contenido_celda = $_POST['hora_entrada'];
			// $this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- agrega_hora_entrada_reporte : %s ', $dia->festivo ) );
			
			if ( $dia->festivo != false ){
				if ( $dia->festivo == 'DIA FESTIVO' ){
					$contenido_celda = 'DIA';
				}

				if ( $dia->festivo == 'SUSPENCION DE LABORES' ){
					$contenido_celda = 'SUSPENCION';
				}
			}

			$this->objetoPHPExcel->getActiveSheet()->setCellValue( 
				sprintf( '%s%d', $columna, $posicion_inicio )
				, $contenido_celda
			);
			$posicion_inicio++;
		}
	}

	private function agrega_hora_salida_reporte (){
		
		$hora_salida     = $this->configuracion_plantilla['hora_salida'];
		$columna         = $hora_salida['columna'];
		$posicion_inicio = $hora_salida['posicion'];
		
		$dias = json_decode( $_POST['dias'] );

		foreach ( $dias as $dia ){

			$contenido_celda = $_POST['hora_salida'];
			// $this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- agrega_hora_salida_reporte : %s ', $dia->festivo ) );

			if ( $dia->festivo != false ){
				if ( $dia->festivo == 'DIA FESTIVO' ){
					$contenido_celda = 'FESTIVO';
				}

				if ( $dia->festivo == 'SUSPENCION DE LABORES' ){
					$contenido_celda = 'DE LABORES';
				}
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
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- [ %s ] = %s ', $celda, $valor ) );
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
		self::vacia_celdas_valor( self::get_celdas_nombre_alumno(), $_POST['nombre_alumno'] );
	}

	private function vacia_celdas_valor ( $celdas, $valor ){
		foreach ( $celdas as $celda ){
			self::agrega_valor_celda( $celda, $valor );
		}
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
		self::vacia_celdas_valor( self::get_celdas_responsable_nombre(), $_POST['responsable_nombre'] );
	}

	private function get_celdas_responsable_nombre (){
		return $this->configuracion_plantilla['responsable_nombre']['celdas'];
	}

	private function agrega_responsable_puesto_reporte (){
		self::vacia_celdas_valor( self::get_celdas_responsable_puesto(), $_POST['responsable_puesto'] );
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