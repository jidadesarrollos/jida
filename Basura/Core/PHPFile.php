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
 */

namespace Jida\Core;
class PHPFile extends File{


    private $content;
    private $accion;

    function __construct($directorio,$name){
        parent::__construct($directorio,$name);



    }
    /**
     * Crea una clase php
     * @method crearClase
     * @param string $nombre Nombre de la clase
     * @param string $tipo Tipo de clase a crear 0 Clase default 1 Modelo 2 Controller
     * @param string $extends Dependencia de la clase
     */
    function crearClase($nombre,$tipo,$extends){
        $this->archivo;

    }

    private function addCommentBlock($linea=0,$content=null){
        $coment = "\/**".PHP_EOL."*";
        // if(!is_null($content) and !is_empty($content)){
            // $coment.=
        // }
        $coment.=PHP_EOL."*/";
        $this->archivo;
    }


    function agregarSaltoLinea(){
        return $this->content.="\n";
    }


}//fin clase

?>