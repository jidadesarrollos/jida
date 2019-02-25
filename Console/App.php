<?php

namespace Jida\Console;

use Symfony\Component\Console\Application;
use Jida\Console\Command;

/**
 * Clase principal para el manejo de comandos 
 * @category    framework
 * @package     Console
 * @author      Enyerber Franco <enyerverfranco@gmail.com>
 */
class App extends Application {

    public function registarComandos() {
        $this->add(new Command\Test());
        $this->add(new Command\CrearModulos());
        $this->add(new Command\CrearControlador());
    }

}
