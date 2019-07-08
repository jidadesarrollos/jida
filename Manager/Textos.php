<?php

namespace Jida\Manager;

use Jida\Configuracion\Config;
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

    public function __construct() {

        $this->idioma = Estructura::$idioma;
        $config = Config::obtener();

        if ($config::MULTIIDIOMA) {
            $this->_inicializar();
            $this->_obtenerContenido();
        }

    }

    private function _inicializar() {

        $archivo = Estructura::$rutaModulo . DS . $this->_dir . DS . $this->_archivo;

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

        $salida = [];
        $arreglo = $this->arreglo[$this->idioma];

        if (isset($arreglo[Estructura::$metodo])) {
            $salida = array_merge($salida, $arreglo[Estructura::$metodo]);
            unset($arreglo[Estructura::$metodo]);
        }

        foreach ($arreglo as $key => $value) {
            if (!is_array($value)) {
                $salida[$key] = $value;
            }
        }

        $this->arreglo = $salida;

    }

    public function texto($key) {

        if (array_key_exists($key, $this->arreglo)) {
            return $this->arreglo[$key];
        }

        return 'Indefinido';

    }

    public static function validar() {

        if (!self::$instancia) return;

        $instancia = self::$instancia;

        if ($instancia->idioma === Estructura::$idioma) return;

        $instancia->_obtenerContenido();

    }

    public static function obtener() {

        if (!self::$instancia) {
            self::$instancia = new Textos();
        }

        return self::$instancia;

    }

}