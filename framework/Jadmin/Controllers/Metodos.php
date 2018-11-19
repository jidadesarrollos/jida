<?PHP
/**
 * Definición de la clase
 *
 * @author   Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package
 * @category Controller
 * @version  0.1
 */

namespace Jida\Jadmin\Controllers;

use Exception;
use Jida\RenderHTML as RenderHTML;

class Metodos extends JController {

    function __construct($id = "") {

        $this->layout = "jadmin.tpl.php";
        $this->url = "/jadmin/metodos/";
        parent::__construct();
        $this->dv->title = "Metodos";
    }

    /**
     * Funcion controladora de metodos de un objeto
     */
    function metodosObjeto($url = "") {
        $url = (empty($url)) ? $this->url : $url;

        if ($this->entero($this->get('obj'))) {
            $objeto = new Objeto($this->entero($this->get('obj')));
            $this->tituloPagina = "Objeto $objeto->objeto - Metodos";
            $nombreClase = $objeto->objeto . "Controller";
            $clase = new ReflectionClass($nombreClase);
            $metodos = $clase->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($metodos as $key => $value) {
                if ($value->name != '__construct' and $value->class == $nombreClase)
                    $arrayMetodos[$key] = $value->name;
            }

            $claseMetodo = new Metodo();
            $claseMetodo->validarMetodosExistentes($arrayMetodos, $objeto->id_objeto);
            $this->dv->vistaMetodos = Metodos::vistaMetodos($objeto);

            return $this->dv->vistaMetodos;
        } else {
            $this->_404();
        }

        return $this->data;
    }

    function addDescripcion() {
        if ($this->entero($this->get('metodo'))) {

            if (isset($_POST['s-ajax'])) {
                $this->layout = 'ajax.tpl.php';
            }

            $form = new Formulario('DescripcionMetodo', 2, $this->get('metodo'), 2);
            $metodo = new Metodo($this->get('metodo'));
            $form->action = "$this->url" . 'add-descripcion/metodo/' . $metodo->id_metodo;
            $form->tituloFormulario = "Agregar Descripci&oacute;n del metodo " . $metodo->metodo;
            if ($this->post('btnDescripcionMetodo')) {
                $validacion = $form->validarFormulario();
                if ($validacion === TRUE) {
                    if ($metodo->salvar($_POST)->ejecutado() == 1) {
                        RenderHTML\Vista::msj('metodos', 'suceso', "La descripci&oacute;n del Metodo <strong>$metodo->metodo</strong> ha sido registrada exitosamente");
                    } else
                        RenderHTML\Vista::msj('metodos', 'error', "No se ha podido registrar la descripci&oacute;n, por favor vuelva a intentarlo");
                } else
                    RenderHTML\Vista::msj('metodos', 'error', "No se ha podido registrar la descripci&oacute;n, vuelva a intentarlo luego", '/jadmin/objetos/metodos/obj/' . $metodo->id_objeto);
            }

            $this->dv->form = $form->armarFormulario();
        } else {
            throw new Exception("Pagina no conseguida", 404);
        }
    }

    protected function vistaMetodos(Objeto $obj) {
        $query = "select id_metodo,metodo as \"Metodo\",descripcion as \"Descripci&oacute;n\" from s_metodos where id_objeto=$obj->id_objeto";
        $vista = new Vista($query, $GLOBALS['configPaginador'], 'metodos');
        $vista->tituloVista = "Metodos del objeto " . $obj->objeto;
        $vista->setParametrosVista(['idDivVista' => 'metodosObjeto']);

        $vista->filaOpciones = [1 => ['a' => [
            'atributos' => ['class'     => 'btn',
                            'title'     => 'Agregar Descripci&oacute;n',
                            'data-link' => $this->url . "add-descripcion/metodo/{clave}",
                            'href'      => $this->url . "add-descripcion/metodo/{clave}",
                #'data-jvista'=>'modal'
            ],
            'html'      => ['span' => ['atributos' => ['class' => 'fa fa-edit fa-lg']]]
        ]
        ],
                                2 => ['a' => [
                                    'atributos' => ['class'     => 'btn',
                                                    'title'     => 'Editar Perfiles',
                                                    'data-link' => $this->url . "asignar-acceso/metodo/{clave}",
                                                    'href'      => $this->url . "asignar-acceso/metodo/{clave}",
                                        #'data-jvista'=>'modal'
                                    ],
                                    'html'      => ['span' => ['atributos' => ['class' => 'fa fa-users fa-lg']]]
                                ]
                                ],
        ];
        $vista->acciones = ['Asignar perfiles de acceso' => ['href'          => $this->url . 'asignar-acceso/',
                                                             'data-jvista'   => 'seleccion',
                                                             'data-multiple' => 'true',
                                                             'data-jkey'     => 'metodo'
        ],
        ];

        $vista->setParametrosVista($GLOBALS['configVista']);

        return $vista->obtenerVista();
    }

    /**
     * Muestra un formulario para asignar el acceso de los perfiles del sistema a un metodo
     * @method asignarAcceso
     *
     * @access public
     *
     */
    function asignarAcceso() {
        if ($this->entero($this->get('metodo'))) {
            $this->vista = "accesoPerfiles";

            $form = new Formulario('PerfilesAMetodos', 2, $this->get('metodo'), 2);
            $metodo = new Metodo($this->entero($this->get('metodo')));

            $form->action = $this->url . "asignar-acceso/metodo/" . $this->get('metodo');
            $form->valueSubmit = "Asignar Perfiles a Metodo";
            $form->tituloFormulario = "Asignar acceso de perfiles al Metodo " . $metodo->metodo;
            if ($this->post('btnPerfilesAMetodos')) {
                $validacion = $form->validarFormulario($_POST);
                if ($validacion === TRUE) {
                    $accion = $metodo->asignarAccesoPerfiles($this->post('id_perfil'));
                    if ($accion['ejecutado'] == 1) {
                        RenderHTML\Vista::msj('metodos', 'suceso', 'Asignados los perfiles de acceso al metodo ' . $metodo->metodo, "/jadmin/objetos/metodos/obj/" . $metodo->id_objeto);
                    } else {
                        RenderHTML\Formulario::msj('error', "No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                    }
                } else {
                    RenderHTML\Formulario::msj('error', "No se han asignado perfiles");
                }
            }
            $this->dv->formAcceso = $form->armarFormulario();
        } else {
            RenderHTML\Vista::msj('metodos', 'error', "Debe seleccionar un objeto", "jadmin/objetos/metodos/obj" . $metodo->id_objeto);
        }
    }
}
