<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Jida\Core\Consola\GeneradorArchivo;

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

        if ($input->getOption('modulo')) {

            $modulo = $input->getOption('modulo');

            if ($input->getOption('jadmin')) {

                $class = "App\\Modulos\\$modulo\\Jadmin\\Controllers\\$nombre";
                $path .= "/Modulos/$modulo/Jadmin";
                $this->createFiles($path, $nombre, $class, \Jida\Jadmin\Controllers\JControl::class);
                $output->writeln("Controlador $class ha sido creado");

            }
            else {

                $class = "App\\Modulos\\$modulo\\Controllers\\$nombre";
                $path .= "/Modulos/$modulo";
                $this->createFiles($path, $nombre, $class, \App\Controllers\App::class);
                $output->writeln("Controlador $class ha sido creado");

            }
        }
        elseif ($input->getOption('jadmin')) {

            $path .= "/Jadmin";
            $class = "App\\Jadmin\\Controllers\\$nombre";
            $this->createFiles($path, $nombre, $class, \Jida\Jadmin\Controllers\JControl::class);
            $output->writeln("Controlador $class ha sido creado");

        }
        else {

            $class = "App\\Controllers\\$nombre";
            $this->createFiles($path, $nombre, $class, \App\Controllers\App::class);
            $output->writeln("Controlador $class ha sido creado");

        }

    }

    protected function createFiles($path, $nombre, $class, $extends) {

        $controladorTpl = new GeneradorArchivo();
        $c = explode("\\", $class);
        $nameClass = array_pop($c);
        $e = explode("\\", $extends);
        $nameExtend = $e[count($e) - 1];
        $variables = [
            'namespace' => implode("\\", $c),
            'uses' => [implode("\\", $e)],
            'class' => $nameClass,
            'extends' => $nameExtend,
            'metodos' => ['index' => "\$this->data(['mensaje' => 'Controlador ' . self::class]);\n"]
        ];
        $plantilla = dirname(__DIR__).'/plantillas/clase.jida';
        $controlador = $controladorTpl->crearArchivo($variables, $plantilla);

        $vistaTpl = new GeneradorArchivo();
        $variables = ['cabecera' => "<?= \$this->mensaje ?>",
                       'mensaje' => "Use esta plantilla para iniciar de forma rápida el desarrollo de un sitio web."
        ];
        $plantilla = dirname(__DIR__).'/plantillas/vista.jida';
        $vista = $vistaTpl->crearArchivo($variables, $plantilla);

        $archivoControlador = "$path/Controllers/$nombre.php";
        $directorioVista = "$path/Vistas/" . lcfirst($nombre);
        $archivVista = "$directorioVista/index.php";

        file_put_contents($archivoControlador, $controlador);
        mkdir($directorioVista);
        file_put_contents($archivVista, $vista);

    }

}
