<?php

define( 'CARPETA_CODIGO', '/opt/lampp/htdocs/');

define( 'URL_DB'    , '127.0.0.1' );
define( 'NOMBRE_BD' , 'donxd_reportess' );

define( 'USUARIO_SOLO_LECTURA'     , 'donxd_reportess');
define( 'CONTRASENIA_SOLO_LECTURA' , 'NJE73V6OMD');

define( 'USUARIO_EDICION'     , 'donxd_reportess');
define( 'CONTRASENIA_EDICION' , 'NJE73V6OMD');

define( 'CONEXION_SOLO_LECTURA' , 0 );
define( 'CONEXION_EDICION'      , 1 );

define( 'CONEXION_ESTATUS_SIN_CONEXION' , FALSE );
define( 'CONEXION_ESTATUS_CON_CONEXION' , TRUE );

define( 'ERROR_CONEXION'        , 100 );
define( 'ERROR_CONEXION_CERRAR' , 101 );
define( 'ERROR_CONSULTA'        , 102 );
define( 'ERROR_PARAMETROS'      , 200 );
define( 'ERROR_PERIODO_DIAS'    , 201 );
define( 'ERROR_ARCHIVO'         , 202 );

define( 'LOG_MENSAJE_ERROR'  , 0 );
define( 'LOG_MENSAJE_PRUEBA' , 1 );
define( 'LOG_MENSAJE_NORMAL' , 2 );

define( 'NUMERO_DIA_SABADO'  , 6 );
define( 'NUMERO_DIA_VIERNES' , 5 );

define( 'LIMITE_DIAS_REPORTE' , 100 );

define( 'BD_TABLA_DIA_FESTIVO', 'dia_festivo' );
define( 'BD_TABLA_INFORMACION', 'informacion' );

?>