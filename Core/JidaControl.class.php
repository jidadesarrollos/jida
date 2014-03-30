<?PHP 

/**
 * Controlador de Funcionamiento del JIDA
 *
 * @package FRAMEWORK
 * @subpackage JIDA
 * @category JIDA
 * @author  Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * Fecha : 23/10/2013
 */
#require_once 'BD/DBContainer.class.php';
#require_once 'Formulario.class.php';
class JidaControl extends DBContainer{
    
    var $id_form;
    var $nombre_f;
    var $query_f;
	var $nombre_identificador;
    var $clave_primaria_f;
	var $estructura;
    private $tablaCampos = "s_campos_f";
	
	
	
    function __construct($id_form=""){

        $this->nombreTabla="s_formularios";
        $this->clavePrimaria="id_form";
        parent::__construct(__CLASS__);
		if($id_form!=""){
			$this->id_form = $id_form;
			$formulario = $this->obtenerDatosFormulario();
			
			
		}
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
		$totalColumnas = $this->bd->totalField($resultDatos);
        if($accion['accion']=='Insertado'){
            
            $campos = array();
            for ($i=0; $i < $totalColumnas; $i++) {
                $nombreCampo = $this->bd->obtenerNombreCampo($resultDatos, $i); 
                $campos[] = "($this->id_form,'$nombreCampo','$nombreCampo',2)";
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
					$campo = new campoHTML(array("name"=>$nombreCampo,"id_propiedad"=>$nombreCampo,"id_form"=>$this->id_form));
                    $campo->procesarCampo();
				}
				
				$camposActuales[]="'$nombreCampo'";
                
			}//final for
			$queryCheck = sprintf("delete from $this->tablaCampos where id_form=%d and name not in(%s)",
								$this->id_form,
								implode(",", $camposActuales)
								);
            
			$this->bd->ejecutarQuery($queryCheck);

        }//fin else

    }//fin funcion
    
    /**
	 * Crea una lista con los campos de un formlario creado, asociando un evento javascript
	 * 
	 */
    function vistaCamposFormulario(){
        
        $query = "select id_campo,name from $this->tablaCampos where id_form=$this->id_form order by orden asc";
        $data = $this->bd->obtenerDataCompleta($query);
        
        $vista = "<ul id=\"listaCampos\" style='width:200px;'>";
        foreach ($data as $key => $value) {
            $vista.="   <li data-id-campo=\"$value[id_campo]\">$value[name]</li>
                      ";  
        }//fin foreach
        $vista.="</ul>";
        
        return $vista;

    }//fin funcion
    /**
     * Valida y procesa el formulario de campos
     * 
     * 
     */
    function procesarCampos($post){
        $form = new Formulario(2);
        $validacion = $form->validarFormulario($post);
        
        if($validacion===true){
            $claseCampo = new campoHTML($_POST);
            $claseCampo->procesarCampo();
            return true;    
        }else{
            return $validacion;
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
		
		$query = "select count(*) from s_campos_f where id_form=$id";
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
        try{
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
        }catch(Exception $e){
            
        }
    } 
}