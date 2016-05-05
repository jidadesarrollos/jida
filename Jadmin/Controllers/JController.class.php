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
		$this->definirJSGlobals();
		
		$this->dv->addJsModulo([
				'min/jd.plugs.js',
			]);   
		
		
		
		$this->dv->addJS([
			$this->obtURLApp()."htdocs/js/jida/jadmin.js",
		],false);		
    }
	
	/**
	 * Redefine el arreglo Global de Archivos JS
	 * 
	 * Evita que se sobrecarguen archivos JS ya cargados
	 * @method definirJSGlobals
	 * 
	 */
	private function definirJSGlobals(){
		if(strtolower($this->_modulo)=='jadmin'){
			$GLOBALS['_JS']=[
				'dev'=>[
					'/htdocs/bower_components/jquery/dist/jquery.js',
					'/htdocs/bower_components/jquery-ui/jquery-ui.min.js',
					'/htdocs/bower_components/bootstrap/dist/js/bootstrap.min.js',
					'/htdocs/bower_components/bootbox/bootbox.js',
				],
				
				'prod'=>[
				 		'https://code.jquery.com/jquery-2.0.3.min.js',
			        	'https://code.jquery.com/ui/1.10.3/jquery-ui.min.js',
			            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',
					],
				//'/htdocs/js/min/jd.plugs.js',
				
				
			
			];
			$this->dv->js=$GLOBALS['_JS'];
		}
			
		return $this;
	}
	
}
