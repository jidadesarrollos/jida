<?php

/**
 * Clase Modelo para s_formularios
 *
 * @internal Clase creada para la transición de Formularios creados con la clase Formulario del Framework
 * en versiones anteriores a la version 0.5
 *
 *
 * @package Aplicacion
 * @category Modelo
 * @version 0.4
 */

namespace Jida\Modelos;

use Jida\Core\JsonManager as JsonManager;
use Jida\Helpers as Helpers;
use Jida\Core\GeneradorCodigo;
use Exception as Excepcion;

class Formulario extends JsonManager {

    use GeneradorCodigo\GeneradorArchivo;

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

    function __construct($form = "", $modulo = null) {

        if (!empty($form)) {
            $this->_instanciar($form, $modulo);

        } else {
            $this->ubicacion();
        }

    }

    /**
     * Permite setear el modulo del formulario
     * @param $modulo
     */
    function modulo($modulo) {

        $this->_modulo = $modulo;

        return $this;
    }


    private function _instanciar($form, $modulo) {

        $json = $this->path($modulo) . DS . $form;
        $this->_ubicacion = $this->path($modulo);
        parent::__construct($json);

        if ($this->campos) {
            $this->_procesarCampos();
        }

    }

    /**
     * Define la ubicación del formulario solicitado
     *
     * Los formularios pueden encontrarse en tres posibles ubicaciones :
     * - Módulo base de la aplicación
     * - Módulo interno de la aplicación
     * - Framework.
     * @param array $argumentos Arreglo de parametros pasados al constructor
     * @return string
     */
    private function _obtUbicacionFormulario() {

        $ubicacion = $this->ubicacion();

        if ($this->identificador) {
            $ubicacion .= DS . $this->_nombreJSON($this->identificador);
            $ubicacion = implode(DS, array_filter(explode(DS, $ubicacion)));
        }

        return $ubicacion;

    }

    /**
     * Agrega la extension json al nombre de un archivo si no la tiene
     * @method _nombreJson
     * @param {string} $nombre Nombre del archivo
     */
    private function _nombreJSON($nombre) {

        if (strpos($nombre, '.json') === FALSE) {
            return $nombre . '.json';
        }

        return $nombre;

    }

    /**
     * Retorna los campos del formulario
     * @return array
     */
    function campos() {

        return $this->_campos;
    }

    /**
     * Permite definir la ubicación del formulario
     *
     * @method ubicacion
     * @param string $ambito o jida.
     * @param string $modulo [opcional] Permite definir el modulo del formulario
     */
    function ubicacion($ambito = "", $modulo = "") {

        $ubicacion = "";
        if (empty($ambito)) $ambito = $this->_ambito;
        if (empty($modulo)) $modulo = $this->_modulo;

        if ($ambito == 'app') {

            $ubicacion = DIR_APP;
            if (!empty($modulo) and !in_array($modulo, ["app", "jida"])) {

                $ubicacion .= DS . 'Modulos' . DS . Helpers\Cadenas::upperCamelCase($modulo);
                if (!Helpers\Directorios::validar($ubicacion)) {
                    throw new Excepcion("El modulo pasado para guardar el formulario no existe " . $ubicacion, $this->_ce . '003');
                }
                $ubicacion .= DS . 'Formularios';

                if (!Helpers\Directorios::validar($ubicacion)) {
                    throw new Excepcion("El Formulario pasado no existe en el modulo " . $modulo . " no existe " . $ubicacion, $this->_ce . '004');

                }
            } else $ubicacion .= DS . 'Formularios';

        } else $ubicacion = DIR_FRAMEWORK . DS . 'Formularios';

        $this->_ubicacion = implode(DS, array_filter(explode(DS, $ubicacion)));


        return $this->_ubicacion;

    }

    private function _procesarCampos() {

        $camposOrdenados = [];
        foreach ($this->campos as $key => $campo) {

            $campoClase = new CampoFormulario($campo);
            if (!$campoClase->orden) {

                array_push($camposOrdenados, (object)(array)$campoClase);
                array_push($this->_campos, (array)$campoClase);
            } else {

                if (array_key_exists($campoClase->orden, $camposOrdenados)) {
                    array_push($camposOrdenados, $campoClase);
                } else {
                    $camposOrdenados[$campoClase->orden] = $campoClase;
                }
                $this->_campos[$campoClase->name] = (array)$campoClase;

            }


        }
        asort($camposOrdenados);

        $this->campos = $camposOrdenados;

    }


