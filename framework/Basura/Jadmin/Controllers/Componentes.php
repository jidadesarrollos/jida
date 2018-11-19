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

use Jida\Render as Render;
use Jida\Medios as Medios;
use Jida\Modelos\Viejos as Modelos;

class Componentes extends JController {

    var $layout = "jadmin.tpl.php";
    var $manejoParams = true;

    function __construct ($id = "") {

        parent::__construct();

        $this->url = "/jadmin/componentes/";
        $this->dv->title = "Componentes de " . TITULO_SISTEMA;
    }

    function index () {

        $this->vista = "vistaComponentes";

        $vista = new Render\JVista('Jida\Modelos\Componente.obtComponentes', [
            'titulos' => ['Componente']
        ], 'Componentes');

        $vista->controlFila = 1;
        $vista->accionesFila([
                                 [
                                     'span'        => 'glyphicon glyphicon-folder-open',
                                     'title'       => "Ver objetos del componente",
                                     'href'        => $this->obtUrl('objetos.lista', ['{clave}']),
                                     'data-jvista' => 'modal'
                                 ],
                                 [
                                     'span'        => 'glyphicon glyphicon-edit',
                                     'title'       => "Asignar perfiles de acceso",
                                     'href'        => $this->obtUrl('asignarAcceso', ['{clave}']),
                                     'data-jvista' => 'modal'
                                 ]
                             ]);

        $vista->addMensajeNoRegistros('No hay Componentes');

        $this->dv->vista = $vista->obtenerVista();

    }

    function setComponente ($idComponente = "") {

        $tipoForm = 1;

        if (!empty($idComponente))
            $tipoForm = 2;

        $form = new Render\Formulario('Componente', $tipoForm, $idComponente, 2);
        $form->action = $this->url . 'set-componente';
        $form->valueSubmit = "Guardar Componente";

        if ($this->post('btnComponente')) {
            $this->entero($this->post('id_componente'));

            if ($this->validarComponente($this->post('componente'))) {
                $comp = new Modelos\Componente($idComponente);
                if ($F->validarFormulario()) {
                    $_POST['componente'] = strtolower($this->post('componente'));
                    if ($comp->salvar($_POST)->ejecutado() == 1) {
                        RenderHTML\Vista::msj('componentes',
                                              'suceso',
                                              'Componente <strong>' . $this->post('componente') . '</strong> guardado',
                                              $this->url . '');
                    }
                    else {
                        RenderHTML\Formulario::msj('error', 'No se pudo registrar el componente');
                    }
                }
            }
            else    RenderHTML\Formulario::msj('error', 'El componente no existe');

        }
        if ($this->solicitudAjax())
            $this->layout = '../ajax.tpl.php';

        $this->dv->fComponente = $form->armarFormulario();
    }

    private function validarComponente ($componente) {

        if (in_array($componente, $GLOBALS['modulos']))
            return true;
        else
            return false;
    }

    function asignarAcceso ($acceso = '') {

        if (!empty($acceso)) {

            $this->vista = "accesoPerfiles";
            $form = new Render\Formulario('PerfilesAComponentes', $acceso);
            $comp = new Modelos\Componente($acceso);

            $form->action = $this->obtUrl('asignarAcceso', [$acceso]);
            $form->valueSubmit = "Asignar Perfiles a Objeto";
            $form->titulo("Asignar acceso de perfiles al componente <strong> $comp->componente</strong>");

            if ($this->post('btnPerfilesAComponentes')) {

                if ($form->validar()) {

                    if ($comp->asignarAccesoPerfiles($this->post('id_perfil'))->ejecutado()) {
                        Render\JVista::msj('componentes',
                                           'suceso',
                                           'Asignados los perfiles de acceso al componente ' . $comp->componente,
                                           $this->urlController());
                    }
                    else
                        RenderHTML\Formulario::msj('error',
                                                   'No se pudieron asignar los perfiles, por favor vuelva a intentarlo');
                }
                else
                    RenderHTML\Formulario::msj('error', 'No se han asignado perfiles');
            }

            if ($this->solicitudAjax())
                $this->layout = '../ajax.tpl.php';

            $this->dv->formAcceso = $form->armarFormulario();

        }
        else {

            if (!$this->solicitudAjax())
                \Jida\Medios\Rutas::redireccionar($this->url);
            else
                Render\JVista::msj('componentes', 'error', "Debe seleccionar un componente");

        }
    }
}
