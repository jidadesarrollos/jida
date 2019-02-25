<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jida\Console\Command;

/**
 * Description of CrearArchivos
 *
 * @author usuario
 */
class CrearArchivos {

    protected $modulo;

    const ControllerClass = "App\\Controllers\\App";
    const JcontrolClass   = "Jida\\Jadmin\\Controllers\\JControl";
    const ModelosClass    = "Jida\\Core\\Modelo";

    public function __construct($modulo) {
        $this->modulo = $modulo;
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

        $controller .= "namespace  " . implode("\\", $c) . ";\n\n";
        $controller .= "use " . implode("\\", $e) . "; \n\n";



        $controller .= "class " . $nameClass . " extends " . $nameExtend . "{\n"
                . "\n"
                . "    public function index(){\n\n"
                . "        \$this->data(['mensaje' => 'Hola mundo']);\n"
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

        $controller .= "namespace  " . implode("\\", $c) . ";\n\n";
        $controller .= "use " . implode("\\", $e) . ";\n\n";



        $controller .= "class " . $nameClass . " extends " . $nameExtend . "{\n"
                . "\n\n"
                . "}\n";
        return $controller;
    }

    public function vista() {
        return "<?= \$this->mensaje ?>";
    }

}
