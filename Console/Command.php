<?php

namespace Jida\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clase base para crear comandos 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Console
 *
 */
abstract class Command extends SymfonyCommand {

    const PathApp = 'Aplicacion';

    /**
     * directorio base de la ejecucion 
     * @var string 
     */
    protected $directorioDeProyecto;

    public function __construct(mixed $name = null) {
        parent::__construct($name);
        $this->directorioDeProyecto = realpath('.');
    }

    /**
     * En este metodo se deben definir las configuraciones de los comandos 
     */
    abstract protected function configurar();

    /**
     * En este metodo se debe definir la ejecucion del comando 
     * @param \Symfony\Component\Console\Input\InputInterface $input 
     * @param \Symfony\Component\Console\Output\OutputInterface $output 
     */
    abstract protected function ejecutar(InputInterface $input, OutputInterface $output);

    protected function configure() {
        $this->configurar();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->ejecutar($input, $output);
    }

}
