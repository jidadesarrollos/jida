<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Rutas\Jadmin;
use Jida\Medios\Debug;

Trait Modulo {

    private $_namespaces = [
        'app'        => 'App\\Controllers\\',
        'modulo'     => 'App\\Modulos\\',
        'jida'       => '\\Jida\\Jadmin\\Controllers\\',
        'jidaModulo' => '\\Jida\\Jadmin\\Modulos\\',
        'jadminApp'  => '\\App\Jadmin\\'

    ];
    private $_namespace;

    protected function _modulo() {

        $padre = $this->_padre;

        $parametro = $padre->proximoParametro();

        $posModulo = $this->_validarNombre($parametro, 'upper');

        if ($posModulo and
            (in_array($posModulo, $padre->modulos) or
             array_key_exists($posModulo, $padre->modulos))
        ) {

            Estructura::$modulo = $posModulo;
            $padre::$ruta = 'app';
            $namespace = $this->_namespaces['modulo'] . Estructura::$modulo;
            $namespace .= ($padre->jadmin) ? '\\Jadmin\\Controllers\\' : '\\Controllers\\';
            $rutaModulo = Estructura::$directorio . DS . 'Aplicacion' . DS . 'Modulos' . DS . $posModulo;
            $rutaModulo .= ($padre->jadmin) ? DS . 'Jadmin' : '';

        }

        else if (Estructura::$jadmin) {

            $namespace = $this->_namespaces['jadminApp'] . "Controllers\\";

            if (!$this->_moduloJadmin($posModulo) and
                class_exists($namespace . ucfirst($posModulo))) {

                $padre::$ruta = 'app';
                $rutaModulo = Estructura::$directorio . DS . 'Aplicacion' . DS . 'Jadmin';

                $padre->reingresarParametro($posModulo);
            }
            else {

                $padre::$ruta = 'jida';

                if ($posModulo and $this->_moduloJadmin($posModulo)) {
                    Estructura::$modulo = $posModulo;
                    $namespace = $this->_namespaces['jidaModulo'] . $posModulo . '\\Controllers\\';
                    $rutaModulo = Estructura::$rutaJida . DS . 'Jadmin' . DS . "Modulos/{$posModulo}";
                }
                else {
                    $padre->reingresarParametro($posModulo);
                    $namespace = $this->_namespaces['jida'];
                    $rutaModulo = Estructura::$rutaJida . DS . 'Jadmin';
                }

            }

        }
        else {
            $namespace = $this->_namespaces['app'];
            $rutaModulo = Estructura::$directorio . DS . "Aplicacion";
            $padre->reingresarParametro($posModulo);
        }

        Estructura::$namespace = $namespace;
        Estructura::$rutaModulo = $rutaModulo;

    }

    private function _moduloJadmin($posModulo) {

        $modulo = $this->_validarNombre($posModulo, 'upper');
        $config = Config::obtener();

        return in_array($modulo, Jadmin::$modulos);

    }

}