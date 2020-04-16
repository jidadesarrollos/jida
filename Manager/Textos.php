<?php

namespace Jida\Manager;

use Jida\Configuracion\Config;
use Jida\Manager\Vista\Layout;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

class Textos {

    /**
     * Codigo de excepcion para el objeto
     *
     * @var $_ce ;
     */
    static private $_ce = 100016;

    private $_dir = "Textos";
    private $_archivo = "textos.json";

    public $arreglo = [];
    public $idioma;

    private static $instancia;

    public function __construct($entry) {

        $this->idioma = Estructura::$idioma;
        $config = Config::obtener();

        $this->_inicializar($entry);
        $this->_obtenerContenido();

    }

    private function _inicializar($entry) {

        $path = empty($entry) ? Estructura::$rutaModulo : $entry;
        $archivo = $path . DS . $this->_dir . DS . $this->_archivo;

        if (Directorios::validar($archivo)) {

            $contenido = file_get_contents($archivo);
            $this->arreglo = json_decode($contenido, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $msj = "El archivo ${archivo} no está estructurado correctamente";
                Excepcion::procesar($msj, self::$_ce . 1);
            }

        }

    }

    private function _obtenerContenido() {

        $arreglo = isset($this->arreglo[$this->idioma]) ? $this->arreglo[$this->idioma] : [];

        $default = [];
        if (count($arreglo) > 0) {
            foreach ($arreglo as $key => $value) {
                if (!is_array($value)) {
                    $default[$key] = $value;
                }
            }
        }

        $controlador = strtolower(Estructura::$nombreControlador);
        $metodo = strtolower(Estructura::$metodo);

        if (isset($arreglo[$controlador])) $arreglo = $arreglo[$controlador];

        if (isset($arreglo[$metodo])) $arreglo = $arreglo[$metodo];

        if (count($arreglo) > 0) {
            foreach ($arreglo as $key => $value) {
                if (!is_array($value)) {
                    $arreglo[$key] = $value;
                }
            }
        }

        $arreglo = array_merge($arreglo, $default);

        $this->arreglo = $arreglo;

    }

    public function texto($key, $secondLevel) {

        if (array_key_exists($key, $this->arreglo)) {

            if (is_string($this->arreglo[$key])) return $this->arreglo[$key];

            if (!is_null($secondLevel) and array_key_exists($secondLevel, $this->arreglo[$key])) {
                return $this->arreglo[$key][$secondLevel];
            }

            return 'Indefinido';

        }
    }

    public static function validar() {

        if (!self::$instancia) return;

        $instancia = self::$instancia;

        if ($instancia->idioma === Estructura::$idioma) return;

        $instancia->_obtenerContenido();

    }

    public static function obtener($entry = "") {

        if (!self::$instancia) {
            self::$instancia = new Textos($entry);
        }

        return self::$instancia;

    }

}