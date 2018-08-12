<?php

namespace Jida\Manager\Rutas\Procesador;

Trait Modulo {

    protected function _modulo () {

        $padre = $this->_padre;

        $parametro = $padre->proximoParametro();
        $posModulo = $this->_validarNombre($parametro, 'upper');

        if (in_array($posModulo, $padre->modulos) or array_key_exists($posModulo, $padre->modulos)) {

            $padre::$modulo = $posModulo;
            $padre::$ruta = 'app';
            if ($padre->jadmin) {

                $this->_namespace = $this->_namespaces['modulo'] . $padre::$modulo . '\\Jadmin\\Controllers\\';
            }
            else {
                $this->_namespace = $this->_namespaces['modulo'] . $padre::$modulo . '\\Controllers\\';
            }

        }
        else if ($padre->jadmin) {

            $padre::$ruta = 'jida';
            if ($this->_moduloJadmin($posModulo)) {

                $padre::$modulo = $posModulo;
                $this->_namespace = $this->_namespaces['jidaModulo'] . $posModulo . '\\Controllers\\';

            }
            else {
                $padre->reingresarParametro($posModulo);
                $this->_namespace = $this->_namespaces['jida'];
            }

        }
        else {
            $this->_namespace = $this->_namespaces['app'];
            $padre->reingresarParametro($posModulo);
        }

    }

    private function _moduloJadmin ($posModulo) {

        $modulo = $this->_validarNombre($posModulo, 'upper');

        return in_array($modulo, Jadmin::$modulos);

    }

}