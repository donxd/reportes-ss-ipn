<?
class log {
	private $archivo;
	function log (){
		$this->archivo = "log.txt";
	}
	function registrar ($categoria, $mensaje){
		@$enlace = fopen($this->archivo, "a+");
		$tipo;
		switch($categoria){
			case 0:
				$tipo = "\t[-Error-------]\t";
				break;
			case 1:
				$tipo = "\t[-Prueba------]\t";
				break;
			case 2:
				$tipo = "\t[-Advertencia-]\t";
				break;
		}
		@fwrite($enlace, "\n".date("d-m-Y H:i:s").$tipo.$mensaje);
		@fclose($enlace);
	}
}
?>