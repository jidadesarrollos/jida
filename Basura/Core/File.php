<?PHP
/**
 * Definición de la clase
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package
 * @subpackage
 * @category Modelo
 * @version 1.0
 * @revision
 *
 * Archivos Requeridos :
 *  Directorios.class.php
 *
 */

namespace Jida\Core;
class File{
    /**
     * @var string Directorio Ubicación Física del archivo a crear/editar
     */
    protected $directorio;
    /**
     * @var string $nombre Nombre del Archivo
     */
    protected $nombre;
    /**
     * Archivo creado o abierto
     * @var $archivo
     * @access protected
     */
    protected $archivo;
    /**
     * @var array $lineas Lineas del Archivo Guardadas en un array
     */
    protected $lineas=array();
    /**
     * @var int $totalLineas Registra total de lineas del archivo
     */
    protected $totalLineas;

    function __construct($directorio,$name){
        $this->directorio=$directorio;
        $this->name=$name;
    }

    /**
     * Crea un archivo
     * @method crear
     */
    function crear(){
        if(!file_exists($this->directorio)){
            Directorios::crear($this->directorio);
        }
        $this->archivo=fopen($this->directorio."/".$this->name,'w');
        if($this->archivo){
            return true;
        }else{
            return false;
        }
    }

    function getLineas(){
        while(!feof($this->archivo)){
            $this->lineas[]=fgets($this->archivo);
        }
        $this->totalLineas=count($this->lineas);

    }
    /**
     * Devuelve el total de lineas de un archivo
     * @method getTotalLineas
     */
    function getTotalLineas(){
        if(count($this->lineas)>0){
            $this->totalLineas=count($this->lineas);
            return $this->totalLineas;
        }else{
            return  0;
        }
    }
    /**
     * Cierra un archivo abierto
     * @method cerrar
     */
    function cerrar($archivo=null){
        if(is_null($archivo)){
            $archivo=& $this->archivo;
        }
        fclose($archivo);
    }
    /**
     * Cuenta las tabulaciones existentes en una linea
     * @method countTabs
     * @param string $line Linea a leer
     */
    protected function countTabs($line){
        $count = strspn($line, "\t");
        return $count;
    }
    /**
     * Agrega las tabulaciones necesarias para una linea
     * @method addTabs
     * @param int $numero Numero de Tabulaciones
     */
    protected function addTabs($numero){
        $tabs="";
        for($i=0;$i<$numero;$i++)
            $tabs.="\n";
        return $tabs;
    }

}

?>