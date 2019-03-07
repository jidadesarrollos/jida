<?php

namespace Jida\Core\Consola;

/**
 * Clase base para crear comandos 
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Console
 *
 */
class MotorDePlantillas {

    protected $smarty;

    public function __construct () {

        $this->smarty = new \Smarty();
        $this->smarty->compile_dir = $this->smarty->template_dir = __DIR__ . "/../../plantillas/codigosPHP/";
        $this->smarty->compile_dir .= 'CompiladosSmarty/';
        $this->smarty->assign('preNamespace', "");
        $this->smarty->assign('postNamespace', '');

    }

    public function asignar ($clave, $valor) {

        $this->smarty->assign($clave, $valor);

    }

    public function obt ($pantilla) {

        return $this->smarty->fetch($pantilla);

    }

}