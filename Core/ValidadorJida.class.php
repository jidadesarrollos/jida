<?php
/**
 * Validador de Controles de Formulario
 *
 * Basado en realizar las validaciones del Plug-in "validadorJida.js" a nivel del Servidor
 * @author Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * Fecha : 28/10/2013
 * @version 1.2 03-2014
 */

class ValidadorJida {
    /**
     * @var array $validaciones Arreglo opcional con parametros de la validación
     */
    private $validaciones = array();
    /**
     *@var string $valorCampo Registra el valor del campo a validar
     */
    private $valorCampo;
    /**
     * @var string $mensajeError Guarda el String del mensaje de Error
     */
    private $mensajeError="";
    /**
     * @var array $validacionesDefault Valores por defecto para las validaciones;
     */
    private $validacionesDefault;
    /**
     * @var array $expresiones Arreglo con las expresiones disponibles para validar
     */
    private $expresiones;
    /**
     * @var boolean $error indica si existe un error en el campo 0 no 1 existe.
     */
    private $error;
    /**
     * @var array $campo Arreglo con todos los datos del campo
     */
    private $campo;
    
    private $mensajesDefecto=array();
	/**
	 * Registro de opciones del Campo
	 */
	private $opciones ="";
	
	
	/**
	 * Constructor de la clase Validador Jida
	 * @param string $campo Campo a validar
	 * @param array $validaciones Arreglo con validaciones asignadas al campo
	 * @opciones string $opciones [opcional] Las opciones asociadas al campo a validar
	 */
    function __construct($campo, $validaciones,$opciones="") {
        $this -> campo = $campo;
        $this->obtenerMensajes();
        $this -> validaciones = array_merge($this -> validacionesDefault, $validaciones);
        $this -> obtenerExpresiones();
    }//fin funcion
    private function obtenerMensajes(){
        $this -> validacionesDefault = array(
                                                'numerico' => false, 
                                                'obligatorio' => false, 
                                                'decimal' => false, 
                                                'caracteres' => false, 
                                                'alfanumerico'=>false,
                                                'programa'=>false,
                                               );
        $this->mensajesDefecto = array(
                                    'numerico' => "Debe ser numerico",
                                    'obligatorio' => "Es Obligatorio",
                                    'decimal' => "Debe ser decimal y los decimales deben estar separados por coma",
                                    'caracteres' => "solo puede contener caractares",
                                    'alfanumerico' => "no puede contener caracteres especiales",
                                    'programa'=>"solo puede poseer caracteres alfanumericos, underscore o guion",
                                    'email'=>"El formato del email no es valido",
                                    'telefono'=>'El formato del telefono debe ser 212 4222211',
                                    'celular'=>'El formato del celular debe ser 4212 4222211',
                                    'coordenada'=>'La coordenada debe tener el siguiente formato',
        							'contrasenia'=> 'Debe cumplir con las especificaciones establecidas.',
        							'internacional'=>'El telefono internacional no es valido',
                                    'fecha'=>'El formato de fecha debe ser dd-mm-yyyy'
                                   );
    }
    private function obtenerExpresiones() {
        $exp = array(
                                                "numerico" => "/^(?:\+|-)?\d+$/", 
                        "decimal" => "/^([0-9])*[.]?[0-9]*$/", 
                        "caracteres" => "/^[A-ZñÑa-záéíóúÁÉÍÓÚ ]*$/",
        				"programa" => "/^[A-Za-z_-]*$/",
        				"alfanumerico" => "/^[\dA-ZñÑa-záéíóúÁÉÍÓÚ ]*$/",
                        #"alfanumerico" => "/^[\s\w]*$/",
                        "programa"=>"/^[\/\.A-Za-z_-]*$/",
		        		"seguridad"		=> "/^([^(;\'\|\*\"\/\\)])*$/",
		        		"seguridad2"	=> "/^([A-Za-z0-9\._]|\-[A-Za-z0-9\._])*\-?$/",
		        		#"email"			=> "/^[A-Za-z0-9._-]*@[A-Za-z]*\.[A-Za-z]*$/",
		        		#"email"			=> "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",
		        		"email"			=> "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/",
		        		#"email"			=> "/^[A-Za-z0-9._-]*@[A-Za-z]*\.[A-Za-z]{4}\.[A-Za-z]{2}|[A-Za-z0-9._-]*@[A-Za-z]*\.[A-Za-z]{2}$/",
		        		"cedula"		=> "/^[0-9]{6,8}$/",
		        		"celular"		=> "/^(412|416|414|424|426)\d{7}$/",
        				"internacional" => "/^\d{9,18}$/",
		        		"telefono"		=>	"/^2[0-9]{9,13}$/",
		        		"twitter"		=> "/^[A-Za-z0-9._-]*$/",
		        		"url"			=> "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \?=.-]*)*\/?$/",
        				"minuscula"		=> "/[a-z]/",
        				"mayuscula"		=> "/[A-Z]/",
        				"numero"		=> "/[0-9]/",
        				"caracteresEsp"	=> "/(\||\!|\"|\#|\$|\%|\&|\/|\(|\)|\=|\'|\?|\<|\>|\,|\;|\.|\:|\-|\_|\*|\~|\^|\{|\}|\+)/",
        				"coordenada"    =>  "/^\-?[0-9]{2}\.[0-9]{3,15}/",
        				"fecha"			=> "/^\d{2,4}[\-|\/]{1}\d{2}[\-|\/]{1}\d{2,4}$/",
		        		
                     );


/**
 *    celular:{expr:/^4\d{9}$/,mensaje:"El formato del celular no es valido"},
      telefono:{expr:/^2[0-9]{13}|[2|4][0-9]{9}$/,mensaje:"El formato del telefono no es valido"},
 */
        $this -> expresiones = $exp;
    }//final funcion

