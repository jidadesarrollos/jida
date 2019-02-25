<?php

namespace Jida\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * clase abstacta para crear clases comandos 
 * @category    framework
 * @package     Console
 * @author      Enyerber Franco <enyerverfranco@gmail.com>
 */
abstract class Command extends SymfonyCommand {

    const PathApp = 'Aplicacion';

    protected $directorioDeProyecto;

    public function __construct(mixed $name = null) {
        parent::__construct($name);
        $this->directorioDeProyecto = realpath('.');
    }

    abstract protected function configurar();

    abstract protected function ejecutar(InputInterface $input, OutputInterface $output);

    protected function configure() {
        $this->configurar();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->ejecutar($input, $output);
    }

}
