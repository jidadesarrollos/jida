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
use Jida\Core 					as Core;
use Jida\Componentes\Traductor 	as Traductor;
use Jida\Helpers 				as Helpers;
use Jida\RenderHTML\Formulario 	as Formulario;
use Jida\Render 				as Render; 
class JController extends Core\Controller{

	protected $urlHtdocs;
	var $idioma = 'es';

	var $manejoParams=FALSE;
    function __construct(){

    	parent::__construct();
		$this->__url = JD('URL_COMPLETA');

		$this->dv->title="JIDAPanel";
		if(empty($this->idioma)){
			$this->idioma='es';
		}
		$this->tr = new Traductor($this->idioma,['path'=>'Framework/Traducciones/']);
		$this->dv->traductor = $this->tr;
		$this->urlHtdocs=$this->obtURLApp()."htdocs/bower_components/";
        $this->layout('jadmin');
        if($this->solicitudAjax()) $this->layout = 'ajax.tpl.php';
		$this->dv->addCss('jida.css');
		$this->definirJSGlobals();


		// $this->dv->addJS([
			// $this->obtURLApp()."htdocs/js/jida/jadmin.js",
		// ],false);
		
		$this->validarSesion();
    }

	protected function validarSesion(){

		if(	Helpers\Sesion::checkPerfilAcceso('JidaAdministrador') or
			Helpers\Sesion::checkPerfilAcceso('Administrador') or 
			Helpers\Sesion::checkPerfilAcceso('Sede'))
				return true;
		else
			$this->formularioInicioSesion();
				
//		Helpers\Debug::imprimir('Final',true);
	}

	protected function formularioInicioSesion(){

		//$form = new Formulario('Login',null,null,2);
		$form = new Render\Formulario('Login');
		$form 	->boton('principal')
				->attr([
					'value'	=>'Iniciar Sesi&oacute;n',
					'id'	=>'btnJadminLogin',
					'name'	=>'btnJadminLogin'
				]);
		if($this->post('btnJadminLogin')){
			
			$userClass = MODELO_USUARIO;
			$user = new $userClass();			
			if($user->validarLogin($this->post('nombre_usuario'),$this->post('clave_usuario')))
			{
				$perfiles = $user->getPerfiles();
				// Helpers\Debug::imprimir($user->perfiles,true);
				Helpers\Sesion::set('Usuario',$user);
				Helpers\Sesion::set('__msjInicioSesion',Helpers\Mensajes::crear('suceso','Bienvenido '.$user->nombre_usuario));
				return true;
			}else{

				 Formulario::msj('error','Usuario o clave invalidos');
			 }
		}

		$this->layout('jadminIntro');
		$this->dv->usarPlantilla('login');
		$this->tituloPagina = NOMBRE_APP;
		#$form->
		// $form->setParametrosFormulario([
			// 'action'		=> JD('URL'),
			// 'nombreSubmit'	=> 'btnJadminLogin',
			// 'valueBotonForm'=> 'Iniciar Sesi&oacute;n'
// 
		// ]);
		//echo $form->armarFormulario();
		$this->data('formLoggin',$form->armarFormulario());
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
