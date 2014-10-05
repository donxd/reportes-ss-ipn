<?php
class conexion{
	
	function ConectarBD ($usuario){
		$servidor = "127.0.0.1";
		
		// $user = "usuario";
		// if (!$usuario){
			$user = "root";
		// }
		$pass = "";
		$nombre = "reportess";

		$dblink;
		
		$link = mysql_connect($servidor,$user,$pass) or die ("No se pudo conectar con la base de datos");
		mysql_select_db($nombre,$link) or die ("No se encontr&oacute; la base de datos");
		
		$this->dblink = $link;
		return $this->dblink;
	} //ConectarBD
	
	function CerrarBD ($kill){	
		mysql_close($kill);
		$this->closelink = $kill;
	} //CerrarBD
} //conexion