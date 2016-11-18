<?php
/**
* Objeto Manager de Permisologia JidaFramework
 *
 *
* @author Julio Rodriguez
* @package Framework
* @version 2.0 2/9/2016
* @category
*/

namespace Jida\Core\Manager;
use Jida\Helpers as Helpers;
use Jida\Helpers\Debug as Debug;
use Jida\Core\Session as Session;
use Jida\Helpers\Arrays as Arrays;
class ACL{

	var $accesos;
	var $usoBD=TRUE;
	private $acl;
	private $perfiles;
	private $componentes;
	private $usuario;

	private $componenteObject;
	private $_componentes;
	private $estructura;
	private $accesoPerfiles;
	private $_acl=[];
    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($id=""){
    	
    	$this->componenteObject = $componenteObject = new \Jida\Modelos\Componente();
		$this->perfilObject 	= new \Jida\Modelos\Perfil();
		$this->usuario 			= Helpers\Sesion::get('Usuario');
		$modeloUser = MODELO_USUARIO;
		
		
		if(!is_a($this->usuario, MODELO_USUARIO)){
			
			$this->usuario = new $modeloUser();
			Helpers\Sesion::set('Usuario',$this->usuario);
		}
		if(empty($this->usuario->perfiles)) $this->usuario->agregarPerfilSesion('UsuarioPublico');
		if(!Helpers\Sesion::get('ACL')){
			$this->leerEstructura();

			$this->leerPerfiles();
		}else{
			$this->_acl = Helpers\Sesion::get('ACL');
		}




    }
	/**
	 * Verifica los perfiles del usuario actual y establece la estructua del ACL
	 * @method leerPerfiles
	 * @private
	 */
	private function leerPerfiles(){
		$this->accesoPerfiles = $this->perfilObject->obtAclPerfiles($this->usuario->perfiles);
		#Debug::mostrarArray($this->estructura,0);

		$componentesPerfil 	= array_filter(array_unique(Arrays::obtenerKey('id_componente',$this->accesoPerfiles)));
			foreach ($this->estructura as $key => $componente) {

					$this->_acl[$componente['componente']] = array('objetos'=>[]);
					$this->validarAccesoObjetos($componente);

			}
		if(!array_key_exists('principal', $this->_acl)) $this->_acl['principal']=['objetos'=>[]];


	}
	/**
	 * Verifica la permisologia sobre objetos y metodos de los perfiles actuales de un componente dado
	 *
	 * @method validarAccesoObjetos
	 * @private
	 * @param $estructuraComponente Estructura del componente actual
	 */
	private function validarAccesoObjetos($estructuraComponente){
		$objetosPerfil	= array_filter(array_unique(Arrays::obtenerKey('id_objeto',$this->accesoPerfiles)));
		$totalObjetos 	= count($objetosPerfil);

		$componente = $estructuraComponente['componente'];
		if($totalObjetos){

			foreach ($estructuraComponente['objetos'] as $key => $objeto) :
				if(in_array($objeto['id'], $objetosPerfil))
				{

					$this->_acl[$componente]['objetos'][$objeto['objeto']] = [
						'nombre' => $objeto['objeto'],
						'metodos'=>[]
					];

					$metodosObjeto =& $this->estructura[$componente]['objetos'][$objeto['objeto']]['metodos'];
					;
					if(count($metodosObjeto))
					{
						$metogosAcl =& $this->_acl[$componente]['objetos'][$objeto['objeto']]['metodos'];

						foreach ($metodosObjeto as $key => $dataMetodo) {

							if(in_array($dataMetodo['id'], $objetosPerfil) or $dataMetodo['login']==0)
								$metogosAcl[$dataMetodo['metodo']] = $dataMetodo['metodo'];
						}
					}//fin if count

				}
			endforeach;

		}

	}


	/**
	 * Lee la estructura de componentes,objetos y metodos registrada en base de datos
	 * @method leerEstructura
	 * @private
	 * @since 1.4
	 */
	private function leerEstructura(){

		$data = $this->componenteObject->obtComponentesData();
		$estructura=[];

		foreach ($data as $key => $info) {

			$componente =$info['componente'];
			if(!array_key_exists($componente, $estructura)){
				$estructura[$componente] =[
					'componente'	=>$info['componente'],
					'id'			=>$info['id_componente'],
					'descripcion'	=>$info['descripcion_componente'],
					'objetos'=>[]
				];
			}

			if(!array_key_exists($info['objeto'], $estructura[$componente]['objetos']))
			{
				$objeto = [
					'objeto'=>$info['objeto'],'id'=>$info['id_objeto'],'metodos'=>[],
					'descripcion'	=>$info['descripcion_objeto'],
				];
				$estructura[$componente]['objetos'][$info['objeto']] = $objeto;
			}
			$metodo = [
				'metodo'		=>$info['metodo'],
				'id'			=>$info['id_metodo'],
				'descripcion'	=>$info['descripcion_metodo'],
				'login'			=>$info['loggin']
				];
			$estructura[$componente]['objetos'][$info['objeto']]['metodos'][$info['metodo']]=$metodo;


		}
		$this->estructura = $estructura;

	}
	/**
	 * Verifica el acceso de la session actual al controlador, metodo o componente dado
	 *
	 * @method validarAcceso
	 * @private
	 * @param string $controlador Controlador a validar
	 * @param string $metodo Nombre del metodo a validar
	 * @param string $componente [opcional] Nombre del componente a validar
	 * @return boolean
	 */
	function validarAcceso($controlador,$metodo,$componente=""){
		if($this->usoBD===FALSE) return true;
		$componente = strtolower($componente);
		$perfiles 	= $this->usuario->perfiles;
		if(empty($componente)) $componente='principal';

		if(defined('DEBUG_ACL') and DEBUG_ACL==TRUE){
		  Debug::mostrarArray($this->_acl,0);
		}
		if(!is_array($this->_acl) or count($this->_acl)<1) return true;

		$acceso = FALSE;
		$i = 0;
		while ($acceso===FALSE and $i<count($perfiles)) {
			$perfilActual = $perfiles[$i];
			if(array_key_exists($componente, $this->_acl)){
				$dataComponente = $this->_acl[$componente];
				if(count($dataComponente['objetos'])>0)
				{
					$objetos = $dataComponente['objetos'];
					if(array_key_exists($controlador, $objetos)){
						$metodos = $objetos[$controlador]['metodos'];
						if(count($metodos)>0){
							if(array_key_exists($metodo, $metodos))
								$acceso = TRUE;
							else{
								Debug::String("no tiene acceso al metodo");
							}
						}else
							// Tiene acceso a todo el objeto
							$acceso = TRUE;
					}else{ Debug::String("no al objeto");
						//No tiene acceso al objeto
						$acceso = FALSE;}
				}else{
				// No hay objetos registrados, por tanto tiene acceso a todo el componente
				$acceso = TRUE;}
			}else{ Debug::string("no componente ".$componente);}
			++$i;
		}//fin while
		return $acceso;
	}// fin metodo validarAcceso
}//fin clase;
