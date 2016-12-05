<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
namespace Jida\Jadmin\Controllers;
use Jida\Modelos 		as Modelos;
use Jida\Helpers 		as Helpers;
use Jida\Render 		as Render;
class GaleriaController extends JController{
    function __construct(){
        parent::__construct();
        $this->modelo = new Modelos\ObjetoMedia();
		if($this->solicitudAjax())
			$this->layout('ajax');
    }
    function index(){

        $this->vista="galeria";
        if($this->solicitudAjax()){
            $this->dv->addJsAjax('/Framework/htdocs/js/jadmin/galeria.js',false);
        }else{
            $this->dv->addJs('/Framework/htdocs/js/jadmin/galeria.js',false);
        }

        $this->dv->seleccionMultiple=TRUE;
        if($this->post('funcion')=='portada') $this->dv->seleccionMultiple=FALSE;
        //$this->dv->addJs(['adm/galeria.js']);
        $this->dv->imagenes= $this->modelo->select()->obt();

    }

    function biblioteca(){
        $this->dv->addJs('admin/biblioteca.js');
        $this->dv->seleccionMultiple=FALSE;
        $this->dv->imagenes= $this->modelo->select()->obt();
    }

    function elemento(){
        if($this->solicitudAjax() and $this->getEntero($this->post('id')) and $this->post('html')){
            $this->formMedia($this->post('id'));
            $this->dv->srcImagen=$this->post('html');
            $this->dv->data=$this->post();
            Helpers\Sesion::set('objetoMedia',$this->post('id'));
            $this->vista="elemento";

        }else{
            Debug::mostrarArray($this->post());
            $this->_404();
        }


    }
    /**
     * Carga una sola imagen a la biblioteca
     * @method cargarImagen
     */
    function cargarImagen(){
         $img = new Helpers\Imagen('imagenesGaleria');

        $anio = date('Y');
        $mes = date('m');
        $path = HTDOCS_DIR."img/cargas/";
        $response=['error'=>TRUE];
        $response['directorio']=URL_IMGS."cargas/$anio/$mes/";
        if($img->validarCargaImagen()){
            if( $img->validarCarga()){


                	Helpers\Directorios::crear($path.$anio."/$mes");
                    $path = $path.$anio."/".$mes;
                    $names = [];

                    $dataMedia=[];

                    if($img->moverArchivosCargados($path, true)){

                        $imgs = $img->getArchivosCargados();

                        foreach ($imgs as $key => $file) {
                            $dataMedia[]=[
                            'tipo_media'=>'imagen',
                            'objeto_media'=>$file['nombre'].".".$file['extension'],
                            'interno'=>1,
                            'directorio'=>"/cargas/$anio/$mes/",
                            'meta_data'=>json_encode([  'img'        	=>$file['nombre'].".".$file['extension'],
                                                        'sm'	  		=>$file['nombre']."-sm.".$file['extension'],
                                                        'min'			=>$file['nombre']."-min.".$file['extension'],
                                                        'md'     		=>$file['nombre']."-md.".$file['extension'],
                                                        'lg'			=>$file['nombre']."-lg.".$file['extension'],
                                                    ])
                            ];



                            $dimensiones = $img->obtDimensiones($file['path']);

                            if($dimensiones['ancho']>1200 or $dimensiones['alto']>800 or $img->obtPeso($file['path'])>2){
                                $img->redimensionar(1200, 800,$file['path'],$file['path']);
                            }
							$img->redimensionar(IMG_TAM_LG,IMG_TAM_LG,$file['path'],$file['path']);
							$img->redimensionar(IMG_TAM_MD,IMG_TAM_MD,$file['path'],$file['directorio']."/".$file['nombre']."-md.".$file['extension']);
							$img->redimensionar(IMG_TAM_SM,IMG_TAM_SM,$file['path'],$file['directorio']."/".$file['nombre']."-min.".$file['extension']);
							$img->redimensionar(IMG_TAM_XS,IMG_TAM_XS,$file['path'],$file['directorio']."/".$file['nombre']."-sm.".$file['extension']);

                            $response['imagenes'][]=['nombre'=>$file['nombre'],"ext"=>$file['extension']];
                        }
                        $response['error']=FALSE;

                        $this->modelo->salvar($dataMedia[0]);
                        $dataMedia[0]['id']=$this->modelo->getResult()->idResultado();
                        $dataMedia[0]['meta_data']=json_decode($dataMedia[0]['meta_data']);
                        $response['dataImg']=$dataMedia[0];

                    }else{
                        $response['msj']='No se pudo realizar la carga, vuelva a intentarlo';
                    }//FIN IF MOVERaRCHIVO
            }
        }else{
            $response['msj']='Valide el tipo de archivo a cargar y vuelva a intentarlo';
        }

        $this->respuestaJson($response);
    }
    /**
     * Agrega imagenes a la galeria
     * @method agregarImagenes
     */
    function agregarImagenes(){
        $this->layout="ajax.tpl.php";
        $img = new Helpers\Imagen('imagenesGaleria');

        $anio = date('Y');
        $mes = date('m');
        $path = HTDOCS_DIR."img/cargas/";
        $response=['error'=>TRUE];
        $response['directorio']=URL_IMGS."cargas/$anio/$mes/";
        if($img->validarCargaImagen()){
            if( $img->validarCarga()){
                    if(!Helpers\Directorios::validar($path.$anio)) Helpers\Directorios::crear($path.$anio);
                    if(!Helpers\Directorios::validar($path.$anio."/".$mes)) Helpers\Directorios::crear($path.$anio."/$mes");
                    $path = $path.$anio."/".$mes;
                    $names = [];

                    $dataMedia=[];
                    if($img->moverArchivosCargados($path, true)){
                        $imgs = $img->getArchivosCargados();
                        foreach ($imgs as $key => $file) {
                            $dataMedia[]=[
                            'tipo_media'=>1,
                            'objeto_media'=>$file['nombre'].".".$file['extension'],
                            'interno'=>1,
                            'directorio'=>"/cargas/$anio/$mes/",
                            'meta_data'=>json_encode([  'img'        	=>$file['nombre'].".".$file['extension'],
                                                        'sm'	  		=>$file['nombre']."-sm.".$file['extension'],
                                                        'min'			=>$file['nombre']."-min.".$file['extension'],
                                                        'md'     		=>$file['nombre']."-md.".$file['extension'],
                                                        'lg'			=>$file['nombre']."-lg.".$file['extension'],
                                                    ])
                            ];
                			$img->redimensionar(IMG_TAM_LG,IMG_TAM_LG,$file['path'],$file['path']);
							$img->redimensionar(IMG_TAM_MD,IMG_TAM_MD,$file['path'],$file['directorio']."/".$file['nombre']."-md.".$file['extension']);
							$img->redimensionar(IMG_TAM_SM,IMG_TAM_SM,$file['path'],$file['directorio']."/".$file['nombre']."-min.".$file['extension']);
							$img->redimensionar(IMG_TAM_XS,IMG_TAM_XS,$file['path'],$file['directorio']."/".$file['nombre']."-sm.".$file['extension']);

                            $response['imagenes'][]=['nombre'=>$file['nombre'],"ext"=>$file['extension']];
                        }
                        $response['error']=FALSE;

                        $this->modelo->salvarTodo($dataMedia);
                    }else{
                        $response['msj']='No se pudo realizar la carga, vuelva a intentarlo';
                    }//FIN IF MOVERaRCHIVO
            }
        }else{
            $response['msj']='Valide el tipo de archivo a cargar y vuelva a intentarlo';
        }

        $this->respuestaJson($response);
    }//fin funcion
    function formMedia($id=""){

        if(!empty($id) or ($this->solicitudAjax() and $this->getEntero($this->post('media'))>0)){
            if(empty($id))$id=$this->post('media');
            $form = new Render\Formulario('GestionObjetoMedia',2,$id);
            Helpers\Sesion::set("objetoMedia",$this->post('media'));
            $form->action=$this->urlActual()."media/".$id;
            $form->valueBotonForm="Actualizar";
            $form->tipoBoton="button";
            $this->dv->media=$id;
            $this->dv->form = $form->armarFormularioEstructura();
            Helpers\Sesion::set('_formMedia',$form);
        }else{
            $this->_404();
        }
    }
    /**
     * Permite editar una imagen para un post
     *
     * Esta funcion es usada en conjunto
     * @method setImg
     *
     */
    function setImg(){
        $valoresDefault = ["html"=>"","id"=>"","descripcion"=>"","alt"=>"","img"=>"img-responsive","classCss"=>""];
        $this->layout="ajax.tpl.php";
        $this->dv->srcImagen=$this->post('img');
        $this->dv->align=$this->post('align');
        $this->dv->class=$this->post('classImg');
        #Debug::string($this->dv->class);
        #Debug::mostrarArray($this->post());
        if(empty($_POST['classCss'])) $_POST['classCss'] ='img-responsive';
        $this->dv->data = Helpers\Arrays::convertirAObjeto(array_merge($valoresDefault,$_POST));

    }
    function setMedia(){
        if($this->solicitudAjax() and $this->post('btnGestionObjetoMedia')){

            $form = Session::get('_formMedia');
            if($this->getEntero($this->get('objeto'))){
                $id=$this->get('objeto');
            }else{
                    $id=Session::get('objetoMedia');
            }
            $this->modelo->instanciar($id);

            if($form->validarFormulario()){

                if($this->modelo->salvar($_POST)->ejecutado()){
                    $this->respuestaJson(["result"=>true]);
                }else{
                    $this->respuestaJson(["result"=>false]);
                }


            }else{
                $this->respuestaJson(["result"=>false]);
            }
        }
    }

    function eliminarImagenes(){
        if($this->solicitudAjax() and $this->post('accion')=='dlt'){
            $imagenes = explode(",", $this->post('img'));
            $Media = new ObjetoMedia();
            $data = $Media->consulta()->in($imagenes,'id_objeto_media')->obt();
            foreach ($data as $key => $valores) {
                $imagens = json_decode($valores['meta_data']);
                foreach ($imagens as $key => $value) {
                    Archivo::eliminarArchivo(HTDOCS_DIR. 'img' .$valores['directorio'].$value);
                }

            }
            if($Media->eliminar($imagenes,'id_objeto_media')){
                $this->respuestaJson(['ejecutado'=>true]);
            }
                $this->respuestaJson(['ejecutado'=>false,'error'=>'No se han podido eliminar los objetos']);
        }else{
            $this->_404();
        }
    }

}
