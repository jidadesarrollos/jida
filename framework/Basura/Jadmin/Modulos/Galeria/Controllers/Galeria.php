<?php
/**
 * Clase Modelo
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace Jida\Jadmin\Modulos\Galeria\Controllers;

if (!defined('IMG_TAM_LG'))
    define('IMG_TAM_LG', '1200');
if (!defined('IMG_TAM_LG'))
    define('IMG_TAM_MD', '800');
if (!defined('IMG_TAM_LG'))
    define('IMG_TAM_SM', '400');
if (!defined('IMG_TAM_LG'))
    define('IMG_TAM_XS', '140');

use Jida\Modelos as Modelos;
use Jida\Medios as Medios;
use Jida\Render as Render;
use Jida\Jadmin\Controllers\JController as JController;

class Galeria extends JController {

    var $manejoParams = true;

    function __construct ($js = true) {

        parent::__construct();
        $this->modelo = new Modelos\ObjetoMedia();

        if ($js === true) {

            $this->dv->addJs([
                                 '/Framework/htdocs/js/dev/jCargaFile.js'
                             ],
                             false);

        }

        $this->dv->addJsModulo(['galeria-jd' => 'galeria.js']);

        if ($this->solicitudAjax())
            $this->layout('ajax');

    }

    /**
     * Testing de carga
     */
    function form () {

        $this->dv->addJs(
            [
                '/Framework/htdocs/js/jadmin/cargaArchivos.js'
            ],
            false);

    }

    function cargaForm () {

        // Medios\Debug::imprimir($_POST,$_FILES,true);
    }

    function index () {

        $this->vista = "galeria";

        $this->dv->seleccionMultiple = true;
        if ($this->post('funcion') == 'portada')
            $this->dv->seleccionMultiple = false;

        $this->data([
                        'objetosGaleria' => $this->modelo->select()->obt()
                    ]);

    }

    function imagenAjax () {

        $respuesta = ['error' => true];

        if ($_FILES['archivoGaleria']) {

            $anio = date('Y');
            $mes = date('m');
            $archivo = new Medios\Imagen('archivoGaleria');
            $pathWeb = "/media/$anio/$mes/";

            if ($archivo->validarCarga()) {

                if ($archivo->validarCargaImagen()) {

                    $path = HTDOCS_DIR . 'img/media/' . $anio . '/' . $mes;

                    if (!Medios\Directorios::validar($path))
                        Medios\Directorios::crear($path);

                    if ($archivo->moverArchivosCargados($path, true)) {

                        $data = $this->_copiarImagenes($archivo, $pathWeb);
                        if ($data) {

                            $respuesta['error'] = false;
                            $respuesta['path'] = URL_IMGS . 'media/' . $anio . '/' . $mes;
                            $respuesta['data'] = $data;
                            if ($this->modelo->salvarTodo($data)) {
                                $respuesta['ids'] = $this->modelo->getResult()->ids();
                            }

                        }

                    }
                }
                else $respuesta['msj'] = 'Formatos de Imagen no válidos';

            }
            else {

                $respuesta['msj'] = 'No se pudo realizar la carga, por favor vuelva a intentarlo';
            }
        }
        $this->respuestaJson($respuesta);

    }

    private function _copiarImagenes ($img, $path) {

        $imgs = $img->getArchivosCargados();
        $dataMedia = [];

        foreach ($imgs as $key => $file) {
            $nombreImg = str_replace("." . $file['extension'], '', $file['nombre']);

            $dataMedia[] = [
                'tipo_media'   => 1,
                'objeto_media' => $nombreImg . "." . $file['extension'],
                'interno'      => 1,
                'directorio'   => $path,

                'meta_data' => json_encode([
                                               'img' => $nombreImg . "." . $file['extension'],
                                               'sm'  => $nombreImg . "-sm." . $file['extension'],
                                               'min' => $nombreImg . "-xs." . $file['extension'],
                                               'md'  => $nombreImg . "-md." . $file['extension'],
                                               'lg'  => $nombreImg . "-lg." . $file['extension'],
                                           ])
            ];
            $img->redimensionar(IMG_TAM_LG, IMG_TAM_LG, $file['path'], $file['path']);
            $img->redimensionar(IMG_TAM_MD,
                                IMG_TAM_MD,
                                $file['path'],
                                $file['directorio'] . "/" . $nombreImg . "-md." . $file['extension']);
            $img->redimensionar(IMG_TAM_SM,
                                IMG_TAM_SM,
                                $file['path'],
                                $file['directorio'] . "/" . $nombreImg . "-sm." . $file['extension']);
            $img->redimensionar(IMG_TAM_XS,
                                IMG_TAM_XS,
                                $file['path'],
                                $file['directorio'] . "/" . $nombreImg . "-xs." . $file['extension']);

            $response['imagenes'][] = [
                'nombre' => $file['nombre'],
                "ext"    => $file['extension']
            ];
        }

        return $dataMedia;
    }

    protected function _obtFormMedia ($id) {

        // \Jida\Medios\Debug::imprimir('_obtFormMedia',$_GET,$id);

        $form = new Render\Formulario('GestionObjetoMedia', $id);
        Medios\Sesion::set('objetoMedia', $id);
        $form
            ->boton('principal')
            ->attr([
                       'value' => 'Guardar',
                       'type'  => 'button'
                   ])->data([
                                'accion' => 'guardarObjeto',
                                'id'     => $id,
                                'config' => '{"post":"guardarMedia"}'
                            ]);
        Medios\Sesion::set('_formMedia', $form);

        return $form;

    }

    function gestionMedia () {

        $this->dv->addJsAjax('/Framework/Jadmin/Modulos/Galeria/htdocs/js/formulario.js', false);

        if ($this->solicitudAjax()) {

            $this->layout('ajax');
            $id = $this->get('id');
            $this->modelo->obtenerBy($id, 'id_objeto_media');

            $this->data([
                            'form' => $this->_obtFormMedia($id)->render(),
                            'obj'  => $this->modelo
                        ]);

        }
    }

    function editarMedia () {

        $this->dv->addJsModulo([
                                   'formulario-galeria' => 'formulario.js'
                               ]);
        if ($this->post('btnGestionObjetoMedia')) {

            if ($this->entero($this->get('objeto'))) {
                $id = $this->get('objeto');
            }
            else {
                $id = Medios\Sesion::obt('objetoMedia');
            }

            if (Medios\Sesion::obt('_formMedia') instanceof Render\Formulario) {
                $form = Medios\Sesion::obt('_formMedia');
            }
            else {
                $form = $this->_obtFormMedia($id);
            }

            $result = [
                'msj'       => 'No se pudo guardar el objeto',
                'ejecutado' => false,
            ];
            $this->modelo->instanciar($id);

            if ($form->validar()) {

                if ($this->modelo->salvar($_POST)->ejecutado()) {
                    $result = [
                        'msj'       => 'El objeto se ha guardado exitosamente',
                        'ejecutado' => true
                    ];
                }

            }

            $this->respuestaJson($result);

        }
        $this->respuestaJson(['error' => '404']);
    }

    function eliminarImagenes () {

        if ($this->solicitudAjax() and $this->post('accion') == 'dlt') {
            $imagenes = explode(",", $this->post('img'));
            $Media = new ObjetoMedia();
            $data = $Media->consulta()->in($imagenes, 'id_objeto_media')->obt();
            foreach ($data as $key => $valores) {
                $imagens = json_decode($valores['meta_data']);
                foreach ($imagens as $key => $value) {
                    Archivo::eliminarArchivo(HTDOCS_DIR . 'img' . $valores['directorio'] . $value);
                }

            }
            if ($Media->eliminar($imagenes, 'id_objeto_media')) {
                $this->respuestaJson(['ejecutado' => true]);
            }
            $this->respuestaJson([
                                     'ejecutado' => false,
                                     'error'     => 'No se han podido eliminar los objetos'
                                 ]);
        }
        else {
            $this->_404();
        }
    }

}
