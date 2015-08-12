<?php

define( 'CARPETA_CODIGO', '/opt/lampp/htdocs/');

define( 'URL_DB'    , '127.0.0.1' );
define( 'NOMBRE_BD' , 'reportess' );

define( 'USUARIO_SOLO_LECTURA'     , 'root');
define( 'CONTRASENIA_SOLO_LECTURA' , '');

define( 'USUARIO_EDICION'     , 'root');
define( 'CONTRASENIA_EDICION' , '');

define( 'CONEXION_SOLO_LECTURA' , 0 );
define( 'CONEXION_EDICION'      , 1 );

define( 'CONEXION_ESTATUS_SIN_CONEXION' , FALSE );
define( 'CONEXION_ESTATUS_CON_CONEXION' , TRUE );

define( 'ERROR_CONEXION'        , 100 );
define( 'ERROR_CONEXION_CERRAR' , 101 );
define( 'ERROR_PARAMETROS'      , 200 );
define( 'ERROR_PERIODO_DIAS'    , 201 );

define( 'LOG_MENSAJE_ERROR'  , 0 );
define( 'LOG_MENSAJE_PRUEBA' , 1 );
define( 'LOG_MENSAJE_NORMAL' , 2 );

define( 'NUMERO_DIA_SABADO'  , 6 );
define( 'NUMERO_DIA_VIERNES' , 5 );

define( 'LIMITE_DIAS_REPORTE' , 100 );

define( 'BD_TABLA_DIA_FESTIVO', 'dia_festivo' );

?>