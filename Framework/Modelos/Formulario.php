<?php

/**
 * Clase Modelo para s_formularios
 *
 * @internal Clase creada para la transición de Formularios creados con la clase Formulario del Framework
 * en versiones anteriores a la version 0.5
 *
 *
 * @package  Aplicacion
 * @category Modelo
 * @version  0.4
 */

namespace Jida\Modelos;

use Exception as Excepcion;
use Jida\Core\GeneradorCodigo;
use Jida\Core\JsonManager as JsonManager;
use Jida\Medios as Medios;

class Formulario extends JsonManager {

    use GeneradorCodigo\GeneradorArchivo;
    use \Jida\Core\ObjetoManager;
    var $nombre;
    var $query;
    var $clave_primaria;
    var $identificador;
    var $estructura;
    var $campos = [];

    private $_modelo = [
        'nombre',
        'query',
        'identificador',
        'estructura',
        'clave_primaria',
        'campos'
    ];

    private $_ambito = 'app';
    private $_json = [];
    private $_ubicacion;
    private $_modulo;
    private $_ce = '10041';
    private $_campos = [];
    private $_camposOrdenados = [];

    function __construct ($form = "", $modulo = null) {

        if (!empty($form)) {
            $this->_instanciar($form, $modulo);

        }
        else {
            $this->ubicacion();
        }

    }

    /**
     * Permite setear el modulo del formulario
     *
     * @param $modulo
     */
    function modulo ($modulo) {

        $this->_modulo = $modulo;

        return $this;
    }

    private function _instanciar ($form, $modulo) {

        $json = $this->path($modulo) . DS . $form;
        $this->_ubicacion = $this->path($modulo);
        parent::__construct($json);

        if ($this->campos) {
            $this->_procesarCampos();
        }

    }

    /**
     * Retorna los campos del formulario
     *
     * @return array
     */
    function campos () {

        return $this->_campos;
    }

    /**
     * Permite definir la ubicación del formulario
     *
     * @method ubicacion
     * @param string $ambito o jida.
     * @param string $modulo [opcional] Permite definir el modulo del formulario
     */
    function ubicacion ($ambito = "", $modulo = "") {

        $ubicacion = "";
        if (empty($ambito))
            $ambito = $this->_ambito;
        if (empty($modulo))
            $modulo = $this->_modulo;

        if ($ambito == 'app') {

            $ubicacion = DIR_APP;
            if (!empty($modulo) and !in_array($modulo,
                                              [
                                                  "app",
                                                  "jida"
                                              ])) {

                $ubicacion .= DS . 'Modulos' . DS . Medios\Cadenas::upperCamelCase($modulo);
                if (!Medios\Directorios::validar($ubicacion)) {
                    throw new Excepcion("El modulo pasado para guardar el formulario no existe " . $ubicacion,
                                        $this->_ce . '003');
                }
                $ubicacion .= DS . 'Formularios';

                if (!Medios\Directorios::validar($ubicacion)) {
                    throw new Excepcion("El Formulario pasado no existe en el modulo " . $modulo . " no existe " . $ubicacion,
                                        $this->_ce . '004');

                }
            }
            else $ubicacion .= DS . 'Formularios';

        }
        else $ubicacion = DIR_FRAMEWORK . DS . 'Formularios';

        $this->_ubicacion = implode(DS, array_filter(explode(DS, $ubicacion)));

        return $this->_ubicacion;

    }

    private function _procesarCampos () {

        $camposOrdenados = [];

        foreach ($this->campos as $key => $campo) {

            $campoClase = new CampoFormulario($campo);
            if (!$campoClase->orden) {

                array_push($camposOrdenados, (object)(array)$campoClase);
                $arrayCampo = $campoClase;
                $this->_campos[$arrayCampo->name] = $arrayCampo;

            }
            else {

                if (array_key_exists($campoClase->orden, $camposOrdenados)) {
                    array_push($camposOrdenados, $campoClase);
                }
                else {
                    $camposOrdenados[$campoClase->orden] = $campoClase;
                }
                $this->_campos[$campoClase->name] = $campoClase;

            }

        }

        asort($camposOrdenados);
        $this->_camposOrdenados = $camposOrdenados;

    }

    /**
     * Crea el identificador en camelCase de un formulario
     *
     * Usa el nombre del formulario para generar el identificador
     * @method _crearIdentificador
     *
     * @param string $nombre Nombre del formulario
     *
     * @return string $identificador Nombre del formulario en UpperCamelCase
     *
     */
    private function _crearIdentificador ($nombre = "") {

        if (empty($nombre))
            $nombre = $this->nombre;

        $identificador = Medios\Cadenas::upperCamelCase($nombre);
        $this->identificador = $identificador;

        return $identificador;

    }

