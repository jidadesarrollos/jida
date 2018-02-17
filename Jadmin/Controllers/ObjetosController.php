<?PHP

/**
 * Definición de la clase ObjetosController
 *
 * @author   Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version  0.1
 */

namespace Jida\Jadmin\Controllers;

use Exception;
use Jida\Render as Render;
use Jida\Modelos as Modelos;
use Jida\RenderHTML\Vista as Vista;
use Jida\RenderHTML as RenderHTML;
use Jida\Helpers as Helpers;

class ObjetosController extends JController {


    var $Mperfil = "";
    var $manejoParams = TRUE;

    function __construct($id = "") {

        $this->helpers = ['Arrays'];

        parent::__construct();

        if (!$this->solicitudAjax()) {
            $this->layout = "jadmin.tpl.php";
        }

        $this->url = "/jadmin/objetos/";
        $this->modelo = new \Jida\Modelos\Objeto();
        $this->data('title', 'Objetos');
    }

    /**
     * Permite visualizar los objetos de base de datos
     *
     * @method analizadorBD
     */
    function analizadorBD() {

        $data = $this->modelo->bd->obtTablasBD(TRUE);

        for ($i = 0; $i < count($data); $i++) {
            array_unshift($data[$i], $data[$i]['table_name']);
        }

        $vista = new Jvista($data, ['titulos' => ['',
            'name',
            'type',
            'collation',
            'create'
        ]
        ]);

        $vista->configuracion('nroFilas', 50);

        $vista->accionesFila([
            ['span'  => 'fa fa-edit',
             'title' => "Generar Modelo",
             'href'  => $this->obtUrl('crearObjeto', ['{clave}',
                 'jida'
             ])
            ],

        ])->acciones([
            'Crear Objetos' => ['href' => $this->getUrl('crearObjeto')]
        ]);
        $vista->controlFila = 2;
        $this->dv->tabla = $vista->obtenerVista();
    }

    function crearObjeto($objeto, $ruta = "") {

        $elemento = new Jida\Elemento;
        $generador = new Jida\GeneradorModelo;
        if ($ruta == 'jida') {
            $generador->ubicacion(DIR_FRAMEWORK . 'ModelFramework/');
        }
        $prefijos = ['m_',
            's_',
            't_'
        ];
        array_walk($prefijos, function (&$valor, $clave) {

            $valor = "/^" . $valor . "/";
        });
        $generador->extensionClass = FALSE;
        if ($generador->generar($objeto, $prefijos)) {
            $msj = Helpers\Mensajes::crear('suceso', 'Objeto creado exitosamente');
            Session::set('__mensaje', $msj);
        }

        $this->redireccionar($this->getUrl('analizadorBD'));

    }

    function index() {

        $this->vista = "lista";
        $this->tituloPagina = "Objetos del Sistema";
        $query = "select id_objeto,objeto as \"Objeto\",a.descripcion \"Descripci&oacute;n\", componente  as Componente
                        from s_objetos a
                        join s_componentes b on (b.id_componente = a.id_componente)";

        $vista = $this->vistaObjetos($query);
        $vista->tituloVista = "Objetos";
        $msjError = "No hay registros de " . $vista->tituloVista . " <a href=\"" . $this->url . "set-objeto\">Agregar objeto</a>";
        $vista->mensajeError = Helpers\Mensajes::mensajeAlerta($msjError);
        $this->dv->vista = $vista->obtenerVista();

    }

    /**
     * Verifica los objetos existentes en un directorio especificado o en todos.
     *
     * @internal Si la funcion consigue nuevos controladores Los registra en base de datos, si valida que se encuentran
     * registrados controladores que ya no existen, los elimina
     *
     * @method validarObjetos
     * @access   private
     */
    private function validarObjetos(Componente $componente) {

        $objetosInexistentes = [];
        $objetosNuevos = [];
        $nombreComponente = Helpers\Cadenas::upperCamelCase($componente->componente);
        if ($nombreComponente == 'Principal') {
            $rutaComponente = DIR_APP . "Controller/";
        } else {
            $rutaComponente = ($nombreComponente == 'Jadmin') ? DIR_FRAMEWORK . 'Jadmin/Controllers/' : DIR_APP . "Modulos/" . $nombreComponente . "/Controller/";
        }

        $objetosCarpeta = [];
        Helpers\Directorios::listarDirectoriosRuta($rutaComponente, $objetosCarpeta, "/^.*Controller.class.php$/");
        array_walk($objetosCarpeta, function (&$objeto, $key) {

            $objeto = str_replace("Controller.class.php", "", $objeto);
        });

        $objetos = new Objeto();
        $dataBD = $objetos->obtenerTodo();

        $objetosBD = [];
        //Recorro los objetos de la bd
        foreach ($dataBD as $key => $valor) {
            $objetosBD[] = $valor['objeto'];
        }

        $nuevos = array_diff($objetosCarpeta, $objetosBD);
        $inexistentes = array_diff($objetosBD, $objetosCarpeta);

        $arr = [];
        foreach ($nuevos as $key => $value) {
            $arr[$key]['objeto'] = $value;
            $arr[$key]['id_componente'] = $componente->id_componente;
            $arr[$key]['descripcion'] = '';
        }

        if (count($nuevos) > 0) {
            $objetos->salvarTodo($arr);
        }

        if (count($inexistentes) > 0) {
            $objetos->eliminar($inexistentes, 'objeto');
        }
    }

