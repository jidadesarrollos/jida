<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Jida\Core\Consola\GeneradorArchivo;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando para crear un controlador
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Consola
 *
 */
class CrearControlador extends Comando {

    protected static $defaultName = 'crear:controlador';
    protected $variables;

    //put your code here
    protected function configurar() {

        $this->addArgument(
            'nombre',
            InputArgument::REQUIRED,
            'Nombre del controlador a crear.'
        );
        $this->addOption(
            'modulo',
            'm',
            InputOption::VALUE_OPTIONAL,
            "Modulo donde sera creado el controlador"
        );
        $this->addOption(
            'jadmin',
            'j',
            InputOption::VALUE_NONE,
            "si esta presente el controlador se creara en Jadmin"
        );

    }

    protected function ejecutar(InputInterface $input, OutputInterface $output) {

        $path = realpath($this->path . DS . self::PathApp);
        $nombre = ucwords($input->getArgument('nombre'));
        $class = "App\\Controllers\\$nombre";
        $modulo = $input->getOption('modulo');
        $jadmin = ($input->getOption('jadmin'));
        $extend = ($jadmin) ? \Jida\Jadmin\Controllers\JControl::class : \App\Controllers\App::class;

        if ($modulo and $jadmin) {
            $path = "$path/Modulos/$modulo/Jadmin";
            $class = "App\\Modulos\\$modulo\\Jadmin\\Controllers\\$nombre";
        }
        else if ($modulo) {
            $path = "$path/Modulos/$modulo";
            $class = "App\\Modulos\\$modulo\\Controllers\\$nombre";
        }
        else if ($jadmin) {
            $path = "$path/Jadmin";
            $class = "App\\Jadmin\\Controllers\\$nombre";
        }

        $this->createFiles($path, $nombre, $class, $extend);
        $output->writeln("Controlador $class ha sido creado");

    }

    protected function createFiles($path, $nombre, $class, $extends) {

        $controladorTpl = new GeneradorArchivo();
        $c = explode("\\", $class);
        $nameClass = array_pop($c);
        $e = explode("\\", $extends);
        $nameExtend = $e[count($e) - 1];
        $variables = [
            'namespace' => implode("\\", $c),
            'use'       => implode("\\", $e),
            'class'     => $nameClass,
            'extends'   => $nameExtend,
            'method'    => 'index'
        ];
        $plantilla = dirname(__DIR__) . '/plantillas/clase.jida';
        $rutaControlador = "$path/Controllers/$nombre.php";
        $controladorTpl->crearArchivo($variables, $plantilla, $rutaControlador);

        $vistaTpl = new GeneradorArchivo();
        $variables = ['cabecera' => "¡Hola mundo!",
                      'mensaje'  => "Use esta plantilla para iniciar de forma rápida el desarrollo de un sitio web."
        ];
        $plantilla = dirname(__DIR__) . '/plantillas/vista.jida';
        $directorioVista = "$path/Vistas/" . lcfirst($nombre);
        $rutaVista = "$directorioVista/index.php";
        mkdir($directorioVista);
        $vistaTpl->crearArchivo($variables, $plantilla, $rutaVista);

    }

}
