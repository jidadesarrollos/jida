<?php

namespace Jida\Console;

use Symfony\Component\Console\Application;
use Jida\Console\Command;

/**
 * Clase principal para el manejo de comandos 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Console
 *
 */
class App extends Application {

    public function registarComandos() {

        $this->add(new Command\CrearModulos());
        $this->add(new Command\CrearControlador());
    }

}
