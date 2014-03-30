<?php
/**
 * Constantes y arreglos de configuración de Base de Datos
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * 
 */

/**
 * Constante para postgres
 */
define('PSQL','PostgreSQL');
/**
 * constante para mysql
 */
define('MySQL','MySQL');
/**
 * constante para mysql
 */
define('MSQL','MSQL');
/**
 * Define manejador de base de datos
 */
#define('manejadorBD','PSQL');
define('manejadorBD','MySQL');
 

$GLOBALS['conexiones'] = array(

        'default'=>array(
                            'puerto'=>"3306",
                            'usuario'=>'root',
                            'clave'=>'123456',
                            'bd'=>'wearerunning',
                            'servidor'=>'127.0.0.1',
                            ),
);



?>