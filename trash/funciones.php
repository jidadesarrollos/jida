<?php
/**
 * Archivo de funciones generales del Framework
 *
 * Contiene funciones para acceso rapido y de entorno global que puedan ser accedidas desde cualquier segmento o capa
 * de las aplicaciones.
 * @since 1.4
 * @author @ark0soner
 *
 */

#=============================================================
/**
 * Funcion general del framework para acceso de informacion
 *
 * Permite acceder de forma rapida y sencilla a las variables de entorno global o sesion del framework.
 * @method JD
 *
 * @param string Nombre de la variable global a la que se desea acceder.
 * @param string $valor [opcional] si es pasado la variable sera creada o modificada si existe.
 * @example JD('Controller') //Retorna el controlador actual
 * @example JD('URL') //Retorna la url actual
 * @example JD('get') //Retorna los parametros get pasados por url.
 */

function JD ($param = "", $valor = null) {

    global $JD;
    if (empty($JD)) {
        // $JD = new JD();
        //Se crea un objeto standard. Si luego es considerado que debe agregarse
        //algun metodo debera definirse la clase
        $JD = new stdClass();
    }
    $totalparams = func_num_args();

    if ($totalparams > 1) {
        $JD->{$param} = $valor;
    }
    else {
        if (property_exists($JD, $param))
            return $JD->{$param};
    }
}

 


