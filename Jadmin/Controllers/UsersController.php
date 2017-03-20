<?PHP 

namespace Jida\Jadmin\Controllers;

use Jida\Helpers as Helpers;
use Jida\Render as Render;
use Jida\Modelos as Modelos;
use Jida\Core\UsuarioManager as UsuarioManager;

class UsersController extends JController{
    
    use UsuarioManager;
    
    protected $urlCierreSession="/jadmin/";
    
    var $layout = 'jadmin.tpl.php';
    
	var $manejoParams=TRUE;
    
	function __construct(){

        parent::__construct();

        if(defined('MODELO_USUARIO') and class_exists(MODELO_USUARIO)){
            $clase = MODELO_USUARIO;
            $this->modelo = new $clase();
        }
        
        $this->url='/jadmin/users/';
    }
    
	function index(){

		$vista = $this->vistaUser();
        $this->vista="vistaUsuarios";
		$this->dv->vista = $vista->obtenerVista();
			
	}
    
    function setUsuario($idUser=''){
        $this->_setUsuario($idUser);
    }
    
    function asociarPerfiles($idUser=''){
        $this->_asociarPerfiles($idUser);
    }
    
    function cierresesion($url=''){
        $this->_cierresesion($url);
    }
    
    function eliminarUsuario($idUser=''){
        
        if($this->_eliminarUsuario($idUser)){
            $tipo='suceso';$msj='Usuario eliminado exitosamente';
        }else{
            $tipo='error';$msj='El usuario no ha podido ser eliminado, por favor intente de nuevo';
        }
        
        Render\JVista::msj('usuarios','suceso','Usuario eliminado exitosamente',$this->obtUrl('index'));
    }
}
