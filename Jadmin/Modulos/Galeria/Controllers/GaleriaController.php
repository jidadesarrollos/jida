<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
namespace Jida\Jadmin\Modulos\Galeria\Controllers;
if(!defined('IMG_TAM_LG')) define('IMG_TAM_LG','1200');
if(!defined('IMG_TAM_LG')) define('IMG_TAM_MD','800');
if(!defined('IMG_TAM_LG')) define('IMG_TAM_SM','400');
if(!defined('IMG_TAM_LG')) define('IMG_TAM_XS','140');


use Jida\Modelos 		as Modelos;
use Jida\Helpers 		as Helpers;
use Jida\Render 		as Render;
use Jida\Jadmin\Controllers\JController as JController;

class GaleriaController extends JController{
	
    function __construct(){
        parent::__construct();
        $this->modelo = new Modelos\ObjetoMedia();
		$this->dv->addJs([
		'/Framework/htdocs/js/libs/jArchivos.js'
		],FALSE);
		$this->dv->addJsModulo('galeria.js');
		if($this->solicitudAjax()) $this->layout('ajax');
    }
	/**
	 * Testing de carga
	 */
	function form(){
		
		$this->dv->addJs(
		['/Framework/htdocs/js/libs/jArchivos.js',
		'/Framework/htdocs/js/jadmin/cargaArchivos.js'
		],false);
		
	}
	
	function cargaForm(){
		
		Helpers\Debug::imprimir($_POST,$_FILES,true);
	}
    function index(){
    	$this->vista="galeria";
		
        $this->dv->seleccionMultiple=TRUE;
        if($this->post('funcion')=='portada') $this->dv->seleccionMultiple=FALSE;
        //$this->dv->addJs(['adm/galeria.js']);
        //$this->dv->imagenes= $this->modelo->select()->obt();
		$this->data([
		'objetosGaleria'=>$this->modelo->select()->obt()
		]);

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
	
	function imagenAjax(){
		
		$respuesta = ['error'=>TRUE];
		
		if ($_FILES['archivoGaleria']) {
				
			$anio = date('Y');
        	$mes = date('m');
			$archivo = new Helpers\Imagen('archivoGaleria');
			$pathWeb = "/media/$anio/$mes/";
			
			if($archivo->validarCarga()){
					
				if($archivo->validarCargaImagen()){
					
					$path = HTDOCS_DIR . 'img/media/' .$anio .'/' . $mes ;
					
					if(!Helpers\Directorios::validar($path)) Helpers\Directorios::crear($path);
					
					if($archivo->moverArchivosCargados($path,TRUE)){
						
						$data = $this->_copiarImagenes($archivo,$pathWeb);
						if($data){
							
							$respuesta['error']=FALSE;
							$respuesta['data'] = $data;
							$this->modelo->salvarTodo($data);
							
						}
						
					}
				}else $respuesta['msj']='Formatos de Imagen no válidos';
					
				
			}else $respuesta['msj']= 'No se pudo realizar la carga, por favor vuelva a intentarlo';
		}
		$this->respuestaJson($respuesta);
		
	}
	private function _copiarImagenes($img,$path){
		$imgs = $img->getArchivosCargados();
		$dataMedia = [];
		
        foreach ($imgs as $key => $file) {
            $dataMedia[]=[
            'tipo_media'=>1,
            'objeto_media'=>$file['nombre'].".".$file['extension'],
            'interno'=>1,
            'directorio'=>$path,
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
       return $dataMedia;
	}

	protected function _obtFormMedia($id){
			
		// \Jida\Helpers\Debug::imprimir('_obtFormMedia',$_GET,$id);
		
		$form = new Render\Formulario('GestionObjetoMedia',$id);
		Helpers\Sesion::set('objetoMedia',$id);
		$form
		->boton('principal')
		->attr([
			'value'=> 'Guardar',
			'type' => 'button'
		])->data([
			'accion'=>'guardarObjeto',
			'id'=>$id,
			'config'=>'{"post":"guardarMedia"}'
		]);
		Helpers\Sesion::set('_formMedia',$form);
		return $form;
		
	}
	function gestionMedia(){
		
		if($this->solicitudAjax()){
			
			$this->layout('ajax');
			$id = $this->get('id');
			$this->modelo->obtenerBy($id,'id_objeto_media');
			
			$this->data([
				'form'=>$this->_obtFormMedia($id)->render(),
				'obj' => $this->modelo
			]);
				
		}
	}

	function editarMedia(){
			
		if($this->post('btnGestionObjetoMedia')){
			
			if($this->getEntero($this->get('objeto'))){
                $id=$this->get('objeto');
            }else{
                $id=Helpers\Sesion::get('objetoMedia');
            }
			
			if(Helpers\Sesion::get('_formMedia') instanceof Render\Formulario){
				$form = Helpers\Sesion::get('_formMedia');
			}else{
				$form = $this->_obtFormMedia($id);
			}
			
			$result = [
				'msj'=>'No se pudo guardar el objeto',
				'ejecutado'=>FALSE,
			];
            $this->modelo->instanciar($id);

            if($form->validar()){

                if($this->modelo->salvar($_POST)->ejecutado()){
                    $result = [
                    	'msj' => 'El objeto se ha guardado exitosamente',
                    	'ejecutado' =>true
                    ];
                }
				

            }
         
		    $this->respuestaJson($result);
            
        
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
