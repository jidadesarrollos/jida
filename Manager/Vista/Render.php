<?php
/**
 * codigo de error: 3;
 */

namespace Jida\Manager\Vista;

use App\Config\Configuracion;
use Jida\Configuracion\Config;
use Jida\Core\ObjetoManager;
use Jida\Helpers\Debug;
use Jida\Helpers\Directorios;
use Jida\Manager\Estructura;
use Jida\Render\Selector;
use Exception as Excepcion;

class Render {

    use ObjetoManager;

    private $_dir;
    private $_nombre;
    private $_tema;
    private $_ce = 100012;
    private $_data;

    private function _obtenerData () {

        $data = Data::obtener();
        $this->_data = $data;
        if (is_object($data)) {
            $this->copiarAtributos($data);
        }
    }


    /**
     * Imprime la vista o layout solicitado
     */
    function imprimir ($layout, $vista) {

        if (!Directorios::validar($layout)) {
            throw new Excepcion ("El layout solicitado no existe, \n Layout: " . $layout, $this->_ce . 1);
        }

        if (!Directorios::validar($vista)) {
            throw new Excepcion ("La vista solicitada no existe, \n Vista:" . $vista, $this->_ce . 2);
        }

        $this->_obtenerData();
        ob_start();
        $contenido = "";

        include_once $vista;
        $contenido = ob_get_clean();

        include $layout;
        $layout = ob_get_clean();

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $layout;


    }

    function __call ($metodo, $parametros) {

        $layout = Layout::obtener();
        if (method_exists($layout, $metodo)) {
            call_user_func_array([$layout, $metodo], $parametros);

        }

    }

    /**
     * Permite incluir objetos media
     */
    function media ($folder, $item, $tema = true) {

        return $this->htdocs($folder, $item, $tema);
    }

    /**
     * Retorna la url publica de los archivos htdocs de un tema
     * @method htdocs
     * @params string $folder Carpeta a obtener
     * @params string $item nombre del archivo
     * @params boolean $tema Determina si el archivo debe buscarse en el contenido
     * de un tema o en el contenido general.
     *
     */
    function htdocs ($folder, $item, $tema = true) {

        $path = (defined('URL_BASE')) ? URL_BASE : '';
        $url = $path . Configuracion::URL_BASE . '/htdocs' . $this->_tema . '/htdocs/' . $folder . '/' . $item;
        if ($tema)
            return $url;

        return Configuracion::URL_ABSOLUTA . '/htdocs' . $folder . '/' . $item;
    }

    /**
     * Imprime las lirerias del lado cliente
     *
     *
     * @since 1.4
     * @param string $lang Tipo de libreria a imprimir [js o css]
     * @param string $modulo Si es pasado, la funcion buscara imprimir solo los valores del key correspondiente.
     * @return string $libsHTML renderización HTML de los tags de inclusión de las librerias.
     */
    function imprimirLibrerias ($lang, $modulo = "") {

        $configuracion = Config::obtener();
        if (!$this->_data) {
            $this->_obtenerData();
        }
        $dataInclude = [];
        $path =  Estructura::url();

        if (!property_exists($this->_data, $lang))
            return false;
        $data = $this->_data->{$lang};

        //Se eliminan las librerias incluidas en un entorno distinto al actual
        //o que pertenezcan a un $modulo no solicitado
        foreach ($data as $key => $value) {
            if (is_array($value) and $key != $configuracion::ENTORNO_APP and $key != $modulo)
                unset($data[$key]);
        }//fin forech

        if (array_key_exists($configuracion::ENTORNO_APP, $data)) {
            $dataInclude = $data[$configuracion::ENTORNO_APP];
            //Se eliminan
            foreach ($dataInclude as $key => $value) {
                if (is_array($value) and $key != $modulo)
                    unset($dataInclude[$key]);
            }
            unset($data[$configuracion::ENTORNO_APP]);
        }

        $librerias = array_merge($dataInclude, $data);
        if (!empty($modulo)) {
            if (array_key_exists($modulo, $librerias)) {
                $libreriasModulo = $librerias[$modulo];
                unset($librerias[$modulo]);
                $librerias = $libreriasModulo;
            }
        }

        $libsHTML = "";
        $cont = 0;


        foreach ($librerias as $id => $libreria) {
            if (is_array($libreria) and $lang == 'css') {
                //se pasa como lenguaje la variable $id ya que es un una etiqueta link la que se creara
                //a partir del arreglo $libreria
                $libsHTML .= $this->__obtHTMLLibreria('link', $libreria, $cont);
            }
            else if (!is_array($libreria))
                $libsHTML .= $this->__obtHTMLLibreria($lang, $libreria, $cont);

            if ($cont == 0)
                $cont = 2;
        }//fin foreach=======================================
        return $libsHTML;
    }


