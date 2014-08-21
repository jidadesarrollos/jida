<?PHP 
/**
 * Clase para creacion y manejo de tablas
 * 
 * @author Julio Rodriguez
 */


class Table extends Selector{
    
    var $thead;
    var $tbody;
    /**
     * Arreglo de Filas
     * @var array $tr
     */
    var $tr;
    var $th;
    /**
     * Arreglo de columnas
     * @var array $td
     */
    var $td;
    
    var $titulos=TRUE;    
    private $totalTR;
    private $totalTD;
    /**
     * Data a renderizar en la Tabla HTML generada
     * @var array $dataArrayTabla
     */
    private $dataArrayTabla;
    /**
     * Data para Titulos a renderizar en la Tabla HTML generada
     * @var array $dataTitulos
     */
    private $dataTitulos;
    /**
     * Contiene la tabla HTML creada
     * @var string $tabla
     */
    private $tabla;
    /**
     * Funcion constructora de la tabla
     * @param array $tabla Datos para Crear la Tabla
     * @param array $titulos Datos para crear los titulos
     */
    function __construct(){
        $numParams = func_num_args();
        $this->selector="TABLE";
        $this->thead=new Selector('THEAD');
        $this->tbody=new Selector('TBODY');
        if($numParams==1 or $numParams==2){
            
        
            if(is_array(func_get_arg(0))){
                $this->dataArrayTabla=func_get_arg(0);
                $this->initTablaByArray();
                $this->agregarContenidoCeldas();
            }else{
                throw new Exception("No se ha instanciado correctamente la clase, debe ser pasado un arreglo", 1);
                
            }
            if($numParams==2){
                if(is_array(func_get_arg(1))){
                    $this->dataTitulos=func_get_arg(1);
                    $this->setThead();
                }
            }            
        }else{
            throw new Exception("Numero de parametros incorrectos para objeto Tabla", 1);
           
        }
        
        
    }//final constructor
    
    /**
     * Inicializa las columnas y filas de una tabla a partir de la estructura de un arreglo
     * @method initTablaByArray
     * @access private
     */
    private function initTablaByArray(){
        $this->totalTR=count($this->dataArrayTabla);
        $this->totalTD=count($this->dataArrayTabla[0]);
        /**
         * Se instancian los TD de forma individual para poder agregar atributos que sean globales
         * para TDs en multiples filas
         */
        for($i=0;$i<$this->totalTD;$i++){
            $this->td[$i]=new Selector('TD');
        }
        /**
         * Instanciación de filas y columnas internas
         */
        for($i=0;$i<$this->totalTR;$i++){
            $this->tr[$i]=new Selector('TR');
            for($a=0;$a<$this->totalTD;$a++){
                $this->tr[$i]->td[$a]=new Selector('TD');
                $this->tr[$i]->td[$a]->establecerAtributos(get_object_vars($this->td[$a]));
                #$this->tr[$i]->td[$a]->contenido=$this->dataArrayTabla[$i][$a];
            }//final construcción TDs
        }//final Construccion TDs
        
      
    }
    /**
     * Modifica las propiedades de una columna especifica
     * @method setColumna
     * @access public
     * @param int $col Numero de la columna a modificar
     * @param mixed $propiedad Propiedad a editar, puede ser un string si es una propiedad o un array si son varias
     * @param string $valor. si $propiedad es un string $valor sera el valor asignado a la propiedad
     */
    function setColumna($col,$propiedad,$valor){
       
        if(array_key_exists($col, $this->thead->tr->th)):
            
            $obj =&  $this->thead->tr->th[$col];
            if(is_array($propiedad)){
                foreach($propiedad as $key => $valor){
                    $obj->$key=$valor;
                }
            }else{
                
                $obj->$propiedad=$valor;    
            }
        endif;

       for($i=0;$i<$this->totalTR;$i++):
            #for($j=0;$j<$this->totalTD;$j++){
                
                if(is_array($propiedad)){
                    $obj =&  $this->tr[$i]->td[$col];
                    foreach($propiedad as $key => $valor){
                          $obj->$key=$valor;
                      }
                }else{
                    
                  $this->tr[$i]->td[$col]->$propiedad=$valor;
                } 
            #}//final for
        endfor;//final for
       }
    
    
    function getTotalColsAndCells(){
        #echo "Filas: ".$this->totalTR."<hr>";
        #echo "Cols: ".$this->totalTD;
    }
    
