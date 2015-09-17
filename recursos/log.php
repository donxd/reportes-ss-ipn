<?
class log {
	private $direccion_archivo;
	private $enlace_archivo;

	private $categoria;
	private $mensaje;

	private $tipo_categoria;

	function __construct ( $nombre_logger = NULL ){
		if ( is_null( $nombre_logger ) )
			$this->direccion_archivo = sprintf('log[ %s ].txt', date('Y-m-d') );
		else 
			$this->direccion_archivo = $nombre_logger;
	}

	function registrar ( $categoria, $mensaje ){

		$this->categoria = $categoria;
		$this->mensaje = $mensaje;

		self::abrir_archivo();
		self::escribir_mensaje();
		self::cerrar_archivo();
	}

	private function abrir_archivo (){
		$this->enlace_archivo = @fopen( $this->direccion_archivo, 'a+' );
	}

	private function escribir_mensaje (){
		$tipo = self::get_mensaje_categoria( $this->categoria );
		@fwrite( $this->enlace_archivo, sprintf(
				"\n%s%s%s"
				, date('d-m-Y H:i:s')
				, $tipo
				, str_replace( array( "\n", "\t") , '', $this->mensaje )
			) 
		);
	}

	private function get_mensaje_categoria ( $categoria ){
		switch($categoria){
			case LOG_MENSAJE_ERROR:
				return "\t[-Error-------]\t";
			case LOG_MENSAJE_PRUEBA:
				return "\t[-Prueba------]\t";
			case LOG_MENSAJE_NORMAL:
				return "\t[-Advertencia-]\t";
		}
	}

	private function cerrar_archivo (){
		@fclose( $this->enlace_archivo );
	}
}
?>