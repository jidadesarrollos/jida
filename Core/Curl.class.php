<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package 
 * @category Controller
 * @version 0.1
 */

 
class Curl extends Controller{
    /**
     * @var int $id Identificador de la llamada curl
     */
    private $id;
    
    private $cookiePath="/path/";
    /**
     * Url a la que se realiza la llamada por medio del curl
     * @var url
     */
    var $url;
    /**
     * LLamada curl
     * @var $curl
     */
    var $curl;
    
	
	var $respuesta="";
    /**
     * Funcion constructora para una llamada via curl
     * @param $url
     * @method __construct
     */
    function __construct($url="",$cookie=null){
        $this->id = time();
        $this->url=$url;
        $this->curl = curl_init($url);
        $this->init($cookie);
   
    }
    /**
     * Inicializa todos los valores del cURL
     * @method init
     * @param $cookie
     */
    private function init($cookie=null){
       if($cookie)  
            $this->cookie = $cookie;  
        else  
            $this->cookie = $this->cookiePath . $this->id;
        
        curl_setopt($this->curl, CURLOPT_HEADER, false);  
        curl_setopt($this->curl, CURLOPT_COOKIEFILE,$this->cookie);  
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Accept-Language: es-es,en"));  
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie);  
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);  
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);  
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, TRUE);   
    }
    /**
     * Realiza una petición get
     * @method get 
     */
    function get($params=array(),$url="",$follow=false){
    	
        $this->init();
		if(!empty($url)) $this->url = $url;
		if(count($params)>0) $this->url= $this->url."?".$this->setParams($params);
		
		if(!is_null($url) or count($params>0)){
            curl_setopt($this->curl, CURLOPT_URL, $this->url);
        }
        curl_setopt($this->curl, CURLOPT_URL, $this->url);  
        curl_setopt($this->curl, CURLOPT_POST,false);  
        curl_setopt($this->curl, CURLOPT_HEADER, $follow);  
        curl_setopt($this->curl, CURLOPT_REFERER, '');  
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);  
        $result=curl_exec ($this->curl);  
        if($result === false){
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
     * @access public
     * @param array $params Arreglo asociativo de parametos post que se desean enviar en la petición.
     * 
     */
    function post($params=array(),$url=null,$follow=false,$header=false){
        
        $this->init();  
        $elements=array();  
        foreach ($params as $name=>$value) {  
            $elements[] = "{$name}=".urlencode($value);  
        }  
        $elements = join("&",$elements);
		  
        if(!is_null($url)){
            curl_setopt($this->curl, CURLOPT_URL, $url);    
        }          
        curl_setopt($this->curl, CURLOPT_POST,true);  
        curl_setopt($this->curl, CURLOPT_REFERER, '');  
        curl_setopt($this->curl, CURLOPT_HEADER, $header OR $follow);  
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $elements);  
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);  
        $result=curl_exec ($this->curl);
        $this->respuesta=$result;
        $this->cerrarCurl();
		        
		return $this;
        
		
    }//fin funcón post
    /**
	 * Retorna la respuesta obtenida tal y como se recibe
	 */
    function respuesta(){
    	return $this->respuesta;
    }
	/**
	 * Retorna la respuesta de la llamada cURL convertida en un objeto
	 * 
	 */
	function arreglo(){
		
		return json_decode($this->respuesta,true, 512, JSON_BIGINT_AS_STRING);
	}
	function objeto(){
		
		return json_decode($this->respuesta);
	}
    
    /**
     * Realiza la llamada Curl
     * @method llamadaCurl
     * @access private
     */
    private function llamadaCurl(){
        $response = curl_exec($this->curl);
        $this->cerrarCurl();
        var_dump($response);
         
    }
    /**
     * Cierra la llamada curl
     * @method cerrarCurl
     * @access private
     */
    private function cerrarCurl(){
        curl_close($this->curl);
    }
    private function setUrl($url,$params=array()){
    	$this->url = $url;
		if(count($params>0)){
			
		}
    }
	private function setParams($array){
		$params = http_build_query($array);
		return $params;
	}
}   
?>