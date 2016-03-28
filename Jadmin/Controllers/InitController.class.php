<?php
/**
* Clase Controladora
* @author Julio Rodriguez
* @package
* @version
* @category Controller
*/
require_once 'Framework/Core/GeneradorCodigo/GeneradorCodigo.trait.php';
class InitController extends JController{
	use GeneradorCodigo;
	private $GeneradorModelo;
	private $gController;
	function __construct(){
		parent::__construct();
		$this->GeneradorModelo=new GeneradorModelo();
		$this->gController= new GeneradorController();
		/**
		 * Esta forma de insertar los archivos debe ser mejorada
		 */
		$this->dv->addCSS([
			$this->urlHtdocs.'bootstrap/dist/css/bootstrap.min.css',
			$this->urlHtdocs."font-awesome/css/font-awesome.min.css",
			$this->obtURLApp()."htdocs/css/jida/jida.css",
			]
			,false);
		$this->dv->addJS([
			$this->urlHtdocs."jquery/dist/jquery.js",
			$this->urlHtdocs.'bootstrap/dist/js/bootstrap.min.js',
			$this->obtURLApp()."htdocs/js/jida/min/jd.plugs.js",
			$this->obtURLApp()."htdocs/js/jida/jadmin.js",
			
		],false);
		
	}
	
	function index(){
		$this->vista="init";
		if($this->post('btnBdConfig')){
			if(!Session::get('dirApp')) $this->crearDirApp();
			if(!$this->validarDatosBD()){
				
				Formulario::msj('error', 'Faltan algunos datos, por favor valida y vuelve a intentarlo');
			}else{
				if($this->configurarBD()){
					$this->crearControllerApp();
					$this->agregarLayout();
					$this->copiarHtdocs()->appConfig()->initConfig();
					$this->crearUsuarioJadmin();
					$this->redireccionar($this->getUrl('modelos'));
				}else{
					
					Formulario::msj('error','No se ha podido realizar la conexion a base de datos, verifica los datos y vuelve a intentarlo');
				}
				
				//Debug::string("final funcion",true);
			}
		}
	}
	/**
	 * Gestiona toda la configuración para la base de datos
	 * 
	 * Crea el archivo de configuracion de bd y la estructura de aplicacion
	 * @method configurarBD
	 * 
	 */
	private function configurarBD(){
		//$bdConfig = file("Framework/Settings/BDConfig.jida");
		$arrayConfig=
		[
			'puerto'	=>'3306',
			'usuario'	=> 	$this->post('usuario_bd'),
			'clave'		=>	$this->post('clave_bd'),
			'servidor'	=>	$this->post('servidor'),
			'bd'		=>	$this->post('bd')
		];
		if($this->probarConexion($arrayConfig)){
			
			$bdConfig="";
			#Debug::mostrarArray($bdConfig);
			if(!Directorios::validar(DIR_APP)) Directorios::crear(DIR_APP);
			Directorios::crear(DIR_APP."/Config");
			$bdConfig.=$this->abrirPHP().$this->docBlock(
				"Archivo de Configuración de Base de Datos"
				);
			$bdConfig.=$this->constante('MANEJADOR_BD', 'MySQL', 'string', 'Manejador de Base de datos utilizado en el sistema');
			$bdConfig.=$this->constante('manejadorBD', 'MySQL', 'string', 'Manejador de Base de datos utilizado en el sistema');
			
			$bdConfig.=$this->saltodeLinea();
			$bdConfig.=$this->docBlock('Arreglo de conexiones',null,['var'=>['type'=>'array','name'=>'$GLOBALS[\'conexiones\']']]);
			
			
			$bdConfig.=$this->definirArray('$GLOBALS[\'conexiones\']',['default'=>$arrayConfig]);
			$this 	->crear(DIR_APP."Config/BDConfig.php")
					->escribir($bdConfig)
					->cerrar();
			return true;
			
		}else{
			
			return false;
		}
					
		
	}

