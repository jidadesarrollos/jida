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

		$this->dv->title="JIDAPanel";
		$this->urlHtdocs=$this->obtURLApp()."htdocs/bower_components/";
        if(!$this->layout)
		  $this->layout="jadminIntro.tpl.php";

			
		$this->dv->addCssModulo('jida.css');   

		$this->dv->addJS([
			#$this->urlHtdocs."jquery/dist/jquery.js",
			#$this->urlHtdocs.'bootstrap/dist/js/bootstrap.min.js',
			#$this->obtURLApp()."htdocs/js/jida/min/jd.plugs.js",
			$this->obtURLApp()."htdocs/js/jida/jadmin.js",
			#$this->obtURLApp()."htdocs/js/jida/jidaPlugs.js",
			
		],false);		
    }
	
}
