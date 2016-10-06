<?php
/**
* Helper para envio de correoos
 *
* @author Julio Rodriguez
* @package Framework
* @version 1.0 05/10/2016
* @category Helper
 *
*/
namespace JComponentes;
use App\Config as Config;
use Jida\Debug as Debug;
use \Directorios as Directorios;
require_once 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
class Correo{

	private $_default=[
		'Username'	=>'',
		'Password'	=>'',
		'From'		=>'',
		'FromName'	=>'',
		'Host'		=>'',
		'Port'		=>'',
		'SMTPSecure'=>'tls',
		'SMTPAuth' 	=>true
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
	private $_general=[];
	/**
	 * Data definida en el objeto \Config\Mail que puede ser pasada a cualquier correo
	 * @var array $_data
	 */
	private $_data=[];
	private $_error="";
	/**
	 * @var string $configuracion Nombre de la configuracion declarada en App\Config\Mail
	 * @see App\Config\Mail
	 *
	 * /
	private $configuracion;
	private $phpMailer;
    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($configuracion="index"){
		$this->configMail = new Config\Mail();

		if(!class_exists('App\Config\Mail'))
		{
			throw new \Exception("No se ha realizado la configuraci&oacute;n para el envio de correos", 1);

		}
		if(!property_exists($this->configMail, $configuracion))
		{
			throw new \Exception("La configuracion pasada no existe", 1);

		}

		$this->phpMailer = new \PHPMailer();
		if(property_exists($this->configMail, 'general'))
			$this->_configGeneral = $this->configMail->general;
		$this->configuracion = array_merge($this->_default,$this->_general,$this->configMail->{$configuracion});
		$this->checkConfiguracion();
    }
	private function checkConfiguracion(){

		foreach ($this->configuracion as $configuracion => $valor) {
			if(property_exists($this->phpMailer, $configuracion))
			{
				$this->phpMailer->{$configuracion} = $valor;
			}
		}
		if(property_exists($this->configMail, 'data') and is_array($this->configMail->data)){
			$this->_data = $this->configMail->data;
		}
        $this->phpMailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' 		=> false,
                'verify_peer_name' 	=> false,
                'allow_self_signed' => true
            ]
        ];

	}


	private function construirMail(){

		if(Directorios::validar(DIR_APP . $this->plantilla)){
			$plantilla = DIR_APP . $this->plantilla;
		}elseif(Directorios::validar(DIR_FRAMEWORK.'Layout/correo/'.$this->plantilla."tpl.php")){
			$plantilla = DIR_FRAMEWORK.'Layout/correo/'.$this->plantilla."tpl.php";
		}
		if(empty($plantilla)) throw new \Exception("No existe la plantilla de correo ".DIR_APP.$this->plantilla, 500);

		ob_start();
			include_once $this->plantilla;
		$content = ob_get_clean();
		foreach($this->_data as $data => $valor)
			$content = str_replace("{{{$data}}}",$valor,$content);

		return $content;

	}
	/**
	 * Define la plantilla de correo a usar
	 * @method plantilla
	 */
	function plantilla($tpl="index"){
		$this->plantilla = $this->pathPlantillas ."/". $tpl.".tpl.php";
		return $this;
	}
	/**
	 * Permite registrar las variables a pasar a la plantilla de correo
	 * @method data
	 * @param array $data Valores.
	 */
	function data($data){
		$data = array_merge($this->_data,$data);
    	foreach($data as $key=>$value){
            $this->_data[":".$key]=$value;
        }
		return $this;
	}
	/**
	 * Imprime los valores del proceso de envio
	 * @param int $numero Tipo de Debug, basado en los tipos de la clase PHPMAiler
	 *
	 */
	function debug($numero=1){

		$this->phpMailer->SMTPDebug=$numero;
		return $this;
	}
	/**
	 * Envia un correo a los destinatarios asignados
	 *
	 * El correo usara como plantilla la que se encuentre definida por el llamado previo al metodo $plantilla
	 * @method enviar
	 * @param mixed $destinatarios;
	 * @param string $asunto Titulo del correo
	 * @param array $mensaje Arreglo de valores a usar en la plantila, es opcional. los valores pueden pasarse por medio del metodo data
	 *
	 */
	function enviar($destinatarios,$asunto,$mensaje=[]){

		$this->phpMailer->IsHTML();
		$this->phpMailer->isSMTP();
		$html = $this->construirMail();
		if(!is_array($destinatarios)) $destinatarios = [$destinatarios];
		$enviado=true;
		if(is_array($destinatarios)){
			for($i=0;$i<=count($destinatarios)-1;++$i){
				$this->phpMailer->addAddress($destinatarios[$i],"");


				$this->phpMailer->msgHTML($html);
				if(!$this->phpMailer->send())
				{
					$this->_error =  "Error : ".$this->phpMailer->ErrorInfo;
					$enviado=false;
					break;
				}
			    $this->phpMailer->clearAddresses();
                $this->phpMailer->clearAttachments();

			}
		}//fin if
		return $enviado;
	}
	/**
	 * Retorna los errores obtenidos en el envio de correos
	 * @method obtError
	 * @return string
	 */
	private function obtError(){
		return $this->_error;
	}
}