    /**
     * Registra los campos del formulario
     *
     * Verifica si los campos pasados en la data existen en el formulario y sino los agrega.
     * @method _validarCampos
     *
     * @param {mixed}  $campos String o Arreglo de campos
     */
    private function _validarCampos ($campos = null) {

        if (is_null($campos)) {
            return;
        }

        $campos = (is_array($campos)) ? $campos : explode(',', $campos);
        $array = [];
        foreach ($campos as $key => $nombre) {

            $nombreID = str_replace(" ", "_", trim($nombre));

            if (array_key_exists($nombreID, $this->campos)) {
                $array[$nombreID] = $this->_campos[$nombreID];
            }
            else {

                $campo = new CampoFormulario();
                $campo->id = $nombreID;
                $campo->name = $nombreID;
                $campo->label = $nombre;
                $array[$nombreID] = $campo;

            }

        }
        $this->_campos = $array;

        return $array;

    }

    /**
     * Genera un json con la data del formulario
     * @method _generarJson
     *
     * @return strnig json_encode
     *
     */
    private function _generarJson () {

        $json = [];
        foreach ($this->_modelo as $key => $campo) {
            if ($campo !== 'campos') {
                $json[$campo] = $this->{$campo};
            }
        }

        $campos = [];
        foreach ($this->_campos as $nombre => $data) {

            $propiedades = $data->obtenerPropiedades();

            foreach ($propiedades as $propiedad => $valor) {

                $campos[$nombre][$propiedad] = $valor;

            }

        }
        $json['campos'] = $campos;

        #Medios\Debug::imprimir($json, true);

        return json_encode($json, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Guarda el contenido del objeto
     * @method salvar
     */
    function salvar ($data = []) {

        if (!empty($data)) {

            foreach ($data as $key => $valor) {
                if ($key != 'campos' and property_exists($this, $key)) {
                    $this->{$key} = $valor;
                }
            }

            if (empty($this->identificador)) {
                $this->_crearIdentificador();
            }
            $this->_validarCampos($data['campos']);

        }

        $json = $this->_generarJson();

        if (empty($this->identificador)) {
            $this->_crearIdentificador();
        }

        $modulo = (isset($data['modulo'])) ? $data['modulo'] : $this->_modulo;
        $directorio = $this->path($modulo);

        if (!Medios\Directorios::validar($directorio)) {
            Medios\Directorios::crear($directorio);
        }
        $nombre = $directorio . DS . $this->identificador . ".json";

        $this
            ->crear($nombre)
            ->escribir($json);

        return $this->cerrar();
    }

    /**
     * Define el ambito de los formularios
     *
     * Los ambitos posibles son app y jida si el ambito
     * jida es declarado los formularios se guardaran en la carpeta
     * formularios dentro del framework y no de la aplicacion
     */
    function _ambito ($ambito = 'app') {

        $this->_ambito = $ambito;
    }

    function orden ($campos) {

        foreach ($campos as $nombre => $posicion) {

            if (array_key_exists($nombre, $this->_campos)) {
                $this->_campos[$nombre]['orden'] = $posicion;
            }

        }

        return $this;

    }

    /**
     * Retorna los valores del campo solicitado.
     *
     * @param $campo
     *
     * @return bool|mixed
     */
    function dataCampo ($campo, $data = null) {

        $selector = false;
        if (array_key_exists($campo, $this->_campos)) {
            $selector = $this->_campos[$campo];
            if ($data and is_array($data)) {

                $clase = $this->_campos[$campo];
                foreach ($data as $propiedad => $valor) {
                    if (property_exists($clase, $propiedad)) {
                        $clase->{$propiedad} = $valor;
                    }
                }
                $selector = $this->_campos[$campo] = $clase;

            }

            if ($selector and !$selector->type) {
                $selector->type = 'text';
            }
            $selector->control = $selector->type;
        }

        return $selector;

    }

    static function path ($modulo) {

        switch (strtolower($modulo)) {
            case 'jida':
                $ubicacion = DIR_FRAMEWORK . DS . 'Formularios';
                break;
            case 'app':
            case 'principal':
            case '':
                $ubicacion = DIR_APP . DS . 'Formularios';
                break;
            default:
                $modulo = Medios\Cadenas::upperCamelCase($modulo);
                $ubicacion = DIR_APP . DS . 'Modulos' . DS . $modulo . DS . 'Formularios';
                break;
        }

        return $ubicacion;

    }

}//fin clase