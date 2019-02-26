<?php

namespace Jida\Console\Command;

use Jida\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Comnado para crear la estructura de modulos de un Modulo 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Console
 *
 */
class CrearModulos extends Command {

    protected static $defaultName = 'crear:modulo';

    const PathModelos = 'Modulos';

    /**
     * Estructura de directorio que sera creada 
     */
    const EstructuraDirectorios = [
        'Controllers' => [],
        'Modelos' => [],
        'Vistas' => [
        ],
        'Jadmin' => [
            'Controllers' => [],
            ///'Modelos' => [],
            'Vistas' => [
            ],
        ]
    ];

    protected function configurar() {
        $this->addArgument('nombre', InputArgument::REQUIRED, 'Nombre del modulo a crear.');
    }

    protected function ejecutar(InputInterface $input, OutputInterface $output) {


        $nombre = $input->getArgument('nombre');
        $path   = realpath($this->directorioDeProyecto . DIRECTORY_SEPARATOR . self::PathApp . DIRECTORY_SEPARATOR . self::PathModelos);
        if (!$path) {
            mkdir($path);
        }
        if (realpath($path . DIRECTORY_SEPARATOR . $nombre)) {
            $output->writeln("El modelo $nombre ya existe");
        }
        else {
            $estructura                                          = self::EstructuraDirectorios;
            $estructura['Vistas'][strtolower($nombre)]           = [];
            $estructura['Jadmin']['Vistas'][strtolower($nombre)] = [];
            $this->crearDirectorios($path, [$nombre => $estructura]);
            $this->crearArchivos($nombre, $path);
            $output->writeln("Estructura de directorios del modelo $nombre ha sido creada");
        }
    }

    public function crearDirectorios($directorio, $extructura) {
        if (count($extructura) == 0) {
            return;
        }
        foreach ($extructura as $carpeta => $subCarpetas) {
            $path = $directorio . DIRECTORY_SEPARATOR . $carpeta;
            // $this->output->writeln($path);
            mkdir($path);
            $this->crearDirectorios($path, $subCarpetas);
        }
    }

    public function crearArchivos($modulo, $path) {
        $php         = new CrearArchivos($modulo);
        $controlador = $php->controlador();
        $jadmin      = $php->controladorJadmin();
        $vista       = $php->vista();
        $modelo      = $php->modelo();
        //$file        = new \SplFileObject($path . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . $modulo . ".php", "w+");
        //$file->fwrite($controlador);

        file_put_contents($path . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Modelos" . DIRECTORY_SEPARATOR . $modulo . ".php", $modelo);
        file_put_contents($path . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Jadmin" . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . $modulo . ".php", $jadmin);
        file_put_contents($path . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . $modulo . ".php", $controlador);
        file_put_contents($path . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Vistas" . DIRECTORY_SEPARATOR . strtolower($modulo) . DIRECTORY_SEPARATOR . "index.php", $vista);
        file_put_contents($path . DIRECTORY_SEPARATOR . $modulo . DIRECTORY_SEPARATOR . "Jadmin" . DIRECTORY_SEPARATOR . "Vistas" . DIRECTORY_SEPARATOR . strtolower($modulo) . DIRECTORY_SEPARATOR . "index.php", $vista);
    }

}