    /**
     * Crea el identificador en camelCase de un formulario
     *
     * Usa el nombre del formulario para generar el identificador
     * @method _crearIdentificador
     * @param string $nombre Nombre del formulario
     * @return string $identificador Nombre del formulario en UpperCamelCase
     *
     */
    private function _crearIdentificador($nombre = "") {

        if (empty($nombre)) $nombre = $this->nombre;

        $identificador = Helpers\Cadenas::upperCamelCase($nombre);
        $this->identificador = $identificador;

        return $identificador;

    }

    /**
     * Registra los campos del formulario
     *
     * Verifica si los campos pasados en la data existen en el formulario y sino los agrega.
     * @method _validarCampos
     * @param {mixed}  $campos String o Arreglo de campos
     */
    private function _validarCampos($campos = null) {

        if (!is_null($campos)) {
            return;
        }
        $campos = (is_array($campos)) ? $campos : explode(',', $campos);
        $array = [];

        foreach ($campos as $key => $nombre) {

            $nombreID = str_replace(" ", "_", trim($nombre));

            if (array_key_exists($nombreID, $this->campos)) {
                $array[$nombreID] = $this->_campos[$nombreID];
            } else {

                $campo = new CampoFormulario();
                $campo->id = $nombreID;
                $campo->name = $nombreID;
                $campo->label = $nombre;
                $array[$nombreID] = $campo;

            }

        }
        $this->_campos = $array;
        Helpers\Debug::imprimir($array, true);

        return $array;

    }

    /**
     * Genera un json con la data del formulario
     * @method _generarJson
     * @return strnig json_encode
     *
     */
    private function _generarJson() {

        $json = [];
        #Helpers\Debug::imprimir($this->_modelo, $this->campos);
        foreach ($this->_modelo as $key => $campo) {
            if ($campo !== 'campos') {
                $json[$campo] = $this->{$campo};
            }
        }

        $campos = [];
        foreach ($this->_campos as $nombre => $data) {

            $campos[$nombre] = [
                'id'          => $data['id'],
                'label'       => $data['label'],
                'name'        => $data['name'],
                'eventos'     => $data['eventos'],
                'opciones'    => $data['opciones'],
                'orden'       => $data['orden'],
                'placeholder' => $data['placeholder'],
                'class'       => $data['class'],
                'data'        => $data['data'],
                'visibilidad' => $data['visibilidad'],
                'type'        => $data['type'],
            ];

        }
        $json['campos'] = $campos;

        return json_encode($json, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Guarda el contenido del objeto
     * @method salvar
     */
    function salvar($data = []) {

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

        $directorio = $this->path($this->_modulo);

        if (!Helpers\Directorios::validar($directorio)) {
            Helpers\Directorios::crear($directorio);
        }

        $this
            ->crear($directorio . DS . $this->identificador . ".json")
            ->escribir($json)
            ->cerrar();

        return true;
    }

    /**
     * Define el ambito de los formularios
     *
     * Los ambitos posibles son app y jida si el ambito
     * jida es declarado los formularios se guardaran en la carpeta
     * formularios dentro del framework y no de la aplicacion
     */
    function _ambito($ambito = 'app') {

        $this->_ambito = $ambito;
    }


    function orden($campos) {

        $totalCampos = count($this->campos);
        for ($i = 0; $i < $totalCampos; ++$i) {

            $campo =& $this->campos[$i];

            if (is_object($campo) and array_key_exists($campo->id, $campos)) {
                $campo->orden = $campos[$campo->id];
            }
        }

        #Helpers\Debug::imprimir($this->campos);
        return $this;

    }

    /**
     * Retorna los valores del campo solicitado.
     *
     * @param $campo
     * @return bool|mixed
     */
    function dataCampo($campo) {

        $data = FALSE;

        if(array_key_exists($campo, $this->_campos)) {
            $data = $this->_campos[$campo];
        }
        if (!$data['type']) {
            $data['type'] = 2;
        }

        $data['control'] = $data['type'];

        return $data;

    }

    static function path($modulo) {

        switch (strtolower($modulo)) {
            case 'jida':
                $ubicacion = DIR_FRAMEWORK . 'Formularios';
                break;
            case 'app':
            case 'principal':
                $ubicacion = DIR_APP . 'Formularios';
                break;
            default:
                $modulo = Helpers\Cadenas::upperCamelCase($modulo);
                $ubicacion = DIR_APP . 'Modulos' . DS . $modulo . DS . 'Formularios';
                break;
        }

        return $ubicacion;

    }
}//fin clase