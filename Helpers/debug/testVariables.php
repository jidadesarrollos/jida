<?PHP 
/**
 * Muestra todas las variables en el entorno al cargar la página
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 */

?>

<style>
    .debug{
        font-size:12px;
        padding:20px;
    }
    .debug h4{
        font-size:14px;
        margin-bottom: 20px;
    }
    .test-var-class{
        padding:10px;
    }
    .test-var-class:first-child{
        background:rgba(25,107,214,.2);
    }
     
    .test-var-class:nth-child(3){
        background:rgba(158,247,220,.4);
    }
    .test-var-class:nth-child(4){
        background:rgba(242,174,192,.8);
    }
    .test-var-class:nth-child(2){
        background:rgba(255,89,56,.4);
    }
    table th{
        
        
        font-weight: bolder;
        width: 200px;
    }
    .debug table th:first-letter{
        text-transform: uppercase;
    }
    .debug table *{
        padding:10px;
    }
    .debug table tr:hover{
        background:#f5f5f5;
    }
</style>
<script>
    $( document ).ready(function(){
        $(".debug").hide();
        $("#showVariables").on('click',function(){
            $(".debug").toggle();    
        })
        $("[data-link=consulta]").on('click',function(){
           window.open("/jadmin/debug/query");
        })
    })
</script>
<?PHP 
    $variablesArray = 
    array(
        "post"=>array(),
        "get"=>array(),
        "session"=>array(),
   //     "globals"=>array()
        );
    $band = 0;
    $posiciones=array('post','get','session','globals');
    if(isset($_SESSION)){
        if(count($_SESSION)>0){
            $variablesArray['session'] = $_SESSION;
            $band++;
        }       
    }
    if(isset($_POST)){
        if(count($_POST)>0){
            $variablesArray['post'] = $_POST;
            $band++;
        }
    }
    if(isset($_GET)){
        if(count($_GET)>0){
            $variablesArray['get'] = $_GET;
            $band++;
        }
    }
    $contentVariables = "";
    $lg = ceil(12/$band);
    foreach ($posiciones as $key => $value) {
        if(!empty($variablesArray[$value])){
            $contentVariables.="<div class=\"col-lg-". $lg ." test-var-class\" >
                                    <h4>VARIABLES ".strtoupper($value)."</h4>";
            $contentVariables.="<table>\n\t";
            foreach ($variablesArray[$value] as $key => $value) {
                $contentVariables.="\n\t\t\t\t
                                    <tr>
                                        <th>$key</th>";
                if(is_array($value)){
                    $valor="";
                    $i=0;
                    foreach($value as $key=>$val){
                        if($i>0) $valor.="<br>";
                        if(is_array($val))
                        $valor.=@"$key => $val";
                        $i++;
                    }
                    $value=$valor;
                }
                $contentVariables.="\n\t\t\t\t\t<td>$value</td>
                                    </TR>";
            }//fin foreach
            $contentVariables.="</table>";
            $contentVariables.="</div>";
        }
    }
    $variables  = "
    <p>
        <button name=\"showVariables\" id=\"showVariables\">+</button>
        <button class=\"button primary\" data-link=\"consulta\">Consulta BD</button>
    </p>
    <div class=\"row debug\">
            $contentVariables
    </div>
    ";
    echo $variables;
?>