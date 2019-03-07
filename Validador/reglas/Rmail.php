<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla para validar mails
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rmail extends Regla {

    public $errorMsj    = " {:attr} debe ser contener un email valido ";
    protected $multiple = false;

    public function validar($value, array $parametros): bool {
        if ($parametros[0] == 'multiple') {
            $this->multiple = true;
            $ex             = explode(',', $value);
            foreach ($ex as $v) {
                if (!filter_var($v, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }
            }
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function processValue($value, array $parametros) {
        if ($parametros[0] == 'multiple') {
            $ret = [];
            foreach (explode(',', $value) as $v) {
                $ret[] = filter_var($v, FILTER_SANITIZE_EMAIL);
            }
            return $ret;
        }
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }

}
