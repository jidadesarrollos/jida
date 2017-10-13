<?php
/**
 * Clase Controladora
 * @author Julio Rodriguez
 * @package
 * @version
 * @category Controller
 */

namespace Jida\Jadmin\Modulos\Formularios\Controllers;

use Jida\Jadmin\Controllers\JController as JController;
use Jida\Helpers as Helpers;
use Jida\Render as Render;
use Jida\Core\GeneradorCodigo;

class Fcontroller extends JController {

    use GeneradorCodigo\GeneradorArchivo;
    /**
     * @property object $_formulario Objeto std creado a partir del JSON de un formulario cargado
     * @see \Jida\Modelos\Formulario
     */
    protected $_formulario;

    /**
     * Retorna la data principal de un formulario consultado
     *
     * @param string $formulario Nombre del Formulario
     * @param string $path Directorio fisico del formulario
     * @return array
     */
    protected function _dataFormulario($formulario, $modulo = "app") {

        $this->_instanciarFormulario($formulario, $modulo);

        return [
            'id' => $this->_formulario->identificador,
            'nombre' => $this->_formulario->nombre,
            'estructura' => $this->_formulario->estructura,
            'identificador' => $this->_formulario->identificador,
            'clave_primaria' => $this->_formulario->clave_primaria,
            'campos' => count($this->_formulario->campos),
            'query' => $this->_formulario->query,
            'modulo' => $modulo

        ];

    }

    /**
     * Retorna la instancia del formulario Dado
     *
     * @param $id
     * @param string $path
     * @return bool|\Jida\Modelos\Formulario
     */
    protected function _instanciarFormulario($id, $modulo = 'jida') {

        Helpers\Sesion::destruir('JFormulario');
        if (is_object(Helpers\Sesion::obt('JFormulario'))) {

            $clase = Helpers\Sesion::obt('JFormulario');
            if ($clase->identificador == $id) {
                $this->_formulario = $clase;
                return $this->_formulario;
            }

        }

        $formulario = new \Jida\Modelos\Formulario($id, $modulo);
        $this->_formulario = $formulario;
        return $this->_formulario;

    }


    function __construct() {
        parent::__construct();
        $this->dv->addJsModulo('formularios.js', 'formularios');
    }

    function configuracion() {
        $url = '/jadmin/formularios/campos/configuracion/:formulario/:campo';
        if ($this->solicitudAjax() and $this->post('campo')) {


        }
    }
}