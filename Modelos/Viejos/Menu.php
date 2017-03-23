<?PHP
/**
 * Clase Menu
 *
 * @package default
 * @author
 */

namespace Jida\Modelos\Viejos;
use Jida\BD as BD;
use Jida\Helpers as Helpers;
use Exception;
class Menu extends BD\DataModel {

	/**
	 * Clave principal del menu
     * @var $id_menu
     * @access public
	 */
	var $id_menu;
	/**
     * Nombre del menu
     *
     * Descripción breve del menu
     * @var $menu
     * @access public
     *
     */
    var $nombre_menu;
	/**
	* @var varchar identificador
	*/
	var $identificador;
	/**
	 * Funcion constructora
	 */

	private $tablaOpcionesAcceso = 's_opciones_menu_perfiles';
    private $tablaOpciones = 's_opciones_menu';
    private $perfilesAcceso=[];
	protected $pk = "id_menu";
	protected $tablaBD = "s_menus";

	function __construct($id=""){
		if(!empty($id) and !is_numeric($id)){
			parent::__construct();

			$this->obtenerBy($id,'nombre_menu');

		}else{
			parent::__construct($id);
		}

	}
	function setPerfilesAccesoMenu($perfiles){
	    if(is_array($perfiles)){
	        $this->perfilesAcceso=array_merge($this->perfilesAcceso,$perfiles);
	    }else{
	        $this->perfilesAcceso[]= $perfiles;

	    }
	}

	/**
     * Perimite agregar perfiles de acceso a la busqueda de opciones
     *
     * @method getPerfilesAcceso
     */
	function getPerfilesAcceso(){

	     $perfiles = Helpers\Sesion::get('Usuario')->perfiles();
         return array_merge($perfiles,$this->perfilesAcceso);

	}
	/**
     * Obtiene un menu desde la base de datos
     */
	private function obtenerMenu(){
	    $query = "select * from s_menus where $this->clavePrimaria = $this->id_menu";

        $result = $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($query));
        if(count($result)>0){
            $this->establecerAtributos($result, __CLASS__);
        }

	}//final funcion


	function procesarMenu($post){
		if(!$this->obtenerMenuByNombre($post['nombre_menu'])){
			$guardado  =$this->salvarObjeto(__CLASS__,$post);
            return $guardado;
		}else{
			$msj = "Ya existe un menu \"".$post['nombre_menu']."\" registrado";
            return $msj;
        }
	}


    private function obtenerMenuByNombre($nombre){
        $query = "select * from s_menus where nombre_menu='$nombre'";

        $result = $this->bd->ejecutarQuery($query);

        if($this->bd->totalRegistros > 0){
            $data = $this->bd->obtenerArrayAsociativo($result);
            $this->establecerAtributos($data,__CLASS__);
            return true;
        }else{
            return false;
        }

    }

    function eliminarMenu($menu=""){

        if(!empty($menu)){
            if(is_array($menu)){
                $this->eliminarMultiplesDatos($menu, 'id_menu');
            }else{
                $this->id_menu = $menu;
                $this->obtenerMenu();
                $this->eliminarObjeto();
                return true;
            }
        }
        else{
            throw new Exception("Debe seleccionar un menú a eliminar", 1);

        }
    }

     function obtenerOpcionesMenu($menu = ""){

         if(!empty($menu)){
            $this->obtenerMenuByNombre($menu);
         }else{
         	#throw new Exception("Menu no definido", 1);
         }
         #Helpers\Debug::mostrarArray(Helpers\Helpers\Sesion::get('usuario','perfiles'),false);


         $perfilesUser = "'".implode("','", $this->getPerfilesAcceso())."'";
         if(!empty($this->id_menu)){
         	$query  = "
	                    select distinct a.id_opcion_menu,id_menu,url_opcion,nombre_opcion,padre,hijo,id_estatus,icono,orden,
	                    selector_icono, id_metodo
	                    from
	                    $this->tablaOpciones a
	                    join $this->tablaOpcionesAcceso b on (a.id_opcion_menu = b.id_opcion_menu)
	                    join s_perfiles c on(b.id_perfil=c.id_perfil)
	                    where id_menu = $this->id_menu
	                    and c.clave_perfil in($perfilesUser)
	                    and  (id_estatus=1 or id_estatus=null)
	                    order by padre,orden,nombre_opcion";

	         $data = $this->bd->obtenerDataCompleta($query);
	         return $data;
         }else{
         	return[];
         }

     }



} // END

?>