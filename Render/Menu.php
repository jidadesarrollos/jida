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
     * Funcion constructora
     * @method __construct
     */
    function __construct($menu = "") {

        if ($menu) {
            $this->_cargarMenu($menu);
        }

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
     * @method _cargarFormulario
     * @param string $form Nombre del Formulario
     */
    private function _cargarMenu($menu) {

        if (!strrpos($menu, ".json")) {
            $menu = $menu . ".json";
        }

        $path = Rutas::obtener($menu, 'menu')->absoluta();

        if (!Helpers\Directorios::validar($path)) {
            throw new Excepcion("No se consigue el archivo de configuracion del menu " . $path, $this->_ce . '2');
        }

        $this->_path = $path;

    }

}
