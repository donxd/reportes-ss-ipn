<?

require_once('log.php');
require_once('conexion.php');

class funciones {

	private $log;
	private $dias_festivos;

	function __construct (){
		$this->log = new log();
	}

	function dias_reporte ( $tipo_dias, $periodo_tiempo ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- dias_reporte ---' );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- tipo_dias : %s ', $tipo_dias ) );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- fecha_inicio : %s ', $periodo_tiempo['fecha_inicio'] ) );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- fecha_cierre : %s ', $periodo_tiempo['fecha_cierre'] ) );

		$dias_festivos  = self::get_dias_festivos( $periodo_tiempo );
		$dias_periodo   = self::get_dias_periodo( $tipo_dias, $periodo_tiempo );
		$dias_reporte   = self::get_dias_reporte( $dias_periodo, $dias_festivos );
		
		return $dias_reporte;
	}

	function get_dias_festivos ( $periodo_tiempo ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- get_dias_festivos ---');
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha_inicio : %s ', $periodo_tiempo['fecha_inicio'] ) );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha_cierre : %s ', $periodo_tiempo['fecha_cierre'] ) );

		$baseDeDatos = new conexion();	
		$conexion    = $baseDeDatos->conectar( CONEXION_SOLO_LECTURA );

		$consulta = self::get_query_dias_festivos( $periodo_tiempo );
		$dias_festivos = self::get_registros( $consulta, $conexion );

		$baseDeDatos->cerrar( $conexion );
		return $dias_festivos;
	}

	private function get_query_dias_festivos ( $periodo_tiempo ){
		return sprintf(
			"SELECT
				  dias_ausueto.id_dia_festivo 
				, dias_ausueto.fecha 
				, dias_ausueto.descripcion 
			FROM 
				%s AS dias_ausueto 
			WHERE 
				dias_ausueto.fecha BETWEEN '%s' AND '%s' "
			, BD_TABLA_DIA_FESTIVO
			, date( 'Y-m-d', $periodo_tiempo['inicio'] )
			, date( 'Y-m-d', $periodo_tiempo['cierre'] )
		);
	}

	private function get_registros ( $consulta, $conexion ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- [ get_registros ] ---' );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf("<-- consulta : %s ", $consulta ) );
		
		$datos = array();
		$resultado = mysqli_query( $conexion, $consulta );
		if ( !is_bool( $resultado ) ){
			$numero_registros = mysqli_num_rows( $resultado );
			$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf("--- # registros :  %d ", $numero_registros ) );

			if ( $numero_registros != 0 ){
				while( $registros = mysqli_fetch_array( $resultado ) ){
					array_push( $datos, $registros );
				}
			}
		}
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf("---> %s ", json_encode( $datos ) ) );
		return $datos;
	}

	private function get_dias_periodo ( $tipo_dias, $periodo_tiempo ){
		$this->log->registrar( LOG_MENSAJE_PRUEBA, '--- dias_periodo ---');
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- tipo_dias : %s ', $tipo_dias ) );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha_inicio : %s ', $periodo_tiempo['fecha_inicio'] ) );
		$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- fecha_cierre : %s ', $periodo_tiempo['fecha_cierre'] ) );

		$dias_periodo = array();
		$dia_inicio = self::get_numero_dia_semana( $periodo_tiempo['inicio'] );
		$numero_dias_periodo = self::get_distancia_dias( $periodo_tiempo['cierre'], $periodo_tiempo['inicio'] ) + 1;
		if ( $numero_dias_periodo < LIMITE_DIAS_REPORTE ){

			$tiempo_dia = $periodo_tiempo[ 'inicio' ];
			
			for ( $contador = 0; $contador < $numero_dias_periodo; $contador++ ){

				$numero_dia_semana = date( 'N', $tiempo_dia );
				if ( self::valida_dia_semana_reporte( $tipo_dias, $numero_dia_semana ) ){
					array_push( $dias_periodo, date( 'd-m-Y', $tiempo_dia ) );
				}
				$tiempo_dia = strtotime( '+1 day', $tiempo_dia );
			}

			$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf('--- dias_periodo : # dias : %d ', count( $dias_periodo ) ) );
			return $dias_periodo;
		} else {
			throw new Exception( NULL, ERROR_PERIODO_DIAS );
		}
	}

	private function get_numero_dia_semana ( $tiempo  ){
		return intval( date('d', $tiempo ) );
	}

	private function get_distancia_dias ( $tiempo_cierre, $tiempo_inicio ){
		$diferencia_tiempo = $tiempo_cierre - $tiempo_inicio;
		return intval( $diferencia_tiempo / ( 60 * 60 * 24 ) );
	}

	private function valida_dia_semana_reporte ( $tipo_dias, $numero_dia_semana ){
		switch ( $tipo_dias ){
			case 'es': //entre semana
				return ( $numero_dia_semana < NUMERO_DIA_SABADO );
			case 'fs': //fines de semana
				return ( $numero_dia_semana > NUMERO_DIA_VIERNES );
			default :
				throw new Exception( NULL, ERROR_PARAMETROS );
				break;
		}
	}

	private function get_dias_reporte ( $dias_periodo, $dias_festivos ){
		$dias_reporte = array();
		$this->dias_festivos = $dias_festivos;

		foreach ( $dias_periodo as $dia_periodo ){			
			array_push( $dias_reporte , array(
					  'fecha'             => $dia_periodo
					, 'numero_dia_semana' => date( 'N', strtotime( $dia_periodo ) )
					, 'festivo'           => self::verifica_dia_festivo( $dia_periodo )
				) 
			);
		}
		return $dias_reporte;
	}

	function verifica_dia_festivo ( $dia ){
		foreach ( $this->dias_festivos as $dia_festivo ){
			if ( strtotime( $dia ) == strtotime( $dia_festivo[ 'fecha' ] ) ){
				$this->log->registrar( LOG_MENSAJE_PRUEBA, ' --- verifica_dia_festivo ---');
				$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- dia : %s ', $dia ) );
				$this->log->registrar( LOG_MENSAJE_PRUEBA, sprintf( '--- dia_festivo : %s ', $dia_festivo[ 'fecha' ] ) );
				return TRUE;
			}
		}
		return FALSE;
	}

}
?>