<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
include_once 'Helpers/funcionesBasicas.php';
include_once 'Helpers/Directorios.class.php';
class Init{

	private $pathOriginal="init/";
	private $dataServer;
	private $pathUrl;
    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($id=""){
    	$this->dataServer=$_SERVER;
		if(!array_key_exists('REQUEST_URI', $this->dataServer))
			throw new Exception("No se encuentra definida la URL", 1);

    }


	function inicializarJida(){

		if($this->crearArchivosRequeridos()){
			$this->copiarHtdocs();

			redireccionar($this->obtenerUrlPath().'jadmin/init/');
		}
	}

	private function copiarHtdocs(){
		Directorios::crear('htdocs');
		Directorios::copiar("htdocs/js/","../htdocs/js/jida/");
		Directorios::copiar("htdocs/css/", "../htdocs/css/jida/");
	}

	private function crearArchivosRequeridos(){
		Directorios::crear($this->obtDirectoriosRequeridos());

		if(!copy($this->pathOriginal.'index-home.php', '../index.php')){
			return false;
		}
		if(!copy($this->pathOriginal.'htaccess-example','../.htaccess'))
			return false;

		return true;
	}

	private function obtenerUrlPath(){

		return str_replace('/Framework', '', $this->dataServer['REQUEST_URI']);
	}

	private function obtDirectoriosRequeridos(){
		return $directorios=[
			'../Aplicacion',
			'../Aplicacion/Config',
			'../Aplicacion/Modelos',
			'../Aplicacion/Controller',
			'../Aplicacion/Layout',
			'../Aplicacion/Vistas'
		];

	}
}
