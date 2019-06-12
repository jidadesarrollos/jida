<?php

namespace Jida\Core\Consola;

use Symfony\Component\Console\Application;
use Jida\Core\Consola\Comandos;

/**
 * Clase principal para el manejo de comandos
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Consola
 *
 */
class App extends Application {

    public function registarComandos($path) {

        $this->add(new Comandos\CrearModulo($path));
        $this->add(new Comandos\CrearControlador($path));
        $this->add(new Comandos\InstaladorBd($path));
        $this->add(new Comandos\ConfiguradorBD($path));

    }

}