<?php
/**
 * Objecto Controlador Padre
 * ce: 4
 */

namespace Jida\Core\Controlador;

use Jida\Configuracion\Config;
use Jida\Core\ObjetoManager;
use Jida\Manager\Vista\Layout;

class Control {

    use ObjetoManager, Inicio, Url, Respuesta, Peticion;
    use Getter;

    public $multiidioma;
    public $titulo;
    protected $vista;
    protected $helpers = [];

    /**
     * @var Layout Objeto layout instanciado
     */
    protected $Layout;
    protected $modelos = [];
    protected $modelo;
    protected $usuario;
    protected $dv;

    private $post;

    private $get;
    private $_layout;
    private $request;

    static private $_ce = 30003;

    function __construct () {

        $this->_inicializar();
        $this->_procesarPeticiones();

    }

    /**
     * Define el layout a utilizar
     * @method layout
     *
     * @since 1.4
     * @param string $layout
     * @return string
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
                $this->_data->{$key} = $value;
            }
        }
        else {
            $this->_data->{$data} = $valor;
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