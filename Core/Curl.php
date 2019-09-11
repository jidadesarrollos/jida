<?PHP
/**
 * Definición de la clase
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package
 * @category Controller
 * @version 0.1
 */

namespace Jida\Core;

use Jida\Core\Controlador\Control;
use Jida\Medios\Debug;

class Curl extends Control {
    /**
     * @var int $id Identificador de la llamada curl
     */
    private $id;

    private $cookiePath = "/path/";
    /**
     * Url a la que se realiza la llamada por medio del curl
     *
     * @var url
     */
    var $url;
    /**
     * LLamada curl
     *
     * @var $curl
     */
    var $curl;

    var $respuesta = "";

    /**
     * Funcion constructora para una llamada via curl
     *
     * @param $url
     * @method __construct
     */
    function __construct($url = "", $cookie = null) {

        $this->id = time();
        $this->url = $url;
        $this->curl = curl_init($url);
        $this->init($cookie);

    }

    /**
     * Inicializa todos los valores del cURL
     * @method init
     *
     * @param $cookie
     */
    private function init($cookie = null) {

        if ($cookie)
            $this->cookie = $cookie;
        else
            $this->cookie = $this->cookiePath . $this->id;

        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, ["Accept-Language: es-es,en"]);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
    }

    /**
     * Realiza una petición get
     * @method get
     */
    function get($params = [], $url = "", $follow = false) {

        $this->init();
        if (!empty($url))
            $this->url = $url;
        if (count($params) > 0)
            $this->url = $this->url . "?" . $this->setParams($params);

        if (!is_null($url) or count($params > 0)) {
            curl_setopt($this->curl, CURLOPT_URL, $this->url);
        }
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_HEADER, $follow);
        curl_setopt($this->curl, CURLOPT_REFERER, '');
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);
        $result = curl_exec($this->curl);
        if ($result === false) {
            echo $this->url;
            echo curl_error($this->curl);
        }
        $this->cerrarCurl();

        $this->respuesta = $result;

        return $this;
    }

    /**
     * Ejecuta una llamada curl via POST
     * @method llamadaPost
     *
     * @access public
     * @param array $params Arreglo asociativo de parametos post que se desean enviar en la petición.
     *
     */
    function post($params = [], $url = null, $follow = false, $header = false) {

        $this->init();
        $elements = [];
        foreach ($params as $name => $value) {
            $elements[] = "{$name}=" . urlencode($value);
        }
        $elements = join("&", $elements);

        if (!is_null($url)) {
            curl_setopt($this->curl, CURLOPT_URL, $url);
        }
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_REFERER, '');
        curl_setopt($this->curl, CURLOPT_HEADER, $header OR $follow);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $elements);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);
        $result = curl_exec($this->curl);
        $this->respuesta = $result;
        $this->cerrarCurl();

        return $this;

    }//fin funcón post

    /**
     * Ejecuta una llamada curl
     * @method call
     *
     * @param string $request Metodo de la llamada (get, post, put, delete, etc)
     * @param string $url Url de la llamada
     * @param array $params Arreglo de parametos que se desean enviar en la petición.
     *
     */
    function call($method, $url, $params = false) {

        $this->init();

        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($this->curl, CURLOPT_POST, 1);
                if ($params) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($params));
                }
                break;
            case "PUT":
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($params) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($params));
                }
                break;
            default:
                if ($params) {
                    $url = sprintf("%s?%s", $url, http_build_query($params));
                }
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $result = curl_exec($this->curl);
        $this->respuesta = $result;
        $this->cerrarCurl();

        return $this;

    }

    /**
     * Retorna la respuesta obtenida tal y como se recibe
     */
    function respuesta() {

        return $this->respuesta;
    }

    /**
     * Retorna la respuesta de la llamada cURL convertida en un objeto
     *
     */
    function arreglo() {

        return json_decode($this->respuesta, true, 512, JSON_BIGINT_AS_STRING);
    }

    function objeto() {

        return json_decode($this->respuesta);
    }

    /**
     * Realiza la llamada Curl
     * @method llamadaCurl
     *
     * @access private
     */
    private function llamadaCurl() {

        $response = curl_exec($this->curl);
        $this->cerrarCurl();
        var_dump($response);

    }

    /**
     * Cierra la llamada curl
     * @method cerrarCurl
     *
     * @access private
     */
    private function cerrarCurl() {

        curl_close($this->curl);
    }

    private function setParams($array) {

        $params = http_build_query($array);

        return $params;
    }
}

?>