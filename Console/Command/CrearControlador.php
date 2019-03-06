<?php

namespace Jida\Console\Command;

use Jida\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Comando para crear un controlador
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Console
 *
 */
class CrearControlador extends Command {

    protected static $defaultName = 'crear:controlador';

    //put your code here
    protected function configurar() {
        $this->addArgument('nombre', InputArgument::REQUIRED, 'Nombre del controlador a crear.');
        $this->addOption('modulo', 'm', InputOption::VALUE_OPTIONAL, " Modulo donde sera creado el controlador");
        $this->addOption('jadmin', 'j', InputOption::VALUE_NONE, "si esta presente el controlador se creara en Jadmin");
    }

    protected function ejecutar(InputInterface $input, OutputInterface $output) {

        $path   = realpath($this->directorioDeProyecto . DIRECTORY_SEPARATOR . self::PathApp);
        $nombre = $input->getArgument('nombre');

        if ($input->getOption('modulo')) {

            $modulo = $input->getOption('modulo');

            if ($input->getOption('jadmin')) {

                $class = "App\\Modulos\\" . $modulo . "\\Jadmin\\Controllers\\" . $nombre;
                $path  .= DIRECTORY_SEPARATOR . "Modulos" . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Jadmin";
                $this->createFiles($path, $nombre, $class, CrearArchivos::JcontrolClass);
                $output->writeln("Controlador $class ha sido creado");
            }
            else {

                $class = "App\\Modulos\\" . $modulo . "\\Controllers\\" . $nombre;
                $path  .= DIRECTORY_SEPARATOR . "Modulos" . DIRECTORY_SEPARATOR . $modulo;
                $this->createFiles($path, $nombre, $class, CrearArchivos::ControllerClass);
                $output->writeln("Controlador $class ha sido creado");

            }

        }
        elseif ($input->getOption('jadmin')) {

            $path .= DIRECTORY_SEPARATOR . "Jadmin";
            $class = "App\\Jadmin\\Controllers\\" . $nombre;
            $this->createFiles($path, $nombre, $class, CrearArchivos::JcontrolClass);
            $output->writeln("Controlador $class ha sido creado");
        }
        else {

            $class = "App\\Controllers\\" . $nombre;

            $this->createFiles($path, $nombre, $class, CrearArchivos::ControllerClass);
            $output->writeln("Controlador $class ha sido creado");
        }

    }

    protected function createFiles($path, $nombre, $class, $extends) {
        $php = new CrearArchivos('');

        $controlador = $php->controller($class, $extends);
        $vista       = $php->vista();
        file_put_contents($path . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . $nombre . ".php", $controlador);
        mkdir($path . DIRECTORY_SEPARATOR . "Vistas" . DIRECTORY_SEPARATOR . strtolower($nombre));
        file_put_contents($path . DIRECTORY_SEPARATOR . "Vistas" . DIRECTORY_SEPARATOR . strtolower($nombre) . DIRECTORY_SEPARATOR . "index.php", $vista);
    }

}
