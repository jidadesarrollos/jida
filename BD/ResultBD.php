<?PHP
/**
 * Representa un objeto Result de Base de datos
 *
 * @internal Permite acceder y manejar la matriz resultado de una consulta a base de datos
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package Framework
 * @subpackage BD
 * @version 1.0
 */

namespace Jida\BD;
class ResultBD {
    /**
     * @var object $bd Objeto Instanciado manejador de base de datos
     */
    private $dataModel;
    /**
     * @var $result Resultado obtenido de la consulta de base de datos
     */

    private $result;
    private $bdObject;
    protected $query;
    protected $idResultado;
    protected $unico;
    private $ejecutado = FALSE;

    function __construct(DataModel $DataModel) {
        $this->setValores($DataModel);

    }

    function setValores(DataModel $DataModel) {
        $this->dataModel = $DataModel;
        $this->bdObject = $this->dataModel->__get('bd');
        $this->idResultado = $this->bdObject->__get('idResult');

        $this->result = $this->dataModel->bd->result;
        if (!empty($this->idResultado) or $this->result)
            $this->ejecutado = TRUE;

        return $this;
    }

    function getData() {
        return $this->result;
    }

    /**
     * Valida si se ejecuto la consulta a base de datos
     * @method ejecutado
     */
    function ejecutado() {
        return $this->ejecutado;
    }

    /**
     * Retorna el id obtenido de la última transacción en base de datos
     * @method idResult
     * @return int Resultado
     * @see $this::idResultado
     */
    function idResultado() {
        return $this->idResultado;
    }

    function setUnico($unico) {
        $this->unico = $unico;
    }

    function esUnico() {
        return $this->unico;
    }

    function totalRegistros() {
        return $this->bdObject->totalRegistros();
    }

    function query() {
        return $this->dataModel->bd->query;
    }

    function __set($property, $valor) {
        if (property_exists($this, $property))
            $this->$property = $valor;
    }

    /**
     * Retorna el listado de ids insertados en un salvar multiple
     * @method ids
     * @return array $idsInsertados
     * @since 1.4
     */
    function ids() {
        return $this->dataModel->obtIdsResultados();
    }

}

