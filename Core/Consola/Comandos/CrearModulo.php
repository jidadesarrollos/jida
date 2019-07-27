<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Jida\Core\Consola\GeneradorArchivo;

/**
 * Comnado para crear la estructura de modulos de un Modulo
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Console
 *
 */
class CrearModulo extends Comando {

    protected static $defaultName = 'crear:modulo';

    const PathModulos = 'Modulos';

    /**
     * Estructura de directorio que sera creada
     */
    const EstructuraDirectorios = [
        'Controllers' => [],
        'Modelos'     => [],
        'Vistas'      => [],
        'Jadmin'      => [
            'Controllers' => [],
            ///'Modelos' => [],
            'Vistas'      => [
            ],
        ]
    ];

    protected function configurar() {

        $this->addArgument(
            'nombre',
            InputArgument::REQUIRED,
            'Nombre del modulo a crear.'
        );

    }

    protected function ejecutar(InputInterface $input, OutputInterface $output) {

        $nombre = ucwords($input->getArgument('nombre'));
        $pathModulo = $this->path . DS . self::PathApp . DS . self::PathModulos;

        $output->writeln($pathModulo);
        if (!is_dir($pathModulo)) mkdir($pathModulo);

        $path = $pathModulo;

        if (realpath($path . DS . $nombre)) {
            $output->writeln("El modulo $nombre ya existe");
            return;
        }

        $estructura = self::EstructuraDirectorios;
        $estructura['Vistas'][lcfirst($nombre)] = [];
        $estructura['Jadmin']['Vistas'][lcfirst($nombre)] = [];
        $this->crearDirectorios($path, [$nombre => $estructura]);
        $this->crearArchivos($nombre, $path);
        $output->writeln("Estructura de directorios del modulo $nombre ha sido creada");

        return;

    }

    public function crearDirectorios($directorio, $extructura) {

        if (count($extructura) == 0) return;

        if (!is_dir($directorio)) mkdir($directorio);

        foreach ($extructura as $carpeta => $subCarpetas) {

            $path = $directorio . DS . $carpeta;
            mkdir($path);
            $this->crearDirectorios($path, $subCarpetas);

        }

    }

    public function crearArchivos($modulo, $path) {

        $codigoMetodo = "\$this->data(['mensaje' => 'Controlador '.self::class]);\n";
        $controladorTpl = new GeneradorArchivo();
        $plantilla = dirname(__DIR__) . '/plantillas/clase.jida';
        $variables = [
            'namespace' => "App\\Modulos\\$modulo\\Controllers",
            'use'       => \App\Controllers\App::class,
            'class'     => $modulo,
            'extends'   => "App",
            'method'    => 'index'
        ];
        $ruta = "$path/$modulo/Controllers/$modulo.php";
        $controladorTpl->crearArchivo($variables, $plantilla, $ruta);

        $jadminTpl = new GeneradorArchivo();
        $variables = [
            'namespace' => "App\\Modulos\\$modulo\\Jadmin\\Controllers",
            'use'       => \Jida\Jadmin\Controllers\JControl::class,
            'class'     => $modulo,
            'extends'   => "JControl",
            'method'    => 'index'
        ];
        $ruta = "$path/$modulo/Jadmin/Controllers/$modulo.php";
        $jadminTpl->crearArchivo($variables, $plantilla, $ruta);

        $modeloTpl = new GeneradorArchivo();
        $variables = [
            'namespace' => "App\\Modulos\\$modulo\\Modelos",
            'use'       => \Jida\Core\Modelo::class,
            'class'     => $modulo,
            'extends'   => "Modelo",
            'method'    => []
        ];
        $ruta = "$path/$modulo/Modelos/$modulo.php";
        $modeloTpl->crearArchivo($variables, $plantilla, $ruta);

        $vistaTpl = new GeneradorArchivo();
        $plantilla = dirname(__DIR__) . '/plantillas/vista.jida';
        $variables = ['cabecera' => "¡Hola mundo!",
                      'mensaje'  => "Use esta plantilla para iniciar de forma rápida el desarrollo de un sitio web."];
        $ruta = "$path/$modulo/Vistas/" . lcfirst($modulo) . "/index.php";
        $vistaTpl->crearArchivo($variables, $plantilla, $ruta);
        $ruta = "$path/$modulo/Jadmin/Vistas/" . lcfirst($modulo) . "/index.php";
        $vistaTpl->crearArchivo($variables, $plantilla, $ruta);

    }

}
