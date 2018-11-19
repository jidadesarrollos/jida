<?PHP
/**
 * Definición de la clase
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package
 * @category Controller
 * @version 0.1
 */

namespace Jida\Jadmin\Controllers;

use Jida\RenderHTML as RenderHTML;
use Jida\Helpers as Helpers;
use Jida\RenderHTML\Vista as Vista;

class Perfiles extends JController {

    /**
     * Funcion constructora
     */
    function __construct ($id = "") {

        parent::__construct();
        $this->url = "/jadmin/perfiles/";
        $this->layout = "jadmin.tpl.php";

    }

    function index () {

        $this->tituloPagina = "Lista de Perfiles";
        $this->vista = "vistaPerfiles";
        $qVista = "select id_perfil,perfil  \"Perfil\" from s_perfiles";
        $vista = new Vista($qVista, $GLOBALS['configPaginador'], 'Perfiles');
        $vista->setParametrosVista($GLOBALS['configVista']);
        $vista->tipoControl = 2;
        $vista->acciones = [
            'Registrar' => ['href' => $this->url . 'set-perfiles'],
            'Modificar' => [
                'href'          => $this->url . 'set-perfiles',
                'data-jvista'   => 'seleccion',
                'data-multiple' => 'true',
                'data-jkey'     => 'perfil'
            ],
            'Eliminar'  => [
                'href'          => $this->url . 'eliminar',
                'data-jvista'   => 'seleccion',
                'data-multiple' => 'true',
                'data-jkey'     => 'perfil'
            ],

        ];
        $this->dv->vistaPerfiles = $vista->obtenerVista();
    }

    /**
     * Procesar un perfil
     * @method process
     */
    function setPerfiles () {

        $pk = "";
        $tipoForm = 1;
        if ($this->entero($this->get('perfil'))) {

            $pk = $this->get('perfil');
            $tipoForm = 2;
        }

        $form = new Formulario('Perfiles', $tipoForm, $pk, 2);
        $form->action = $this->url . "set-perfiles/";
        $form->tituloFormulario = "Gesti&oacute;n de Perfiles";

        if ($this->post('btnPerfiles')) {

            $msj = 'No se ha podido registrar el perfil, vuelva a intenarlo';
            $validacion = $form->validarFormulario();
            if ($validacion === true) {
                $perfil = New Perfil($pk);
                $_POST['clave_perfil'] = Helpers\Cadenas::upperCamelCase($_POST['perfil']);
                #Debug::mostrarArray($_POST);
                $guardado = $perfil->salvar($_POST);
                if ($guardado['ejecutado']) {
                    $msj = "El perfil <strong>$perfil->perfil</strong> ha sido registrado exitosamente";
                    RenderHTML\Vista::msj('perfiles', 'suceso', $msj, '/jadmin/perfiles/');
                }
                else {
                    if ($guardado['unico'] == 1) {
                        $msj = "El perfil <strong>$_POST[nombre_perfil]</strong> ya se encuentra registrado";
                    }
                }
            }
            RenderHTML\Formulario::msj('error', $msj);
        }
        $this->dv->form = $form->armarFormulario();
    }//final funcion

    function eliminar () {

        if ($this->get('perfil')) {
            $total = explode(",", $this->get('perfil'));
            $perfil = new Perfil();

            if (count($total) == 1 and $this->get('perfil') > 0) {
                $perfil->eliminarObjeto($this->get('perfil'));
                $msj = "Perfil eliminado exitosamente";
                RenderHTML\Vista::msj('perfiles', 'error', $msj, $this->urlController());
            }
            else {
                $noNumerico = false;
                foreach ($total as $key => $value) {
                    if ($this->entero($value) == 0) {
                        $noNumerico = true;
                    }

                }
                if ($noNumerico !== true) {
                    $perfil->eliminarMultiplesDatos($total, 'id_perfil');
                    $msj = "Perfiles eliminados exitosamente";
                    RenderHTML\Vista::msj('perfiles', 'error', $msj, $this->urlController());
                }
                else {
                    $msj = "No se ha logro eliminar el perfil, porfavor vuelva a intentarlo";
                    RenderHTML\Vista::msj('perfiles', 'error', $msj, $this->urlController());
                }
            }
        }
        else {
            $msj = "No se ha podido realizar la acci&oacute;n vuelva a intentarlo";
            RenderHTML\Vista::msj('perfiles', 'error', $msj, $this->urlController());
        }

    }//fin eliminarCategorias

}


