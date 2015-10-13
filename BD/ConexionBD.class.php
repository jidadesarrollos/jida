<?php
/**
 * Clase Conexion BD
 * 
 * Define y establece una Conexion a la Base de Datos
 *
 * @category    framework
 * @package     BD
 *
 * @author      Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * @license     http://www.gnu.org/copyleft/gpl.html    GNU General Public License
 * @version     0.1 - 09/09/2013
 *
 */
class ConexionBD {
    
    protected $bd;
    /**
     * 
     * @var unknown
     */
    protected $idConexion;
    
    /**
     * 
     * @var unknown
     */
    protected $usuario;
    
    /**
     * 
     * @var unknown
     */
    protected $clave;
    
    /**
     * 
     * @var unknown
     */
    protected $servidor;
    
    /**
     * 
     * @var unknown
     */
    protected $manejadorBD;
    
    /**
     * 
     * @var unknown
     */
    protected $conexionID;
    
    
    /**
     * Define el puerto de conexión a base de datos
     * @var string $puerto
     * @access protected
     */
    protected $puerto;
    
    /**
     * Constructor
     * 
     * Asigna los valores por defecto a los atributos de la clase y Establece 
     * 
     * @throws Exception Error de Conexion a la Base de Datos
     */
    public function __construct($conexion="default") {
       		 
            if(array_key_exists('conexiones',$GLOBALS) and array_key_exists($conexion,$GLOBALS['conexiones'])){
                $arr = $GLOBALS['conexiones'][$conexion];
                $metodos = get_class_vars(__CLASS__);
                
                foreach($metodos as $k => $valor) {
                    
                    if (isset($arr[$k])) {
                        $this->$k = $arr[$k];
                    }
                }
            }else       
            if (defined('usuarioBD') and defined('servidorBD') and defined('manejadorBD') and defined('claveBD') and defined('puerto') and defined('BDutilizada')) {
                $this->usuario = usuarioBD;
                $this->servidor= servidorBD;
                
                $this->clave = claveBD;
                $this->puerto = puerto;
                $this->bd = BDutilizada;
            
            }else {
                throw new Exception ( "Error en constantes de configuración a base de datos", 101 );
            }
        
    }
    
    
    
    
}