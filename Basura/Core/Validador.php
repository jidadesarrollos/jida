<?php
/***
 * Clase Generica para validaciones del framework
 *
 *
 * @author   Julio Rodriguez <jirodriguez@sundecop.gob.ve
 * @package  Framework
 * @category Validate
 * @version  0.1
 */

namespace Jida\Core;

use Jida\Helpers as Helpers;

class Validador {

    /**
     * @var string $mensajeError Guarda el String del mensaje de Error
     */
    protected $mensajeError = "";


    /**
     * @var boolean $validacion TRUE si se cumple la valicación, FALSE caso contrario
     */
    protected $validacion;
    /**
     * Arreglo asociativo con validaciones y mensajes de error
     *
     * @var array dataValidaciones
     */
    protected $dataValidaciones =
        [
            'numerico'            => ['expresion' => "/^(?:\+|-)?\d+$/",
                                      'mensaje'   => "Debe ser numerico"
            ],
            'obligatorio'         => ['expresion' => "/^.*$/",
                                      'mensaje'   => "Es Obligatorio"
            ],
            'decimal'             => ['expresion' => "/^([0-9])*[.]?[0-9]*$/",
                                      'mensaje'   => "Debe ser decimal y los decimales deben estar separados por coma"
            ],
            'caracteres'          => ['expresion' => "/^[A-ZñÑa-záéíóúÁÉÍÓÚ.,\'{1} ]*$/",
                                      'mensaje'   => "solo puede contener caractares"
            ],
            'alfanumerico'        => ['expresion' => "/^[\dA-ZñÑa-záéíóúÁÉÍÓÚ.,\'{1} ]*$/",
                                      'mensaje'   => "no puede contener caracteres especiales"
            ],
            'programa'            => ['expresion' => "/^[\d\/\.A-Za-z_-]*$/",
                                      'mensaje'   => "Solo puede poseer caracteres alfanumericos,underscore o guion"
            ],
            'email'               => ['expresion' => "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/",
                                      'mensaje'   => "El formato del email no es valido"
            ],
            'telefono'            => ['expresion' => "/^0?2[0-9]{9,13}$/",
                                      'mensaje'   => "El formato del telefono debe ser 0212 4222211"
            ],
            'multiple'            => ['expresion' => "/^0?2[0-9]{9,13}$/",
                                      'mensaje'   => "El tel&eacute;fono ingresado no es v&aacute;lido"
            ],
            'celular'             => ['expresion' => "/^0?(412|416|414|424|426)\d{7}$/",
                                      'mensaje'   => "El formato del celular debe ser 4212 4222211"
            ],
            'coordenada'          => ['expresion' => "/^\-?[0-9]{2}\.[0-9]{3,15}/",
                                      'mensaje'   => "La coordenada debe tener el siguiente formato"
            ],
            'contrasenia'         => ['expresion' => "",
                                      'mensaje'   => "Debe cumplir con las especificaciones establecidas."
            ],
            'internacional'       => ['expresion' => "/^[0-9]{9,18}$/",
                                      'mensaje'   => "El telefono internacional no es valido"
            ],
            'minuscula'           => ['expresion' => "/[a-z]/",
                                      'mensaje'   => "El telefono internacional no es valido"
            ],
            'mayuscula'           => ['expresion' => "/[A-Z]/",
                                      'mensaje'   => "El telefono internacional no es valido"
            ],
            'numero'              => ['expresion' => "/[0-9]/",
                                      'mensaje'   => "El telefono internacional no es valido"
            ],
            'caracteresEsp'       => ['expresion' => "/(\||\!|\"|\#|\$|\%|\&|\/|\(|\)|\=|\'|\?|\<|\>|\,|\;|\.|\:|\-|\_|\*|\~|\^|\{|\}|\+)/",
                                      'mensaje'   => "El campo debe contener alg&uacute;n caracter especial"
            ],
            'twitter'             => ['expresion' => "/^[A-Za-z0-9._-]*$/",
                                      'mensaje'   => "Formato de twitter incorrecto"
            ],
            'url'                 => ['expresion' => "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \?=.-]*)*\/?$/",
                                      'mensaje'   => "El formato de la URL no es correcto"
            ],
            'seguridadComillas'   => ['expresion' => "(([A-Za-z0-9])*\'([A-Za-z0-9])*|\'([A-Za-z0-9])*|([A-Za-z0-9])*\')",
                                      'mensaje'   => ""
                                      /*NO PUEDEN HABER DOS COMILLAS SIMPLES EN EL MISMO CAMPO '' */
            ],
            'seguridadComentario' => ['expresion' => "([A-Za-z0-9\|\!\"\#\$\%\&\(\)\=\?\<\>\,\;\.\:\-\_\~\^\{\}\+\*]|\/[A-Za-z0-9\|\!\"\#\$\%\&\/\(\)\=\?\<\>\,\;\.\:\-\_\~\^\{\}\+])*/?",
                                      'mensaje'   => ""
                                      /*NO PUEDE HABER UN COMENTARIO EN EL CAMPO /* */
            ],
            'seguridadGuiones'    => ['expresion' => "/^([A-Za-z0-9\._]|\-[A-Za-z0-9\._])*\-?$/",
                                      'mensaje'   => ""
                                      /*NO PUEDEN HABER DOS GUIONES CONSECUTIVOS --*/
            ],
            'fecha'               => ['expresion' => "/^\d{2,4}[\-|\/]{1}\d{2}[\-|\/]{1}\d{2,4}$/",
                                      'mensaje'   => 'El formato de fecha debe ser dd-mm-yyyy'
            ],
            'fechaHora'           => ['expresion' => '/^[1-9]{2}/[1-9]{2}[/|-][1-9]{2}:[1-9]{2}:[1-9]{2}$/',
                                      'mensaje'   => 'Formato de Fecha u hora incorrecto.'
            ],
            'limiteCaracteres'    => ['mensaje' => "La cadena no puede superar el total de caracteres permitidos"],
            'documentacion'       => ['expresion' => "/^(([V|v|E|e|G|g|J|j|P|p|N|n]{1})?\d{7,10})*$/",
                                      'mensaje'   => "El campo debe tener el siguiente formato J12345678 o 12345678"
            ]
        ];
    /**
     * Arreglo que registra los errores
     *
     * @var array $errors
     */
    private $errors = "";

