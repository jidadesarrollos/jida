<?PHP 

/**
 * Controlador de Funcionamiento del JIDA
 * 
 * Clase Modelo para manejar los formularios del framework.
 *
 * @package FRAMEWORK
 * @subpackage JIDA
 * @category JIDA
 * @author  Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * Fecha : 23/10/2013
 */

class JidaControl extends DBContainer{
    
    var $id_form;
    var $nombre_f;
    var $query_f;
	var $nombre_identificador;
    var $clave_primaria_f;
	var $estructura;
    private $tablaCampos = "s_campos_f";
	
	
	/**
     * Funcion constructora del modelo JidaControl
     * 
     * @method __construct
     * @param int $id_form Id del formulario a modificar, no obligatorio
     * @param int $tabla Tabla de Formularios a manejar 1. Formularios Aplicacion 2. Formularios Framework
     */
    function __construct($id_form="",$tabla=1){
        
        if($tabla==2){
            $this->nombreTabla="s_jida_formularios";
            $this->tablaCampos="s_jida_campos_f";
            
        }else{
            $this->nombreTabla="s_formularios";    
        }
        
        
        $this->clavePrimaria="id_form";
        $this->unico=array('nombre_identificador');
        
        parent::__construct(__CLASS__,$id_form);
		
    }
    
	/**
	 * Obtiene los datos de un formulario
	 * @access private
	 */
	private function obtenerDatosFormulario($idForm = ""){
		if($idForm!=""){
			$this->id_form=$idForm;
		}
        $query = "select * from $this->nombreTabla where $this->clavePrimaria=$this->id_form";
        $formulario = $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($query));
        
