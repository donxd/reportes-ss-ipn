<?php

require_once('configuracion.php');
require_once('log.php');

class conexion {

	private $servidor;
	private $usuario;
	private $contrasenia;
	private $nombre_bd;
	
	private $enlace;
	private $estado_conexion;
	private $tipo_conexion;

	function __construct (){
		$this->servidor = URL_DB;
		$this->nombre_bd = NOMBRE_BD;

		$this->estado_conexion = CONEXION_ESTATUS_SIN_CONEXION;
	}

	function conectar ( $tipo_conexion ){
		$this->tipo_conexion = $tipo_conexion;
		self::realiza_conexion_bd();
		return $this->enlace;
	}

	private function realiza_conexion_bd (){
		try {
			self::selecciona_tipo_conexion();
			self::conecta_bd();
			self::set_estado_conexion( CONEXION_ESTATUS_CON_CONEXION );
		} catch ( Exception $error ){
			self::procesa_error( $error );
			$this->enlace = NULL;
		}
	}

	private function selecciona_tipo_conexion (){
		switch ( $this->tipo_conexion ) {
			case CONEXION_EDICION :
				$this->usuario = USUARIO_SOLO_LECTURA;
				$this->contrasenia = CONTRASENIA_SOLO_LECTURA;
				break;
			case CONEXION_SOLO_LECTURA :
			default:
				$this->usuario = USUARIO_EDICION;
				$this->contrasenia = CONTRASENIA_EDICION;
				break;
		}
	}

	private function conecta_bd (){
		$this->enlace = mysqli_connect( $this->servidor, $this->usuario, $this->contrasenia, $this->nombre_bd );
		if ( is_bool( $this->enlace ) )
			throw new Exception( mysqli_connect_error(), ERROR_CONEXION );

		mysqli_set_charset( $this->enlace, 'utf8' );
	}

	private function set_estado_conexion ( $valor ){
		$this->estado_conexion = $valor;
	}

	private function procesa_error (){
		$log = new log();
	}
	
	function cerrar ( &$enlace ){
		if ( !mysqli_close( $enlace ) )
			throw new Exception( 'No se pudo cerrar la conexión', ERROR_CONEXION_CERRAR );
	}

}