<?php

namespace Jida\Console\Command;

/**
 * crea los codigos para los archivos del proyecto
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Console
 *
 */
class CrearArchivos {

    protected $modulo;

    const ControllerClass = "App\\Controllers\\App";
    const JcontrolClass   = "Jida\\Jadmin\\Controllers\\JControl";
    const ModelosClass    = "Jida\\Core\\Modelo";

    protected $head;

    public function __construct($modulo) {
        $this->modulo = $modulo;
        $fecha        = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $this->head   = <<<EOD
                
/**
 * Creado por Jida Framework
 * $fecha
 */

EOD;
    }

    public function controlador($nombre = null) {
        $class = "App\\Modulos\\" . $this->modulo . "\\Controllers\\" . ($nombre ? $nombre : $this->modulo);

        return $this->controller($class, self::ControllerClass);
    }

    public function controladorJadmin($nombre = null) {
        $class = "App\\Modulos\\" . $this->modulo . "\\Jadmin\\Controllers\\" . ($nombre ? $nombre : $this->modulo);

        return $this->controller($class, self::JcontrolClass);
    }

    public function modelo($nombre = null) {
        $class = "App\\Modulos\\" . $this->modulo . "\\Modelos\\" . ($nombre ? $nombre : $this->modulo);

        return $this->model($class, self::ModelosClass);
    }

    public function controller($class, $extends) {
        $c          = explode("\\", $class);
        $nameClass  = array_pop($c);
        $e          = explode("\\", $extends);
        $nameExtend = $e[count($e) - 1];
        $controller = "<?php\n"
                . "\n";
        $controller .= $this->head;
        $controller .= "namespace  " . implode("\\", $c) . ";\n\n";
        $controller .= "use " . implode("\\", $e) . "; \n\n";



        $controller .= "class " . $nameClass . " extends " . $nameExtend . "{\n"
                . "\n"
                . "    public function index(){\n\n"
                . "        \$this->data(['mensaje' => 'Controlador '+self::class]);\n"
                . "    }\n"
                . "}\n";
        return $controller;
    }

    public function model($class, $extends) {
        $c          = explode("\\", $class);
        $nameClass  = array_pop($c);
        $e          = explode("\\", $extends);
        $nameExtend = $e[count($e) - 1];
        $controller = "<?php\n"
                . "\n";
        $controller .= $this->head;
        $controller .= "namespace  " . implode("\\", $c) . ";\n\n";
        $controller .= "use " . implode("\\", $e) . ";\n\n";



        $controller .= "class " . $nameClass . " extends " . $nameExtend . "{\n"
                . "\n\n"
                . "}\n";
        return $controller;
    }

    public function vista() {
        $fecha = (new \DateTime('now'))->format('Y-m-d H:i:s');

        $text = <<<EOD
<!-- Creado por Jida Framework  $fecha -->
<div class = "jumbotron">
    <h2><?= \$this->mensaje ?></h2>
    <p>Use esta plantilla para iniciar de forma rápida el desarrollo de un sitio web.</p>
</div >
EOD;
        return $text;
    }

}