    /**
     * Lista los objetos registrados
     * @method lista
     *
     */
    function lista($item) {

        $this->tituloPagina = "jida-Registro Componentes";
        $this->dv->vista = "";
        if (!empty($item)) {
            $idComponente = $this->entero($this->get('comp'));
            $comp = new Modelos\Componente($idComponente);

            $this->validarObjetos($comp);
            $query = "select id_objeto,objeto as \"Objeto\",descripcion \"Descripci&oacute;n\" from s_objetos where id_componente = $idComponente";
            $vista = $this->vistaObjetos($query);
            $vista->tituloVista = "Objetos del Componente " . $comp->componente;
            $vista->mensajeError = "No hay registros de " . $vista->tituloVista . " <a href=\"" . $this->url . "set-objeto/comp/$idComponente\">Agregar objeto</a>";
            $this->dv->vista = $vista->obtenerVista();
        } else {
            Render\JVista::msj('componentes', 'alert', 'Debe seleccionar un componente');
        }

        if ($this->solicitudAjax())
            $this->layout = '../ajax.tpl.php';
    }


    protected function vistaObjetos($query) {

        $vista = new Vista($query, $GLOBALS['configPaginador'], "Objetos");
        $vista->setParametrosVista(['idDivVista' => 'objetos']);
        $vista->setParametrosVista($GLOBALS['configVista']);
        $vista->filaOpciones = [
            0 => ['a' => [
                'atributos' => ['class' => 'btn',
                                'title' => 'ver metodos',
                                'href'  => $this->url . "metodos/obj/{clave}"
                ],
                'html'      => ['span' => ['atributos' => ['class' => 'glyphicon glyphicon-eye-open']]]
            ]
            ],

            1 => ['a' => [
                'atributos' => ['class' => 'btn',
                                'title' => 'Agregar Descripci&oacute;n',
                                'href'  => $this->url . "/add-descripcion/obj/{clave}"
                ],
                'html'      => ['span' => ['atributos' => ['class' => 'fa fa-info']]]
            ]
            ],
            2 => ['a' => [
                'atributos' => ['class' => 'btn',
                                'title' => 'Asignar Accesos',
                                'href'  => $this->url . "asignar-acceso/obj/{clave}"
                ],
                'html'      => ['span' => ['atributos' => ['class' => 'fa fa-users']]]
            ]
            ],

        ];
        $vista->acciones = [
            'Agregar Descripci&oacute;n' => ['href'          => $this->url . 'set-objeto/obj/',
                                             'data-jvista'   => 'seleccion',
                                             'data-multiple' => 'true',
                                             'data-jkey'     => 'obj'
            ],
        ];
        $vista->mensajeError = "No hay registros de " . $vista->tituloVista . " <a href=\"" . $this->url . "set-objeto/\">Agregar objeto</a>";

        return $vista;
    }

    /**
     * Permite agregar un nombre descriptivo a un objeto
     *
     * La descripción del objeto es usada para que un usuario final pueda visualizar un nombre entendible
     * @method addDescripcion
     */
    function addDescripcion() {

        if ($this->entero($this->get('obj'))) {

            if (isset($_POST['s-ajax'])) {
                $this->layout = 'ajax.tpl.php';
            }

            $form = new Formulario('DescripcionMetodo', 2, $this->get('obj'), 2);
            $Objeto = new Objeto($this->get('obj'));

            $form->action = "$this->url" . 'add-descripcion/obj/' . $Objeto->id_objeto;
            $form->tituloFormulario = "Agregar Descripci&oacute;n del Objeto " . $Objeto->objeto;
            if ($this->post('btnDescripcionMetodo')) {
                $validacion = $form->validarFormulario();
                if ($validacion === TRUE) {
                    if ($Objeto->salvar($_POST)->ejecutado() == 1) {
                        RenderHTML\Vista::msj('objetos', 'suceso', "La descripci&oacute;n del Metodo <strong>$Objeto->objeto</strong> ha sido registrada exitosamente");
                    } else {
                        RenderHTML\Vista::msj('objetos', 'error', "No se ha podido registrar la descripci&oacute;n, por favor vuelva a intentarlo");
                    }
                } else {
                    RenderHTML\Vista::msj('objetos', 'error', "No se ha podido registrar la descripci&oacute;n, vuelva a intentarlo luego");
                }
                $this->redireccionar('/jadmin/objetos/lista/comp/' . $Objeto->id_componente);
            }

            $this->dv->form = $form->armarFormulario();
        } else {

            throw new Exception("Pagina no conseguida", 404);
        }

    }