	/**
	 * Definicion archivo appConfig
	 */
	private function appConfig(){
		
		$devCss = [
			$this->urlHtdocs.'bootstrap/dist/css/bootstrap.min.css',
			$this->urlHtdocs."font-awesome/css/font-awesome.min.css",
			$this->obtURLApp()."htdocs/css/jida/jida.css",
			];
			
		$devJS =[
			$this->urlHtdocs."jquery/dist/jquery.js",
			$this->urlHtdocs.'bootstrap/dist/js/bootstrap.min.js',
			$this->obtURLApp()."htdocs/js/jida/min/jd.plugs.js",
			$this->obtURLApp()."htdocs/js/jida/jadmin.js",
			
		];

		$appConfig = 
			  $this->abrirPHP()
			. $this->docBlock('Archivo de Configuracion de la Aplicación',
							'El app config es creado para definir variables y constantes de configuracion que
* puedan ser utilizadas en cualquier ambiente de la aplicacion (De desarrollo o produccion) ')
			. $this->saltodeLinea()
			. $this->docBlock(
				'Archivos CSS Requeridos', 'Los archivos definidos en el primer nivel del arreglo serán incluidos
* siempre sin importar el ambiente de la aplicacion. Si se desea especificar archivos solo para un ambiente,
* se debe definir una clave con el nombre del ambiente.
			')
			.$this->definirArray('$GLOBALS[\'_CSS\']',['dev'=>$devCss]).$this->saltodeLinea()
			.$this->docBlock(
					'Archivos JS Requeridos', 
					'Los archivos definidos en el primer nivel del arreglo serán incluidos siempre sin importar el ambiente de 
* la aplicacion. Si se desea especificar archivos solo para un ambiente, se debe definir una clave con el nombre del ambiente.
			')
			.$this->definirArray('$GLOBALS[\'_JS\']',['dev'=>$devJS]);
			$this 	->crear(DIR_APP."Config/appConfig.php")
					->escribir($appConfig)
					->cerrar();
			return $this;
	}
	private function initConfig(){
		$initConfig = 
			$this->abrirPHP() .
			$this->docBlock(
				'Archivo Inicial de configuracion de la Aplicacion',
				'El InitConfig es usado para definir todas aquellas variables globales o constantes que 
 * solo sean utilizadas en un ambiente especifico (como desearrollo, calidad o producción), esto facilita
 * agrupar en un solo archivo todo lo que no desea ser pasado de un ambiente a otro.
				'
				) 
			. $this->constante('APP_MANTENIMIENTO', FALSE, 'boolean', 'Define si la aplicacion se encuentra en mantenimiento')
			. $this->constante('URL_APP', '/', 'url', 'Direccion URL de la App')
			. $this->constante('ENTORNO_APP', 'dev', 'string', 'Define el entorno de la aplicacion')
			
			;
		$initConfig .= $this->saltodeLinea() 
			. $this->definirArray('$GLOBALS[\'modulos\']',['Jadmin']);
		$this 	->crear(DIR_APP."Config/initConfig.php")
					->escribir($initConfig)
					->cerrar();	
		return $this;
	}
	private function validarDatosBD(){
		$bandera=FALSE;
		$validaciones = ['obligatorio'];
		
		if(	!empty($this->post('servidor')) and !empty($this->post('usuario_bd')) and
			!empty($this->post('clave_bd')) and !empty($this->post('bd'))
			// Validador::validar($validaciones, $this->post('servidor')) and
			// Validador::validar($validaciones, $this->post('usuario_bd')) and
			// Validador::validar($validaciones,$this->post('servidor')) and
			// Validador::validar($validaciones,$this->post('clave_bd'))
			){
				
				
				$bandera=TRUE;
			}
			
		return $bandera;
	}
	/**
	 * @method modelos;
	 */
    function modelos(){
     	
		$tablas = $this->GeneradorModelo->obtenerTablas();
        //Debug::mostrarArray($BDManager->obtenerTablas());
        if($this->post('btnCrearModelos')){
            if(count($this->post('tablas_bd')>0)){
                if($this->crearModelos()){
                	Vista::msj('componentes','suceso', 'Se han creado los objetos correctamente');
                	$this->redireccionar($this->obtURLApp()."jadmin/componentes/");
//                	
                }
				
            }else{
                Session::set('__msj',Mensajes::crear('error', 'Debes Seleccionar alguna tabla'));
            }
                        
        }    
        
        
        
        $this->dv->tablas=$tablas;
    }
	/**
	 * Crea los modelos de la aplicacion a partir de la estructura de base de datos.
	 * 
	 * @method crearModelos
	 * @return void
	 */
    private function crearModelos(){
        $objetos = $this->post('tablas_bd');
        $prefijos = explode(",",$this->post('txtPrefijos'));
        
        array_walk($prefijos,function(&$valor,$clave){
          $valor ="/^".$valor."/";
        });
    
        foreach ($objetos as $key => $objeto) {
            $this->GeneradorModelo->generar($objeto,$prefijos);   
        }
		
		return true;
    }
	private function crearDirApp(){
		$directorios=[
			'Aplicacion',
			'Aplicacion/Config',
			'Aplicacion/Modelos',
			'Aplicacion/Controller',
			'Aplicacion/Layout',
			'Aplicacion/Vistas'
		];
		Directorios::crear($directorios);
		Session::set('dirApp', TRUE);
	}
	
	
	private function probarConexion($configuracion){
		$GLOBALS['conexiones']['default'] = $configuracion;
		$manejador="MySQL";
		try{
			switch ($manejador) {
				case 'MySQL':
						$bd = new Mysql();
					$GLOBALS['conexiones']['default']['puerto']=3306;
					break;
				
				case 'PSQL':
					
					break;
			}
			if($bd->establecerConexion()){
				return true;	
			}
		}catch(Exception $e){
			
			return false;
		}
			
	}
	
	
	private function crearControllerApp(){
		$this->gController->documentacion(
		"Controlador Principal de la Aplicacion",null,
		['className'=>'AppController','category'=>'controller','package'=>'Aplicacion']);
		
		$this->gController->agregarExtend('Controller')->crearController('App');
		$this->gController
		->agregarExtend('AppController')
		->metodoIndex(function(){
			$content = $this->tab(1);
			$content.= ' $this->layout="default.tpl.php";';
			return $content;
		})
		->crearController('Index');
		
		$this->crearVista(
				'index', 'index', 
				Selector::crear("a",
						['href'=>$this->urlController(),'class'=>"text-center"],
			 			'Configurar Aplicaci&oacute;n'
				)
		 );
		
				
	}
	
	
	protected function crearVista($controller,$metodo,$contenido=""){
		$modulo  =explode(".", $controller);
		$ubicacion = DIR_APP;
		
		if(count($modulo)>1){
		
			$ubicacion.=Cadenas::upperCamelCase(Cadenas::upperCamelCase($modulo[0]))."/Vistas/".Cadenas::lowerCamelCase($modulo[1])."/";
		}else{
		
			$ubicacion.="Vistas/".Cadenas::lowerCamelCase($controller)."/";
		}
		
		
		if(!Directorios::validar($ubicacion)) Directorios::crear($ubicacion);
		
		$view = 
			$this->abrirPHP()
			. $this->docBlock("Archivo vista para $metodo",null,
				['category'=>'view','package'=>'Aplicacion'])
			.$this->cerrarPHP()
			. $contenido;
		$this 	->crear($ubicacion."/".$metodo.".php")
				->escribir($view)->cerrar();
	}

	private function agregarLayout(){
		if(!Directorios::validar(DIR_APP."Layout")) Directorios::crear(DIR_APP."Layout");
		copy(DIR_FRAMEWORK."Layout/jadminIntro.tpl.php",DIR_APP."Layout/default.tpl.php");
		return $this;
	}
	private function copiarHtdocs(){
		Directorios::copiar(DIR_FRAMEWORK."htdocs/js/", HTDOCS_DIR."js/jida/");
		Directorios::copiar(DIR_FRAMEWORK."htdocs/css/", HTDOCS_DIR."css/jida/");
		return $this;
	}
	private function crearUsuarioJadmin(){
		$user = new User();
		
		$user->initBD('MySQL');
		
		$data = [
			'id_estatus'=>1,
			'validacion'=>1,
			'nombre_usuario'=>$this->post('nombre_usuario'),
			'clave_usuario'=>md5($this->post('clave_usuario'))
		];
		$user->registrarUsuario($data,[1],FALSE);
		$user->agregarPerfilSesion('JidaAdministrador');
		
		Session::sessionLogin();
		$user->registrarSesion();
		Session::set('Usuario',$user);
		return $this;
	}
	
}
