<?php
/**
* Clase Controladora
* @author Julio Rodriguez
* @package
* @version
* @category Controller
*/

namespace Jida\Jadmin\Controllers;
use Jida\Componentes;
use Jida\Core as Core;
use Jida\Componentes\Traductor as Traductor;
class JController extends Core\Controller{

	protected $urlHtdocs;
	var $idioma = 'es';

	var $manejoParams=FALSE;
    function __construct(){
    	parent::__construct();

		$this->dv->title="JIDAPanel";
		if(empty($this->idioma)){
			$this->idioma='es';
		}
		$this->tr = new Traductor($this->idioma,['path'=>'Framework/Traducciones/']);
		$this->dv->traductor = $this->tr;
		$this->urlHtdocs=$this->obtURLApp()."htdocs/bower_components/";
        $this->layout('inicio');


		$this->dv->addCss('jida.css');
		$this->definirJSGlobals();

		// $this->dv->addJsModulo([
				// 'min/jd.plugs.js',
		// ]);



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
			if(!array_key_exists('jadmin', $GLOBALS['_JS'])){


				$GLOBALS['_JS']=[
					'dev'=>[
						'/htdocs/bower_components/jquery/dist/jquery.js',
						'/htdocs/bower_components/jquery-ui/jquery-ui.min.js',
						'/htdocs/bower_components/bootstrap/dist/js/bootstrap.min.js',
						'/htdocs/bower_components/bootbox.js/bootbox.js',
					],

					'prod'=>[
					 		'https://code.jquery.com/jquery-2.0.3.min.js',
				        	'https://code.jquery.com/ui/1.10.3/jquery-ui.min.js',
				            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',
						],
					'/htdocs/js/jida/min/jd.plugs.js',



				];

			$this->dv->js=$GLOBALS['_JS'];
			}
		}

		return $this;
	}

}
