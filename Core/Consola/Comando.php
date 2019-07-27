<?php

namespace Jida\Core\Consola;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clase base para crear comandos
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Console
 *
 */
abstract class Comando extends Command {

    const PathApp = 'Aplicacion';

    /**
     * directorio base de la ejecucion
     *
     * @var string
     */
    protected $path;

    public function __construct($path) {

        $this->path = $path;
        parent::__construct(null);

    }

    /**
     * En este metodo se deben definir las configuraciones de los comandos
     */
    abstract protected function configurar();

    /**
     * En este metodo se debe definir la ejecucion del comando
     *
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