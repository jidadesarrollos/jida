<?php
/**
 * Objecto Controlador Padre
 * ce: 1
 */

namespace Jida\Core\Controlador;

use Jida\Configuracion\Config;
use Jida\Core\Manager\DataVista;

class Control {

    use \Jida\Core\ObjetoManager;
    use Inicio, Url, Respuesta;

    public $multiidioma;
    public $titulo;
    protected $vista;
    protected $helpers = [];

    protected $modelos = [];
    protected $usuario;

    private $post;

    private $get;
    private $_layout;
    private $request;

    /**
     * Objejo DataVista
     * @property object DataVista
     * @see DataVistaa
     */
    private $_dataVista;
    static private $_ce = 30003;

    function __construct () {

        $this->_inicializar();

    }

    /**
     * Define el layout a utilizar
     * @method layout
     *
     * @since 1.4
     */
    public function layout ($layout = "") {

        if (!empty($layout) and !strpos($layout, ".tpl.php")) {
            $layout .= ".tpl.php";
            $this->_layout = $layout;
        }

        return $this->_layout;

    }

    /**
     * Registra informacion a pasar a la vista
     *
     * @param mixed $data nombre del valor a pasar o arreglo de valores, compuesto por clave y valor.
     * @param mixed $valor Valor a asignar a la variable pasada a la vista, solo tomado en cuenta si
     * $data es una cadena.
     *
     */
    protected function data ($data, $valor = "") {

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->_dataVista->{$key} = $value;
            }
        }
        else {
            $this->dv->{$data} = $valor;
        }

    }

    /**
     * Retorna el objeto de configuración de la aplicación
     *
     * @return {object} \App\Config\Configuracion
     */
    protected function _conf () {

        return Config::obtener();
    }

    /**
     * Devuelve o modifica el nombre de la vista a renderizar
     *
     * @param string $vista si es pasado, se modificara el nombre de la vista a renderizar
     *
     * @return string nombre de la vista
     */
    public function vista ($vista = "") {

        if ($vista and $vista != $this->vista) {
            $this->vista = $vista;
        }

        return $this->vista;

    }
}