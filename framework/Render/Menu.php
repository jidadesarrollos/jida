<?php
/**
 * Objeto Renderizador de Menu
 *
 * @author Rosmy Rodriguez
 * @package JidaFramework
 * @version 1.0
 * @since 1.6
 * @category Render
 */

namespace Jida\Render;

use \Exception as Excepcion;
use Jida\Core\Rutas;
use Jida\Helpers as Helpers;

class Menu extends Selector {

    /**
     * Codigo de excepcion para el objeto
     *
     * @var $_ce ;
     */
    private $_ce = "100100";
    /**
     * Define la ubicacion del archivo de configuracion del menu
     *
     * @var $_path
     * @access private
     */
    private $_path;
    /**
     * @var $menu Configuracion del menu
     */
    private $menu;
    /**
     * Contenido del menu en HTML
     *
     * @var $html
     */
    private $html;
    /**
     * Contenedor del menu, puede ser un <div> o <ul> etc. Por defecto es <ul>
     *
     * @var $contenedor
     */

    public $selectorMenu = "ul";
    /**
     * Selector de elementos del menu, puede ser <div> o <li> etc. Por defecto es <li>
     *
     * @var $selector
     */
    public $selectorItem = "li";

    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($menu = "") {

        $this->_conf = $GLOBALS['JIDA_CONF'];

        if ($menu) {
            $this->cargarMenu($menu);
        }

        parent::__construct($menu);

    }

    /**
     * Carga el Menu a mostrar
     *
     * @internal Verifica si existe un archivo json para el menu pedido, carga la informacion del mismo y la
     * procesa.
     *
     * Los menus deben encontrarse en la carpeta Menus de Aplicacion o Framework, caso contrario arrojara
     * excepcion.
     *
     * @method cargarMenu
     * @param string $menu Nombre del Menu
     */
    private function cargarMenu($menu) {

        if (!strrpos($menu, ".json")) {
            $menu = $menu . ".json";
        }

        $path = Rutas::obtener($menu, 'menu')->absoluta();

        if (!Helpers\Directorios::validar($path)) {
            throw new Excepcion("No se consigue el archivo de configuracion del menu " . $path, $this->_ce . '1');
        }

        $this->_path = $path;
        $this->validarJson();

    }

    private function validarJson() {

        $contenido = file_get_contents($this->_path);
        $this->menu = json_decode($contenido);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Excepcion("El menu  " . $this->_path . " no esta estructurado correctamente", $this->_ce . "1");
        }

        return $this;

    }

    /**
     * Funcion recursiva que genera el HTML del selector
     * @method _obtHtml
     *
     * @param array $menu Arreglo con la configuracion del menu
     */
    private function _obtHtml($menu) {

        $this->html .= "\n";
        $this->html .= "<" . $this->selectorMenu;

        if (!empty($menu->id)) {
            $this->html .= " id=\"" . $menu->id . "\"";
        }
        if (!empty($menu->class)) {
            $this->html .= " class=\"" . $menu->class . "\"";
        }
        if (!empty($menu->style)) {
            $this->html .= " style=\"" . $menu->style . "\"";
        }

        $this->html .= ">\n";

        if (!empty($menu->items)) {

            foreach ($menu->items as $key => $item) {

                $this->html .= "<" . $this->selectorItem;

                if (!empty($item->id)) {
                    $this->html .= " id=\"" . $item->id . "\"";
                }
                if (!empty($item->class)) {
                    $this->html .= " class=\"" . $item->class . "\"";
                }
                if (!empty($item->style)) {
                    $this->html .= " style=\"" . $item->style . "\"";
                }

                $this->html .= ">";

                if (property_exists($item, 'url')) {
                    $link = new Selector('a', ['href' => $item->url]);
                    $label = !empty($item->encode_html) ? htmlentities($item->label) : $item->label;
                    $link->addFinal($label);
                    $this->html .= $link->render() . "\n";
                }

                if (!empty($item->submenu)) {
                    $this->_obtHtml($item->submenu);
                }

                $this->html .= "</$this->selectorItem>\n";
            }
        }

        $this->html .= "</$this->selectorMenu>\n";

        return $this->html;

    }

    /**
     * Renderiza el menu
     *
     * @internal Genera el HTML de un menu creado en el Framework, con toda la personalizacion creada
     * @method render
     */
    public function render() {

        $menu = $this->_obtHtml($this->menu);

        return $menu;

    }

}
