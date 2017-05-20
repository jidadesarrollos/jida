<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
namespace Jida\Modelos;
use Jida\BD 		as BD;
class ObjetoMedia extends BD\DataModel{
    var $id_objeto_media;
    var $objeto_media;
    var $tipo_media;
    var $descripcion;
    var $leyenda;
    var $alt;
    var $meta_data;
	var $idioma;
    /**
     * Ubicación parcial de la imagen, distribución de carpetas dentro del directorio Publico,
     * la url del directorio absoluto debe ser omitida pues se usarán las constantes correspondientes.
     * @var $directorio
     */
    var $directorio;
	private $_data;
    /**
     * Define si un objeto media ha sido cargado de forma propia o si es un recurso externo
     * @var int $interno
     */
    var $interno;
	var $idioma;
	private $tamanios=[];
    protected $tablaBD="s_objetos_media";
    protected $pk="id_objeto_media";

	function __construct($id=""){
		parent::__construct($id);
		$this->configuracion();
		
	}
	private function configuracion(){
		 $this->tamanios=[
			'lg' => IMG_TAM_LG,
			'md' => IMG_TAM_MD,
			'sm' => IMG_TAM_SM,
			'xs' => IMG_TAM_XS,
		];
	}
	function data($prop=""){
		if(empty($this->_data)){
			$this->_data = json_decode($this->meta_data);
		}
		if(!empty($prop) and property_exists($this->_data, $prop)){
			return $this->_data->{$prop};
		}
		return $this->_data;
		
	}
	
    function directorioMedia(){
        return URL_HTDOCS.$this->directorio.$this->objeto_media;
    }

}
