<?php
/**
 * Objeto Renderizador de Menus
 *
 * @author Julio Rodriguez
 * @package JidaFramework
 * @version 1.0
 * @since 1.4
 * @see Selector
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
     * @var $_configuracion Configuracion del menu
     */
    private $_configuracion;
    /**
     * Arreglo de contenedor principal del menu
     *
     * @var array $_contenedor
     */
    private $_contenedor;

    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($menu = "") {

        $this->_conf = $GLOBALS['JIDA_CONF'];

        if ($menu) {
            $this->_cargarMenu($menu);
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
     * @method _cargarMenu
     * @param string $menu Nombre del Menu
     */
    private function _cargarMenu($menu) {

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
        $this->_configuracion = json_decode($contenido);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Excepcion("El menu  " . $this->_path . " no esta estructurado correctamente", $this->_ce . "1");
        }

        return $this;

    }

    private function _atributosItem($item) {

        $attr = [];

        if (!empty($item->id))
            $attr['id'] = $item->id;

        if (!empty($item->class))
            $attr['class'] = $item->class;

        if (!empty($item->style))
            $attr['style'] = $item->style;

        return $attr;

    }

    private function _agregarItems($menu) {

        if (!empty($menu->items)) {

            foreach ($menu->items as $key => $item) {

                $selector = !empty($item->selector) ? $item->selector : 'li';
                $attr = $this->_atributosItem($item);
                $menuItem = new Selector($selector, $attr);

                if (property_exists($item, 'url')) {
                    $link = new Selector('a', ['href' => $item->url]);
                    $label = !empty($item->encode_html) ? htmlentities($item->label) : $item->label;
                    $link->addInicio($label);
                    $menuItem->addFinal($link->render());
                }

                if (!empty($item->submenu)) {

                    $sm = $item->submenu;
                    $selector = !empty($sm->selector) ? $sm->selector : 'ul';
                    $attr = $this->_atributosItem($sm);
                    $contenedor_sm = new Selector($selector, $attr);

                    if (!empty($sm->items)) {

                        foreach ($sm->items as $k => $item_sm) {
                            $selector = !empty($item_sm->selector) ? $item_sm->selector : 'li';
                            $attr = $this->_atributosItem($item_sm);
                            $submenuItem = new Selector($selector, $attr);

                            if (property_exists($item_sm, 'url')) {
                                $link_sm = new Selector('a', ['href' => $item_sm->url]);
                                $label = !empty($item_sm->encode_html) ? htmlentities($item_sm->label) : $item_sm->label;
                                $link_sm->addInicio($label);
                                $submenuItem->addFinal($link_sm->render());
                            }

                            $contenedor_sm->addFinal($submenuItem->render());
                        }

                        $menuItem->addFinal($contenedor_sm->render());
                    }
                }

                $this->_contenedor->addFinal($menuItem->render());
            }
        }
    }

    /**
     * Renderiza un menu
     *
     * @internal Genera el HTML de un menu creado en el Framework, con toda la personalizacion creada
     * @method render
     */
    function render() {

        $selector = !empty($this->_configuracion->selector) ? $this->_configuracion->selector : 'ul';
        $attr = $this->_atributosItem($this->_configuracion);
        $this->_contenedor = new Selector($selector, $attr);
        $this->_agregarItems($this->_configuracion);

        return $this->_contenedor->render();

    }

}