    private function instanciarColAndCells(){
        for($i=0;$i<$this->totalTR;$i++){
            $this->tr[$i]=new Selector('TR');
            for($a=0;$a<$this->totalTD;$a++){
                $this->td[$i]['td'][$a]=new Selector('TD');
            }//final construcción TDs
        }//final Construccion TDs
    }
    /**
     * Arma una tabla a partir de las filas y columnas recibidas
     */
    private function crearTablaConFilasYColumnas($filas,$cols){
        $tabla="";
        
        //echo $filas." $cols<hr>";
        for($i=0;$i<$filas;$i++){
            
            for($e=0;$e<=$cols;$e++){
                
                if($e==0)
                    $columnas="";
                $columnas.=self::crear('TH',null,"a");
            }
            $tabla.=self::crear('TR',null,$columnas);
        }
        return self::crear('table',array('border'=>1,'cellspacing'=>'5'),$tabla);
        
    }//final funcion
    /**
     * Inicializa objetos del encabezado de la tabla
     * @method setThead
     */
    private function setThead($arrayTitles=""){
        if(empty($arrayTitles)){
            $arrayTitles=& $this->dataTitulos;
        }
        
        $this->thead->tr = new Selector('TR');
        $this->thead->tr->th=array();
        for($i=0;$i<$this->totalTD;$i++){
            $this->thead->tr->th[$i]=new Selector('TH');
            $a =$this->thead->tr->th[$i];
            
            $this->thead->tr->th[$i]->contenido=$arrayTitles[$i];
        }
        
    }
    /**
     * Arma la estructura del encabezado de la tabla HTML a renderizar
     * @method getThead
     * @access private
     */
    private function getThead(){
         $thead="";
         $col =& $this->thead->tr;
         
         if(count($this->dataTitulos)>0){
             for($i=0;$i<$this->totalTD;$i++){
                 
                 $col->contenido.=$col->th[$i]->getSelector();
             }
             
             $thead = $this->thead->contenido=$col->getSelector();
             
             return $this->thead->getSelector();    
         }
         
         
    }
    /**
     * Devuelve la tabla creada
     * @method getTabla
     * @access public
     */
    function getTabla(){
        $content = "";
        
        if($this->titulos===TRUE){
            $content.=$this->getThead();
        }
        
        for($i=0;$i<$this->getTotalFilas();$i++){
            $contentTR="";
            
            $totalColumnas=$this->getTotalColumnas();
            for($j=0;$j<$totalColumnas;$j++){
                 if(array_key_exists($j, $this->tr[$i]->td)):       
                        $contentTR.=$this->tr[$i]->td[$j]->getSelector();
                 endif;
            }//fin for    
            $this->tr[$i]->contenido=$contentTR;
            $content.=$this->tr[$i]->getSelector();
            
        }//fin for
        
        $this->contenido=$content;
        
        $content = $this->getSelector();
        
        return $content;
        
    }
    private function agregarContenidoCeldas(){
        for($i=0;$i<$this->totalTR;$i++){
            $j=0;
            foreach ($this->dataArrayTabla[$i] as $key => $value) {
                $this->tr[$i]->td[$j]->contenido=$this->dataArrayTabla[$i][$key];
                ++$j;
            }
            
        }
    }
    function getTotalColumnas(){
        if(is_array($this->tr[0]->td)){
            $this->totalTD=count($this->tr[0]->td);
                
        }
        return $this->totalTD;
    }
    function getTotalFilas(){
        $this->totalTR=count($this->tr);
        return $this->totalTR;
    }
    /**
     * Agrega una columna a la tabla creada
     * @method addColumna
     * 
     */
    function addColumna($contenido,$posicion=""){
        if(empty($posicion)){
            $posicion=$this->totalTD;
        }
        for($i=0;$i<$this->totalTR;$i++){
            if(is_string($contenido)){
                $this->tr->td[$posicion]=new Selector('TD');
                $this->tr->td[$posicion]->contenido=$contenido;
            }
        }
    }
    
    
}


?>