    /**
     * Verifica si una cadena tiene el formato requerido
     *
     * Valida si la cadena cumple con el formato requerido por medio
     * de una expresión regular.

     * @param String $validacion Nombre de la validación a aplicar.
     * @param array $datosValidacion Arreglo con inf. de la validacion
     * @return boolean True Si es correcto, fal
     */
    private function validarCadena($nombreValidacion, $datosValidacion) {
    	if($nombreValidacion=="decimal" or $nombreValidacion=="numerico"){
	    	$this->valorCampo = str_replace(".","",$this->valorCampo);
			$this->valorCampo = str_replace(",",".",$this->valorCampo);
				
			
    	}
        #echo $this->campo['name']." ".$nombreValidacion."<hr>";
       
        if (preg_match($this -> expresiones[$nombreValidacion], $this -> valorCampo)) {
        	/**
				 * En caso de que sea decimal o numerico se reemplaza el valor formateado por el requerido 
				 * para el registro en base de datos
				 */
        		if(array_key_exists($this->campo['name'], $_POST)){
        			
        			$_POST[$this->campo['name']] = $this->valorCampo;
					
        		}
					
            return true;
        } else {
        	
            $this->obtenerMensajeError($nombreValidacion,$datosValidacion);
            return false;
        }
    }
    private function obtenerMensajeError($validacion,$datos){
    	
        if(isset($datos['mensaje'])){
            $this->mensajeError = $datos['mensaje'];
        }else{        
            $this->mensajeError = "El campo ".$this->campo['label'] ." ".$this->mensajesDefecto[$validacion];
        }
        
    }
    /**
     * Valida un campo
     *
     * Funcion controladora que verifica las validacionesexit
     *  que debe tener un campo
     * @param string $campo Campo a validar
     */
    function validarCampo($campo) {

        $this->valorCampo = $campo;
        
        //En caso de haber un error la variable bandera debe ser modificada a 1.
        $bandera = 0;
        $valorLleno = $this -> validarCampoLleno();
        //El valor es identico a true si el campo tiene algun valor
        if ($valorLleno !== true and $this -> validaciones['obligatorio'] != false) {
            $datosObl = $this -> validaciones['obligatorio'];
            $bandera = 1;
            $this -> mensajeError = $datosObl['mensaje'];
        } elseif ($valorLleno === true) {
            unset($this->validaciones['obligatorio']);
            #---------------------------------------------
            
            foreach ($this->validaciones as $validacion => $detalle) {
                $CheckValor = false;
                $validacion = strtolower($validacion);
                
                if ($bandera == 0 and (is_array($detalle) or $detalle == true)) {
                    
                    switch ($validacion) {
                        case "obligatorio" :
                            $nada="";
                            break;
						
						case "telefono" :
							$CheckValor = $this->validarTelefono($validacion,$detalle);
							break;
                        case "fecha" :
							$CheckValor = $this->validarFecha($validacion,$detalle);
							break;
                        default :
                            $CheckValor = $this->validarCadena($validacion, $detalle);
                            break;
                    }
                    #echo"bandera " . $bandera."<hr>chek $CheckValor<hr>";
                    if ($CheckValor!==true) {
                        
                        $bandera = 1;
                    }
                }
            }//fin foreach
            #---------------------------------------------
            
            
        }
        //fin if
        //-------------------------
        if ($bandera == 0) {
            return true;
        } else {
            
            return $this -> mensajeError;
        }
        

    }//fin validarCampo