		$this->establecerAtributos($formulario, __CLASS__);        
		return $formulario;
    }
 
    /**
     * Registra Campos Nuevos del Formulario
     *  @method validarCamposFormulario
     */
     private function validarCamposFormulario(){
         $query=$this->query_f." limit 1 offset 1";
		 
         $result = $this->bd->ejecutarQuery($query);
         $totalColumnas = $this->bd->totalField($result);
         $formulario = new Formulario(2);
         $camposActuales = array();
         foreach ($formulario->camposFormulario as $key => $value) {
             $camposActuales[$value['name']]=$value['name'];
         }//fin foreach
        
         
         
     }//fin funcion
     
    /**
	 * Verifica los campos de un formulario y los actualiza
	 * 
	 * Si el formulario es nuevo realiza una inserción inicial de los campos, si ya existe valida
	 * si hay campos nuevos para agregarlos o si se debe eliminar alguno
	 * @method procesarCamposFormulario
	 * @access private
	 * @param array Accion Arreglo resultado de gestion de formulario (result dbContainer)
	 */
    function procesarCamposFormulario($accion){
		$resultDatos = $this->bd->ejecutarQuery($this->query_f);
        
        
		$totalColumnas = $this->bd->totalField($this->bd->result);
        
        if($accion['accion']=='Insertado'){
            
            $campos = array();
            for ($i=0; $i < $totalColumnas; $i++) {
                $nombreCampo = $this->bd->obtenerNombreCampo($resultDatos, $i); 
                $campos[] = "(".$accion['idResultado'].",'$nombreCampo','$nombreCampo',2)";
            }//fin for
            $insertCampos = sprintf("insert into $this->tablaCampos (id_form,name,id_propiedad,control) values %s",
                                    implode(", ", $campos));
            $this->bd->ejecutarQuery($insertCampos);  
        }
        elseif($accion['accion']=='Modificado'){
            
            $camposActuales = array();
            
			for($i=0;$i<$totalColumnas;$i++){
			    
				$nombreCampo =  $this->bd->obtenerNombreCampo($resultDatos, $i);
                
				$query = "select * from $this->tablaCampos where id_form=$this->id_form and name='$nombreCampo'";
                
        		$this->bd->ejecutarQuery($query);
				$total=	$this->bd->totalRegistros;
				
				if($total == 0){
					$campo = new CampoHTML(2,null);
                    $campo->procesarCampo(array("name"=>$nombreCampo,"id_propiedad"=>$nombreCampo,"id_form"=>$this->id_form));
				}
				
				$camposActuales[]="'$nombreCampo'";
                
			}//final for
			/**
             * Se eliminan los campos borrados del formulario
             */
			$queryCheck = sprintf("delete from $this->tablaCampos where id_form=%d and name not in(%s)",
								$this->id_form,
								implode(",", $camposActuales)
								);
            
			$this->bd->ejecutarQuery($queryCheck);

        }//fin else

    }//fin funcion
    
    /**
	 * Crea una lista con los campos de un formlario creado, asociando un evento javascript
	 * @method vistaCamposFormulario
	 */
    function getCamposFormulario(){
        
        $query = "select id_campo,name from $this->tablaCampos where id_form=$this->id_form order by orden asc";
        $data = $this->bd->obtenerDataCompleta($query);
        return $data;

    }//fin funcion
    /**
     * Valida y procesa el formulario de campos
     * @method procesarcampos
     * @param array $post Data post a guardar
     * @param int $form 2 Formulario Framework 1 Aplicacion
     */
    function procesarCampos($post,$form){
        
        $claseCampo = new CampoHTML($form,$post['id_campo']);
        $guardado = $claseCampo->procesarCampo($_POST);
        if($guardado['ejecutado']===TRUE){
            return true;    
        }else{   
            return FALSE;
            
        }    
    }//fin funcion
    #===================================================
    
    /**
	 * Devuelve string con HTML para formulario de registro de formularios
	 * 
	 * Valida el tipo de formulario a crear y hace uso del metodo crearFormularioRegistroForms-
	 * 
	 * @param array $post Post capturado.
	 * @return string $formulario string con codigo HTML;
	 */
    function obtenerFormularioRegistro($post){
        
        $seleccion = (isset($post['seleccionar']) and !empty($post['seleccionar']))?$post['seleccionar']:"";
        $seleccion = (is_array($seleccion))?$seleccion[0]:$seleccion;
        $tipoForm = 0;
        if(isset($post['btnNuevo'])) $tipoForm=1;
        if(isset($post['btnModificar'])) $tipoForm=2;
		
		if(isset($data['id_form']) and $tipoForm!=1){
				$totalCampos = $this->obtenerTotalCamposFormulario($data['id_form']);
			}else{
				$totalCampos=0;				
			}
        if($tipoForm>0){
            $formulario = $this->crearFormularioRegistroForms($tipoForm,$seleccion);    
        }else{
            $formulario ="No tiene acceso";
        }
        return $formulario;
        
        
    }//fin funcion
    
    
    /**
	 * Devuelve el numero de campos de un formulario creado
	 * @method obtenerTotalCamposFormularios
	 * @param int $id Id del Formulario
	 * @return int $result Total de campos del formulario
	 */
    function obtenerTotalCamposFormulario($id){
	    
		$query = "select count(*) from $this->tablaCampos where id_form=$id";
        
		$result = $this->bd->obtenerArray($this->bd->ejecutarQuery($query));
		return $result[0];
		
	}	
  
    /**
     * Genera las tablas para formularios del framework
     * 
     * Verifica si el tipo de base de datos es soportado y
     * crea las tablas s_formularios y s_campos_f para el manejo de
     * formularios, en caso de q la base de datos no sea soportada
     * devuelve una excepción
     */
    
    function crearTablasBD(){
        try{
           $esquema="";
           if(manejadorBD == 'MySQL' or manejadorBD == 'PSQL'){        
               include_once 'BD/tablasBasicas.' . strtolower(manejadorBD) . ".php";
           }
           else{
               throw new Exception("Base de datos no soportada :  " . manejadorBD ." ");
           }
           if(isset($queryBD)){
                if($this->bd->ejecutarQuery($queryBD,2)){
                    return true;
                }else{
                    return false;
                }
           }else{
               throw new Exception("Error : Consulta a base de datos no definida");
           } 
        }catch(Exception $e){
            controlExcepcion($e->getMessage());
        }//fin catch
    } //fin funcion crearTablasBD   
     
    /**
     * Verifica la conexión a base de datos
     * 
     */
    function testBD(){
       if($this->bd->establecerConexion()){
           return true;
       } else{
           return false;
       }
    }
    
    function obtenerTablasBD(){
        return $this->bd->obtenerTablasBD();       
    }
    
    /**
     * Permite consultar la base de datos con uno o varios querys
     * 
     * @param string $query La o las consultas a base de datos
     * @return object $result Resultado de la(s) consultas realizadas
     */
    function consultarBD($query){
        
        $result = $this->bd->ejecutarQuery($query,2);
        // while($data = $this->bd->obtenerArrayAsociativo($result)){
            // Arrays::verArray($data);
        // }
        $data = $this->bd->obtenerDataMultiQuery($result);
        
        return $data;
    }
    /**
     * Elimina uno o varios formularios
     * @method eliminarFormularios
     * @param $id Numero o arreglo de numeros identificadores de formularios
     * 
     */
    function eliminarFormulario($ids){
        
        if(is_array($ids)){
            
            //verificar campos
            $delete = sprintf("delete from %s where id_form in(%s)",$this->tablaCampos,implode(",",$ids));
            
            if($this->bd->ejecutarQuery($delete)){
                $this->eliminarMultiplesDatos($ids, 'id_form');        
            }else{
             throw new Exception("No se pueden eliminar los campos del formulario $this->id_form", 1);
                
            }
        }else{
            $delete = "delete from $this->tablaCampos where id_form=$ids";
            if($this->bd->ejecutarQuery($delete)){
                $this->eliminarDatos(array('id_form'=>$ids));        
            }else{
             throw new Exception("No se pueden eliminar los campos del formulario $this->id_form", 1);
                
            }
            
        }
        return true;
        
    }
    /**
     * Verifica el query de un formulario
     * @validarQuery
     */
    function validarQuery($query){
        try{
            if($this->bd->ejecutarQuery($query)){
                return true;
            }else{
                return false;
            }
            
        }catch(Exception $e){
            Debug::mostrarArray($e);
            if($e->getCode()=='200'){
                return false;
            }
        }
    }
    
    
    /**
     * Modifica el orden de los campos de un formulario
     * @method setOrdenCamposForm
     */ 
    public function setOrdenCamposForm($arrayCampos,$form){
        $update="";
        foreach ($arrayCampos as $key => $campo) {
            $update .= "update $this->tablaCampos set orden=".$campo['orden']." where id_campo=".$campo['id_campo'].";";            
        }//fin foreach
        $this->bd->ejecutarQuery($update,2);
        
    }
}