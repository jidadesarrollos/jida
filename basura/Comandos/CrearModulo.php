<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Jida\Core\Consola\MotorDePlantillas;

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
        $controladorTpl = new MotorDePlantillas();
        $controladorTpl->asignar('namespace', "App\\Modulos\\$modulo\\Controllers");
        $controladorTpl->asignar('uses', [\App\Controllers\App::class]);
        $controladorTpl->asignar('class', $modulo);
        $controladorTpl->asignar('extends', "App");
        $controladorTpl->asignar('metodos', ['index' => $codigoMetodo]);
        $controlador = $controladorTpl->obt("clase.jida");

        $jadminTpl = new MotorDePlantillas();
        $jadminTpl->asignar('namespace', "App\\Modulos\\$modulo\\Jadmin\\Controllers");
        $jadminTpl->asignar('uses', [\Jida\Jadmin\Controllers\JControl::class]);
        $jadminTpl->asignar('class', $modulo);
        $jadminTpl->asignar('extends', "JControl");
        $jadminTpl->asignar('metodos', ['index' => $codigoMetodo]);
        $jadmin = $jadminTpl->obt("clase.jida");

        $modeloTpl = new MotorDePlantillas();
        $modeloTpl->asignar('namespace', "App\\Modulos\\$modulo\\Modelos");
        $modeloTpl->asignar('uses', [\Jida\Core\Modelo::class]);
        $modeloTpl->asignar('class', $modulo);
        $modeloTpl->asignar('extends', "Modelo");
        $modeloTpl->asignar('metodos', []);
        $modelo = $modeloTpl->obt("clase.jida");
        $vistaTpl = new MotorDePlantillas();
        $vistaTpl->asignar('cabecera', "<?= \$this->mensaje ?>");
        $vistaTpl->asignar('mensaje', "Use esta plantilla para iniciar de forma rápida el desarrollo de un sitio web.");
        $vista = $vistaTpl->obt('vista.jida');

        file_put_contents("$path/$modulo/Modelos/$modulo.php", $modelo);
        file_put_contents("$path/$modulo/Jadmin/Controllers/$modulo.php", $jadmin);
        file_put_contents("$path/$modulo/Controllers/$modulo.php", $controlador);
        file_put_contents("$path/$modulo/Vistas/" . lcfirst($modulo) . "/index.php", $vista);
        file_put_contents("$path/$modulo/Jadmin/Vistas/" . lcfirst($modulo) . "/index.php", $vista);

    }

}
