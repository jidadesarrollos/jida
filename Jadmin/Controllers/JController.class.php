<?php
/**
* Clase Controladora
* @author Julio Rodriguez
* @package
* @version
* @category Controller
*/

class JController extends Controller{
	
	protected $urlHtdocs;
    function __construct(){
    	parent::__construct();
		
		$this->urlHtdocs=$this->obtURLApp()."htdocs/bower_components/";
		$this->layout="jadminIntro.tpl.php";
		$this->dv->addCSS($this->urlHtdocs.'bootstrap/dist/css/bootstrap.min.css',false);
		$this->dv->addJS([
			$this->urlHtdocs.="jquery/dist/jquery.js",
			$this->urlHtdocs.'bootstrap/dist/js/bootstrap.min.js',
			$this->obtURLApp()."htdocs/jida/jadmin.js"
			
		],false);
    }
	
}
