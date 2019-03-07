<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Valida que una fecha sea posterior a la indicada en el primer parametro de la regla
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rdespues_de extends Regla {

    public $errorMsj = "{:attr} debe ser posterior a {:param[0]}";

    public function validar($value, array $parametros):bool {

        if (!($value instanceof \DateTime)) {
            try {
                $date = new \DateTime($value);
            }
            catch (\Exception $ex) {
                return false;
            }
        }
        else {
            $date = $value;
        }

        $actual  = $date->getTimestamp();
        $despues = (new \DateTime($parametros[0]))->getTimestamp();
        return !($actual <= $despues);
    }

}
