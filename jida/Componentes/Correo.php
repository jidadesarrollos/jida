<?php
/**
 * Helper para envio de correos
 *
 * @author Julio Rodriguez
 * @package Framework
 * @version 1.0 05/10/2016
 * @category Helper
 *
 */

namespace Jida\Componentes;

use App\Config as Config;
use Jida\Helpers\Directorios as Directorios;
use Jida\Manager\Estructura;

$path = Estructura::path();

require_once $path . '/Framework/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

class Correo {

    /**
     * Arreglo de configuracion para liberia PHPMailer
     * @var array $_default
     *
     * Declarar SMTPDebug para depurador de envio de correos. 2 flujo con errores - 4 corrida completa
     *
     * SMTPSecure para protocolo de envios, verificar en la configuracion (tls o ssl)
     *
     */
    private $_default = [
        'Username'      => '',
        'Password'      => '',
        'From'          => '',
        'FromName'      => '',
        'Host'          => '',
        'Port'          => '',
        'SMTPSecure'    => 'tls',
        'SMTPAuth'      => true,
        'AddAttachment' => []
        //'SMTPDebug'	=> 4

    ];
    /**
     * Define la ubicacion de las plantillas para correo
     * @var string $pathPlantillas
     */
    var $pathPlantillas = 'Vistas/correo';
    /**
     * Clase Mail Definida en la carpeta de Config
     * @var object $configMail
     */
    private $configMail;
    /**
     * Configuracion general a usar independientemente de la configuracion pedida para el envio de correo
     * @var array $_general
     *
     */
    private $_general = [];
    /**
     * Data definida en el objeto \Config\Mail que puede ser pasada a cualquier correo
     * @var array $_data
     */
    private $_data = [];
    private $_error = "";

    /**
     * @var string $configuracion Nombre de la configuracion declarada en App\Config\Mail
     * @see App\Config\Mail
     *
     * /
     * private $configuracion;
     * private $phpMailer;
     * /**
     * Funcion constructora
     * @method __construct
     */
    function __construct ($configuracion = "index") {

        $this->configMail = new Config\Mail();

        if (!class_exists('App\Config\Mail')) {
            throw new \Exception("No se ha realizado la configuraci&oacute;n para el envio de correos", 1);
        }

        $this->phpMailer = new \PHPMailer();
        $this->phpMailer->CharSet = 'UTF-8';

        if (property_exists($this->configMail, 'general'))
            $this->_configGeneral = $this->configMail->general;

        $this->config($configuracion);
        $this->checkConfiguracion();
    }

    private function checkConfiguracion () {

        foreach ($this->configuracion as $configuracion => $valor) {
            if (property_exists($this->phpMailer, $configuracion)) {
                $this->phpMailer->{$configuracion} = $valor;
            }
        }
        if (property_exists($this->configMail, 'data') and is_array($this->configMail->data)) {
            $this->_data = $this->configMail->data;
        }
        $this->phpMailer->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            ]
        ];
    }

    private function construirMail () {

        if (Directorios::validar(DIR_APP . $this->plantilla)) {
            $plantilla = DIR_APP . $this->plantilla;
        }
        else if (Directorios::validar(DIR_FRAMEWORK . DS . 'Layout/correo/' . $this->plantilla . "tpl.php")) {
            $plantilla = DIR_FRAMEWORK . DS . 'Layout/correo/' . $this->plantilla . "tpl.php";
        }
        if (empty($plantilla))
            throw new \Exception("No existe la plantilla de correo " . DIR_APP . $this->plantilla, 500);

        ob_start();
        include_once $this->plantilla;
        $content = ob_get_clean();

        foreach ($this->_data as $data => $valor)
            $content = str_replace("{{{$data}}}", $valor, $content);

        return $content;

    }

    /**
     * Define la plantilla de correo a usar
     * @method plantilla
     */
    function plantilla ($tpl = "index") {

        $this->plantilla = $this->pathPlantillas . "/" . $tpl . ".tpl.php";

        return $this;
    }

    /**
     * Permite registrar las variables a pasar a la plantilla de correo
     * @method data
     * @param array $data Valores.
     */
    function data ($data) {

        $data = array_merge($this->_data, $data);
        foreach ($data as $key => $value) {
            $this->_data[":" . $key] = $value;
        }

        return $this;
    }

    /**
     * Imprime los valores del proceso de envio
     * @param int $numero Tipo de Debug, basado en los tipos de la clase PHPMailer
     *
     */
    function debug ($numero = 1) {

        $this->phpMailer->SMTPDebug = $numero;

        return $this;
    }

    /**
     * Envia un correo a los destinatarios asignados
     *
     * El correo usara como plantilla la que se encuentre definida por el llamado previo al metodo $plantilla
     * @method enviar
     * @param mixed $destinatarios ;
     * @param string $asunto Titulo del correo
     * @param array $mensaje Arreglo de valores a usar en la plantila, es opcional. los valores pueden pasarse por
     *     medio del metodo data
     *
     */
    function enviar ($destinatarios, $asunto, $mensaje = []) {

        $this->phpMailer->IsHTML();
        $this->phpMailer->isSMTP();
        $html = $this->construirMail();
        $this->phpMailer->Subject = $asunto;

        if (!is_array($destinatarios))
            $destinatarios = [$destinatarios];

        $enviado = true;
        if (is_array($destinatarios)) {
            for ($i = 0; $i <= count($destinatarios) - 1; ++$i) {

                $this->phpMailer->msgHTML($html);
                $this->phpMailer->addAddress($destinatarios[$i], "");

                if (is_array($this->_default['AddAttachment'])) {
                    foreach ($this->_default['AddAttachment'] as $adjunto) {
                        $this->phpMailer->addAttachment($adjunto);
                    }
                }

                if (!$this->phpMailer->send()) {
                    $this->_error = "Error : " . $this->phpMailer->ErrorInfo;
                    $enviado = false;
                    break;
                }
                $this->phpMailer->clearAddresses();
                $this->phpMailer->clearAttachments();

            }
        }//fin if
        return $enviado;
    }

    /**
     * Edita el valor de configuracion para envio de correos
     * @method config
     * @param string $var Configuracion a usar, el valor pasado debe estar definido
     * como propiedad de la clase App\Config\Mail
     * @throws Excepion
     */
    function config ($configuracion) {

        if (!property_exists($this->configMail, $configuracion)) {
            throw new \Exception("La configuracion pasada no existe", 1);
        }

        $this->configuracion = array_merge($this->_default, $this->_general, $this->configMail->{$configuracion});
    }

    /**
     * Retorna los errores obtenidos en el envio de correos
     * @method obtError
     * @return string
     */
    function obtError () {

        return $this->_error;
    }

    /**
     * Agrega un archivo adjunto para el envio de correo
     * @method agregarAdjunto
     */
    function agregarAdjunto ($archivo, $ruta) {

        array_push($this->_default['AddAttachment'], $ruta . $archivo);

    }
}
