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
            'uses'      => [\App\Controllers\App::class],
            'class'     => $modulo,
            'extends'   => "App",
            'metodos'   => ['index' => $codigoMetodo]
        ];
        $controlador = $controladorTpl->crearArchivo($variables, $plantilla);
        file_put_contents("$path/$modulo/Controllers/$modulo.php", $controlador);

        $jadminTpl = new GeneradorArchivo();
        $variables = [
            'namespace' => "App\\Modulos\\$modulo\\Jadmin\\Controllers",
            'uses'      => [\Jida\Jadmin\Controllers\JControl::class],
            'class'     => $modulo,
            'extends'   => "JControl",
            'metodos'   => ['index' => $codigoMetodo]
        ];
        $jadmin = $jadminTpl->crearArchivo($variables, $plantilla);
        file_put_contents("$path/$modulo/Jadmin/Controllers/$modulo.php", $jadmin);

        $modeloTpl = new GeneradorArchivo();
        $variables = [
            'namespace' => "App\\Modulos\\$modulo\\Modelos",
            'uses'      => [\Jida\Core\Modelo::class],
            'class'     => $modulo,
            'extends'   => "Modelo",
            'metodos'   => []
        ];
        $modelo = $modeloTpl->crearArchivo($variables, $plantilla);
        file_put_contents("$path/$modulo/Modelos/$modulo.php", $modelo);

        $vistaTpl = new GeneradorArchivo();
        $plantilla = dirname(__DIR__) . '/plantillas/vista.jida';
        $variables = ['cabecera' =>  "<?= \$this->mensaje ?>",
                      'mensaje' => "Use esta plantilla para iniciar de forma rápida el desarrollo de un sitio web."];
        $vista = $vistaTpl->crearArchivo($variables, $plantilla);
        file_put_contents("$path/$modulo/Vistas/" . lcfirst($modulo) . "/index.php", $vista);
        file_put_contents("$path/$modulo/Jadmin/Vistas/" . lcfirst($modulo) . "/index.php", $vista);

    }

}