    private function __obtHTMLLibreria ($lang, $libreria, $cont = 2) {

        $path = (defined('URL_BASE') and (is_string($libreria) and strpos($libreria,
                                                                          'http') === false)) ? URL_BASE : "";

        switch ($lang) {
            case 'js':
                if (is_array($libreria))
                    Debug::mostrarArray($libreria, 0);
                $html = Selector::crear('script', ['src' => $path . $libreria], null, $cont);
                break;
            case 'link':

                $libreria['href'] = $path . $libreria['href'];
                $html = Selector::crear('link', $libreria, null, $cont);
                break;
            default:
                //css
                $html = Selector::crear('link',
                                        [
                                            'href' => $path . $libreria,
                                            'rel'  => 'stylesheet',
                                            'type' => 'text/css'
                                        ],
                                        null,
                                        2);
                break;
        }

        return $html;
    }

    function segmento ($segmento, $params = []) {

        if (!is_array($params))
            $params = [$params];

        foreach ($params as $key => $p)
            $this->$key = $p;

        $directorio = DIR_APP . 'Segmentos/';
        if (file_exists($directorio . $segmento . '.php')) {
            echo $this->incluir('Aplicacion/Segmentos/' . $segmento);
            // echo  $this->obtenerContenidos('Aplicacion/Segmentos/'.$segmento.'.php');
            // return true;
        }
        else {
            throw new \Exception("No existe el segmento $segmento en la carpeta " . $directorio, 100);
        }

        return false;
    }

    /**
     * Función para incluir archivos
     * @param mixed $files Nombre de Archivo o arreglo de archivos a incluir
     *
     */
    function incluir ($archivo) {

        if (is_array($archivo)) {
            foreach ($archivo as $key => $ar) {
                include_once $ar . '.php';
            }
        }
        else if (is_string($archivo)) {
            include_once $archivo . '.php';
        }
    }

    /**
     * Permite acceder a un nexo
     *
     */
    function nexo ($nexo, $modulo = "") {

        $partes = explode(".", $nexo);
        if (count($partes) > 1) {

        }
        else {
            $modulo = (empty($modulo)) ? ucwords($this->_modulo) : ucwords($modulo);

            if ($this->_esJadmin) {

                $namespace = '\Jida\Jadmin\Modulos\\' . $modulo . '\Nexos\\';
            }
            else {
                $namespace = '\App\Modulos\\' . $modulo . '\Nexos\\';
            }
        }
        $nexoAbsoluto = $namespace . ucfirst($nexo);
        if (!class_exists($nexoAbsoluto))
            throw new Excepcion("No existe el nexo solicitado " . $nexoAbsoluto, $this->_ce . '90');

        $objNexo = new $nexoAbsoluto;

        return $objNexo;
    }

    /**
     * Obtiene el texto correspondiente a una traduccion
     *
     * @internal Es un atajo para acceder al objeto traductor, el cual debe
     * haber sido instanciado en el controlador previamente. El traductor debe
     * haber sido pasado con el nombre de varible "traductor" al objeto DataVista
     * @method cadena
     * @param string $texto Texto a imprimir
     * @param string $ubicacion Ubicacion dentro del sistema de traducciones
     * @param string $seccion [opcional] seccion declarada en el sistema de traducciones
     *
     */
    function cadena ($texto, $ubicacion, $seccion = "") {

        if (!property_exists($this, 'traductor'))
            throw new Excepcion("El objeto vista no consigue al traductor, no se ha instanciado correctamente",
                                $this->_ce . '10');

        return $this->traductor->cadena($texto, $ubicacion);
    }


    function enlace ($url = "") {

        $path = (defined('URL_BASE')) ? URL_BASE : '';
        if (!empty($this->idioma))
            $enlace = $path . '/' . $this->idioma . '/' . $url;
        else
            $enlace = $path . $url;

        return $enlace;
    }

    /**
     * Retorna la url actual para el idioma pasado
     *
     * Metodo provisional para manejo de urls desde las vistas
     * @method cambiarUrl
     * @param {string} idioma
     * @since 0.5
     */
    function cambiarUrl ($idioma) {

        $url = "/";
        if (!empty($this->modulo))
            $url .= $this->modulo . "/";
        if (!empty($this->controlador) and strtolower($this->controlador) != 'index')
            $url .= $this->controlador;
        if (!empty($this->metodo) and strtolower($this->metodo) != 'index')
            $url .= $this->metodo;

        $base = URL_BASE;
        $base = (empty($base)) ? "/" : "/" . URL_BASE . '/';
        $url = explode("/", Helpers\Cadenas::guionCase($idioma, true) . '/' . Helpers\Cadenas::guionCase($url, true));

        return $base . implode("/", array_filter($url));

    }

}