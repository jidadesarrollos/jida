<?PHP
/**
 * Definición de la clase JidaFormulario
 *
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @subpackage
 * @category Modelo
 * @version 1.0
 */

namespace Jida\Modelos\Viejos;
use Jida\BD as BD;
class JidaFormulario extends BD\DBContainer{

     /**
     * Id del formulario
      * @var int $id_form
     */
    public $id_form;

    /**
     * Nombre del formulario
     * @var string $nombre_f
     */
    public $nombre_f;

    /**
     *@var string $query_f Query que crea el formulario.
     */
    public $query_f;
    /**
     * Define la estructura de un formulario a renderizar por medio del metodo armarFormularioEstructura
     * @internal La estructura se define colocando el numero de campos que se desean por culumnas, teniendo en cuenta
     * que se utiliza el sistema grid de bootstrap y q el max. de columnas es 12.
     * Si se desea emplear el mismo grid de columnas en varias filas se puede usar el simbolo "x" de modo que
     * "3x5" repetirá 5 filas de 3 columnas
     * @var string $estructura
     * @example 1;3;2x4;3;1
     * @access public
     * @see @armarFormularioEstructura
     */
    public $estructura;
    /**
     *  @var string $nombre_identificador Nombre unico para identificar el formulario
     */
    public $nombre_identificador;
    /**
     * Define la clave primaria del formulario creado.
     *
     * Es utilizada para traer formularios en modo update
     * @var $clave_primaria_f
     */
    public $clave_primaria_f;
    /**
     * Constructor
     * @param $tabla 2 Si es JidaFramework 1 Si es aplicacion
     * @param $id Id del formulario [opcional]
     */
    function __construct($tabla,$id=""){
        if($tabla==1){
            $this->nombreTabla="s_formularios";
            $this->clavePrimaria="id_form";
        }elseif($tabla==2){
            $this->nombreTabla="s_jida_formularios";
            $this->clavePrimaria="id_form";
        }


        parent::__construct(__CLASS__,$id);

    }
    /**
     * Inicializa los valores del objeto a partir de un arreglo asociativo
     * @method inicializarForm
     * @see establecerAtributos
     * @param array $datos Arreglo asociativo, las claves deben tener nombres de las propiedades del objeto
     * @return void
     */
    function inicializarForm($datos){
        $this->establecerAtributos($datos);
    }
}

?>