    /**
     * Metodo Constructor
     * @method __construct
     *
     * @param string $cadena          Cadena a validar
     * @param mixed  $validacion      String o Array de validaciones a ejecutar a la cadena
     * @param array  $datosValidacion [opcional] Datos adicionales a ejecutar en validacion a tener en cuenta
     *
     */
    function __construct($cadena = "", $validacion = "", $datosValidacion = []) {

        $this->valorCampo = $cadena;
        $this->validacion = TRUE;
        $error = FALSE;

        if (is_array($validacion)) {

            for ($i = 0; $i < count($validacion); ++$i) {
                if (!$error):
                    if (!$this->validarCadena($validacion[ $i ], $datosValidacion)) {
                        $this->errors = $validacion[ $i ];
                        $this->validacion = FALSE;
                        $error = TRUE;
                        break;
                    }
                endif;
            }//fin for
        } else {

            if (!empty($cadena) and !$this->validarCadena($validacion, $datosValidacion)) {

                $this->validacion = FALSE;
                $this->errors = $validacion;
            } else {
                $this->validacion = TRUE;
            }
        }

    }

    function validarCadena($nombreValidacion, $datosValidacion) {

        if ($nombreValidacion == "decimal" or $nombreValidacion == "numerico") {
            $this->valorCampo = str_replace(".", "", $this->valorCampo);
            $this->valorCampo = str_replace(",", ".", $this->valorCampo);

        }
        if ($this->dataValidaciones[ $nombreValidacion ]['expresion'] != "") {

        } else {
            throw new Exception("Se llama a una expresion $nombreValidacion, la cual se encuentra indefinida", 1);

        }

        if (is_array($this->valorCampo)) {
            $band = TRUE;
            foreach ($this->valorCampo as $key => $value) {
                $result = preg_match($this->dataValidaciones[ $nombreValidacion ]['expresion'], $value);
                if ($result != 1) {
                    $band = FALSE;
                }
            }
            $resultValidacion = ($band === TRUE) ? TRUE : FALSE;
        } else {
            $resultValidacion = (preg_match($this->dataValidaciones[ $nombreValidacion ]['expresion'], $this->valorCampo)) ? TRUE : FALSE;
        }
        if ($resultValidacion === TRUE) {
            /**
             * En caso de que sea decimal o numerico se reemplaza el valor formateado por el requerido
             * para el registro en base de datos
             */
            return TRUE;
        } else {

            $this->obtenerMensajeError($nombreValidacion, $datosValidacion);

            return FALSE;
        }
    }

    /**
     * Obtiene el mensaje de error configurado para el campo
     *
     * @obtener mensaje de error
     */
    protected function obtenerMensajeError($validacion, $datos) {

        if (isset($datos['mensaje'])) {
            $this->mensajeError = $datos['mensaje'];
        } else {

            $this->mensajeError = "El campo " . $this->dataValidaciones[ $validacion ]['mensaje'];
        }
    }//fin función

    /**
     * Permite realizar validaciones a una cadena
     * @method validar
     *
     * @access public static
     *
     * @param mixed   $validacion nombre de la validacion o array de nombres
     * @param string  $cadena     Cadena a validar
     * @param boolean $mensaje    Define si se obtiene el mensaje de error o no.
     *
     * @return boolean
     */
    static function validar($validacion, $cadena, $mensaje = FALSE) {
        $validador = new Validador();
        if (is_array($validacion)) {
            $bandera = TRUE;
            foreach ($validacion as $key => $v) {
                $validador->valorCampo = $cadena;
                $bandera = $validador->validarCadena($v, ['mensaje' => $mensaje]);
            }

            return $bandera;
        } else
            return $validador->validarCadena($validacion, ['mensaje' => $mensaje]);
    }

    /**
     * Devuelve el valor del resultado de la valicación efectuada
     * @method getValidacion
     *
     * @return boolean see::Validador::validacion;
     */
    public function getValidacion() {
        return $this->validacion;
    }

    public function getErrors() {
        return $this->errors;
    }

}
