<?php
/**
 * Clase para manejo de Traducciones del sitio Web
 *
 * @internal
 * El archivo permite ubicar las cadenas a mostrar en la web, las cuales son buscadas
 * en un archivo [idioma].php, por tanto las cadenas para español deben encontrarse en el archivo
 * "es.php".
 * Las cadenas para cada lenguaje deben ser definidas por medio de una matriz llamada "$cadenas"
 * la cual debe tener la siguiente estructora
 * $cadena[ 'Ubicacion'=>['idCadena'=>'valor de la cadena']]
 * en donde "Ubicacion" puede hacer referencia a un metodo o nombre dado por el programador que permita
 * segmentar todas las cadenas del sitio de forma ubicables.
 *
 * IMPORTANTE
 *  > Los keys de Ubicacion deben encontrarse en UpperCamelCase.
 *  > Los identificadores de cadena pueden tener la estructura que se desee, pero deberán llamarse
 *    de la misma forma.
 * Los
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace Jida\Componentes;

use Jida\Configuracion\Config;
use Jida\Medios\Debug;
use Jida\Medios\Directorios as Directorios;
use Jida\Manager\Estructura;

class Traductor {

    private $idiomas = [];
    private $idiomaActual;
    private $ubicacion;
    private $textos;
    private $path = "Aplicacion/Traducciones/";
    private $textosSeccion = [];
    /**
     * Define el nombre del archivo para la seccion de textos
     * @see
     * @var string $dir
     */
    private $dir;

    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct ($idiomaActual, $data = []) {

        $configuracion = Config::obtener();

        $this->idiomaActual = $idiomaActual;

        $traducciones = [];
        $this->_obtenerArchivo($data);

        $this->textos = $traducciones;

        if (property_exists($configuracion, 'idiomas')) {
            $this->idiomas = $configuracion->idiomas;
        }
        else {
            throw new \Exception("No se han configurado idiomas para manejar traducciones", 1);
        }
        if (!array_key_exists($this->idiomaActual, $this->idiomas))
            throw new \Exception('El idioma ' . $this->idiomaActual . ' no ha sido cargado', 1);

    }

    /**
     * Obtiene el arcghivo de traducciones
     *
     * @param array $data
     * @throws \Exception
     */
    function _obtenerArchivo ($data) {

        if (array_key_exists('path', $data)) {
            $this->path = $data['path'];

        }
        if (array_key_exists('ubicacion', $data)) {
            $this->ubicacion = $data['ubicacion'];
        }
        $path = Estructura::$directorio . DS . $this->path;
        $archivo = $path . strtolower($this->idiomaActual) . ".php";

        if (file_exists($archivo)) {
            include_once $archivo;

        }
        else {
            $msj = "No existe el archivo de traducciones " . $this->idiomaActual . " en " . $archivo;
            throw new \Exception($msj, 950);

        }

    }

    function path ($path = "") {

        if (!empty($path) and Directorios::validar($path)) {
            $this->path = $path;

            return;
        }

        return $this->path;

    }

    function seleccionarIdioma ($idioma) {

        include_once strtolower($idioma) . ".php";
        $this->textos = $traducciones;
    }

    /**
     * Traduce una cadena recibida
     * @param string $cadena Cadena string a buscar
     * @param string $ubicacion Ubicacion de la cadena dentro de la matriz
     */
    function cadena ($cadena, $ubicacion = "") {

        if (empty($ubicacion))
            $ubicacion = $this->ubicacion;

        #Debug::mostrarArray($this->textos);
        if (!empty($ubicacion)) {
            if (array_key_exists($ubicacion, $this->textos) and array_key_exists($cadena, $this->textos[$ubicacion]))
                return $this->textos[$ubicacion][$cadena];
        }
        else {

            if (array_key_exists($cadena, $this->textos))
                return $this->textos[$cadena];
        }

        return 'Indefinido';

    }

    /**
     * Busca la cadena de una seccion especificada
     *
     * La cadena es buscada en un archivo con el nombre de la seccion que debe tener por ubicacion
     * una carpeta con el nombre del idioma. por ejemplo "es/index" y adentro debe haber un arreglo
     * llamado $traduccion
     * @method seccion
     * @param string $seccion
     * @param string $cadena
     * @param string $ubicaicon
     *
     */
    function seccion ($cadena, $seccion = "", $ubicacion = "") {

        $this->validarSeccion($seccion);

        if (empty($ubicacion))
            $ubicacion = $this->ubicacion;

        if (!empty($ubicacion)) {
            if (array_key_exists($ubicacion, $this->textosSeccion)
                and array_key_exists($cadena, $this->textosSeccion[$ubicacion])) {
                return $this->textosSeccion[$ubicacion][$cadena];
            }

        }
        else {

            if (array_key_exists($cadena, $this->textosSeccion))
                return $this->textosSeccion[$cadena];
        }

        return 'Indefinido';
    }

    private function validarSeccion ($seccion) {

        if (!empty($seccion) and $seccion != $this->dir) {

            if (!file_exists($this->path . $this->idiomaActual . '/' . $seccion . ".php"))
                throw new Exception("No existe el archivo " . $seccion, 1);

            $this->dir = $seccion;
            include_once $this->path . $this->idiomaActual . DS . $seccion . ".php";
            if (isset($traducciones) and is_array($traducciones)) {
                $this->textosSeccion = $traducciones;
            }
            else throw new Exception("No esta definido el arreglo de traducciones para la seccion " . $seccion, 1);
        }
    }

    /**
     * Permite identificar la ubicacion de los textos a utilizar
     * dentro de la matríz de traducciones
     *
     * @method addUbicacion
     * @param string $ubicacion
     */
    function addUbicacion ($ubicacion) {

        $this->ubicacion = $ubicacion;

        return $this;
    }

    /**
     * Renderiza una URL conforme al idioma actual
     * @method renderURL
     */
    function renderURL ($url) {

        return "/" . $this->idiomaActual . $url;
    }

    /**
     * Permite agregar una seccion de traducciones
     * @method addSeccion
     * @param string $seccion
     */
    function addSeccion ($seccion) {

        $this->validarSeccion($seccion);
    }

    function obtTraduccionesArray () {

        return $this->textos;
    }

    /**
     * Valida si una traduccion existe
     */
    function validar ($traduccion, $ubicacion = "") {

        if (!empty($ubicacion) and array_key_exists($ubicacion, $this->textos)) {
            if (array_key_exists($traduccion, $this->textos[$ubicacion]))
                return true;
        }
        else if (array_key_exists($traduccion, $this->textos)) {
            return true;
        }

        return false;
    }
}