    /**
     * Valida la estructura del nombre de un objeto
     * @method validarNombreObjeto
     */
    private function validarNombreObjeto($nombre) {

        $nombreClase = Cadenas::upperCamelCase($nombre . "Controller");
        if (class_exists($nombreClase)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Permite visualizar los metodos de un controlador
     *
     * @see    MetodosController::vistaMetodos();
     * @method metodos
     * @access public
     */
    function metodos() {

        $this->vista = "listaMetodos";
        $controladorMetodos = new MetodosController();
        $this->dv->vistaMetodos = $controladorMetodos->metodosObjeto();
    }

    /**
     * Muestra un formulario para dar acceso de los perfiles registrados al metodo de un objeto
     *
     * @method accesoPerfiles
     * @access public
     *
     */
    function accesoPerfiles() {

        if ($this->get('metodo')) {

            $metodo = new Metodo($this->get('metodo'));
            $form = new Formulario('PerfilesAMetodos', 2, $this->get('metodo'), 2);

            $form->action = $this->url . "acceso-perfiles/metodo/" . $this->get('metodo');
            $form->valueSubmit = "Asignar Perfiles";
            $form->tituloFormulario = "Asignar acceso de perfiles a metodo $metodo->nombre_metodo";

            if ($this->post('btnPerfilesAMetodos')) {
                $validacion = $form->validarFormulario($_POST);
                if ($validacion === TRUE) {
                    $accion = $metodo->asignarAccesoPerfiles($this->post('id_perfil'));
                    if ($accion['ejecutado'] == 1) {
                        RenderHTML\Vista::msj('metodosObjeto', 'suceso', 'Asignado los perfiles de acceso al metodo ' . $metodo->nombre_metodo, $this->url . "metodos/obj/" . $metodo->id_objeto);
                    } else {
                        RenderHTML\Formulario::msj('error', 'No se pudieron asignar los perfiles, por favor vuelva a intentarlo');
                    }
                } else {
                    RenderHTML\Formulario::msj('error', 'No se han asignado perfiles');
                }
            }
            $this->dv->formAcceso = $form->armarFormulario();
        } else {
            RenderHTML\Vista::msj('objetos', 'error', 'Debe seleccionar un metodo', $this->url);
        }

    }

    /**
     * Muestra un formulario para asignar el acceso de los perfiles del sistema a un objeto determinado
     * @method asignarAcceso
     *
     * @access public
     *
     */
    function asignarAcceso() {

        if ($this->entero($this->get('obj')) > 0) {
            $this->vista = "accesoPerfiles";
            $form = new Formulario('PerfilesAObjetos', 2, $this->get('obj'), 2);
            $obj = new Objeto($this->entero($this->get('obj')));

            $form->action = $this->getUrl('asignarAcceso', ['obj' => $this->get('obj')]);
            $form->valueBotonForm = "Asignar Perfiles a Objeto";
            $form->tituloFormulario = "Asignar acceso de perfiles al objeto $obj->objeto";
            if ($this->post('btnPerfilesAObjetos')) {
                $validacion = $form->validarFormulario();
                if ($validacion === TRUE) {
                    $accion = $obj->asignarAccesoPerfiles($this->post('id_perfil'));
                    if ($accion['ejecutado'] == 1) {
                        RenderHTML\Vista::msj("objetos", 'suceso', 'Asignados los perfiles de acceso al objeto ' . $obj->objeto, $this->getUrl('lista', ['comp' => $obj->id_componente]));
                    } else {
                        RenderHTML\Formulario::msj('error', "No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                    }
                } else {
                    RenderHTML\Formulario::msj('error', "No se han asignado perfiles");
                }
            }
            $this->dv->formAcceso = $form->armarFormulario();
        } else {
            RenderHTML\Vista::msj("objetos", 'suceso', "Debe seleccionar un objeto", $this->urlController());
        }
    }

}