     /**
     * Valida un campo obligatorio
	  * @method validarCampoLleno
	  * @access private
     */
    private function validarCampoLleno() {
    	
    	$validacion = true;
    	if(is_array($this->validaciones['obligatorio']) and array_key_exists('condicional',$this->validaciones['obligatorio'])){
    		if($_POST[$this->validaciones['obligatorio']['condicional']]==$this->validaciones['obligatorio']['condicion']){
    			$validacion = true;
    		}else{
    			$validacion=false;
    		}
    	}
        if($validacion===true){
        	if ($this->valorCampo != "") {
        		return true;
        	} else {
        		return false;
        	}
        }else{
        	return true;
        }

    }
	
	/**
	 * Valida un campo con formato fecha
	 * @method validarFecha
	 * @access private
	 */
	private function validarFecha($validacion,$detalle){
		if($this->validarCadena('fecha', $this->valorCampo)){
			$_POST[$this->campo['name']]=FechaHora::fechaInvertida($this->valorCampo);
			return true;
		}else{
			$this->obtenerMensajeError('fecha', $detalle);
			return false;
		}
	}
	/**
	 * Valida un campo para telefono
	 * @method validarTelefono
	 * @access private
	 */
	private function validarTelefono($validacion,$detalle){
		
		
		$code =(array_key_exists("code",$detalle))?$detalle['code']:false;
		$ext =(array_key_exists("ext",$detalle))?$detalle['ext']:false;
		$todalDigitos=($ext)?14:10;
		$valorCodigo = (isset($_POST[$this->campo['name']."-codigo"]))?$_POST[$this->campo['name']."-codigo"]:"";
		$valorExt = (isset($_POST[$this->campo['name']."-ext"]))?$_POST[$this->campo['name']."-ext"]:"";
		
		$valorTelefono = $valorCodigo.$this->valorCampo.$valorExt;
		$expTlf=$this->expresiones["telefono"];
		$expCel=$this->expresiones['celular'];
		$expInter=$this->expresiones['internacional'];
		$validacionTlf =(preg_match($expTlf,$valorTelefono))?1:0;
		$validacionCel =(preg_match($expCel,$valorTelefono))?1:0;
		$validacionInter = (preg_match($expCel,$valorTelefono))?1:0;
		if(	array_key_exists('tipo',$detalle) and
				($detalle['tipo']=='telefono' and $validacionTlf==1 or
					$detalle['tipo']=='celular' and $validacionCel==1 or
					$detalle['tipo']=='internacional' and $validacionInter==1 or
					$detalle['tipo']=="multiple" and ($validacionCel==1 or $validacionTlf==1 or $validacionInter==1)) or 
					!array_key_exists('tipo',$detalle) and $validacionTlf==1){
					
					return true;
				}else{
					$this->obtenerMensajeError('telefono', $detalle);
					return false;
				}
		
	}
	
	/**
	 * Valida un campo de contraseña segura
	 * @method validarContrasenia
	 * @access private
	 */
	private function validarContrasenia($validacion,$detalle){

		$contrasenia = $this->valorCampo;
		
		$expMin = $this->expresiones['minuscula'];
		$expMay = $this->expresiones['mayuscula'];
		$expNum = $this->expresiones['numero'];
		$expCaract = $this->expresiones['caracteresEsp'];
		
		$validacionMin =(preg_match($expMin,$contrasenia))?1:0;
		$validacionMay =(preg_match($expMay,$contrasenia))?1:0;
		$validacionNum =(preg_match($expNum,$contrasenia))?1:0;
		$validacionCaract =(preg_match($expCaract,$contrasenia))?1:0;
		
		if($validacionMin == 1 && $validacionMay == 1 && $validacionNum == 1 && $validacionCaract == 1 && strlen($contrasenia) >= 8){
			return true;
		}else{
			$this->obtenerMensajeError('contrasenia', $detalle);
			return false;
		}

	}

}