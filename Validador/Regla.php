<?php

namespace Jida\Validador;

/**
 * Clase base para las reglas del validador 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
abstract class Regla {

    /**
     * Mensaje de error 
     * @var string 
     */
    public $errorMsj = " ";
    public $reglas;

    /**
     * debe validar el valor pasado en el primer parametro
     * @param mixes $value valor 
     * @param array $parametros parametros recibidos para la regla 
     * @return boolean si el valor es valido debe retornar true
     */
    abstract public function validar($value, array $parametros): bool;

    /**
     * Sanitiza el valor validado con la regla 
     * @param mixes $value
     * @param array $parametros parametros recibidos para la regla 
     * 
     */
    public function processValue($value, array $parametros) {
        return $value;
    }

}
