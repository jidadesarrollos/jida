<?PHP 
/**
 * 
 */
 
class DebugController extends Controller{
    private $jctrl="";
    
    function __construct(){
        $this->jctrl = new JidaControl();
		$this->header= "query/pre_query.php";
		$this->footer= "query/footer_query.php";
    }      
    
      function query(){
      	$this->tituloPagina="JidaDesarrollos - Consulta BD";
        try{
            if(isset($_POST['ejecutarQuery'])){
                if(isset($_POST['consulta']) and !empty($_POST['consulta'])){
                    $data = $this->jctrl->consultarBD($_POST['consulta']);
                    
                    $tablas = "";
                    $arrayConsultas =  explode(";", $_POST['consulta']);
                    
                    $cont=0;
                    foreach ($data as $fila => $arrayData) {
                        #primer nivel
                        $i = 0;
                        $tablas .= "<table class=\"table-result table table-striped\">";
                        #entrando al arreglo de posiciones de los querys
                        if($arrayData['totalRegistros']>0){
                            foreach ($arrayData as $key => $valor) {
                                if(is_array($valor)){
                                    if($i==0){
                                        $nombreCols = array_keys($valor);
                                        $tablas.="<tr class=\"active\">
                                            <th colspan=\"".count($nombreCols)."\">
                                            ".$arrayConsultas[$cont]."
                                            </th></tr>";
                                        $tablas.="<tr>";
                                    
                                        #Armar titulo de las columnas de la consulta
                                        foreach ($nombreCols as $key) {
                                            
                                            $tablas.="<th>$key</th>";
                                        }
                                        $tablas.="</tr>";
                                    }
                                    #Recorriendo cada fila
                                    $tablas.="<tr>";
                                    foreach($valor as $campo => $valor){
                                        $tablas.="<td>$valor</td>";
                                    }//fin 4 foreach
                                    $tablas.="</tr>";
                                    $i++;
                                }//fin segundo foreach
                             }
                         }else{
                             $nombreCols=1;
                         }//fin validacion de registros
                         
                        $tablas.="<tr class=\"active\">
                                    <th colspan=\"".count($nombreCols)."\">
                                     TOTAL REGISTROS:   ".$arrayData['totalRegistros']."
                                    </th>
                                  </tr>";
                        $tablas .="</table>";
                        $cont++;
                    }//fin primer foreach
                    
                    $dataArray['resultQuery'] = $tablas;
                    
                }else{
                    throw new Exception("La consulta a base de datos esta vacia", 1);
                    
                }
            }
            $jctrl = new JidaControl();
            $tablasBD = $jctrl->obtenerTablasBD();
            $dataArray['tablasBD'] = $tablasBD;
            $this->data = $dataArray;
        }catch(Exception $e){
            controlExcepcion($e->getMessage(),$e->getCode());
            
        } 
        
    }
}
?>