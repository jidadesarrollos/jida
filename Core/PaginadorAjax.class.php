<?PHP 

#=============================================================================
/*Realizado por: Julio Rodriguez
 * creado : 24-07-2012
 */
#==============================================================================
#require_once'includesJida.php';
class paginadorAjax{
    #---------------------------
    var $bdusada="";
    var $filas_pag=10;
    var $totalPaginas="";
    var $totalRegistros="";
    var $paginaActual=1;
    var $query="";
    var $inicio=0;
    var $numMostrado=6;
    var $s_paginador="paginador";
    #---------------------------
    var $s_linkActual="linkActual";
    var $s_linkPaginas="linkPaginas";
    var $parametros="";
    var $nextprev=TRUE;
    var $st_prev="prev";
    var $st_next="next";
    var $nextConten=">>";
    var $prevConten="<<";
    var $paginaPag="";
    var $gridAMostrar="";
    #---------------------------------------------------
    function __construct($query,$paginadora){
        
        #validar si se pasan parametros del paginador
        if(is_array($paginadora)){
            isset($paginadora['filas_pag'])?$this->filas_pag=$paginadora['filas_pag']:$this->filas_pag=10;
            isset($paginadora['numMostrado'])?$this->numMostrado=$paginadora['numMostrado']:$this->numMostrado=6;
            isset($paginadora['paginaPag'])?$this->paginaPag=$paginadora['paginaPag']:$this->paginaPag=$_SERVER['PHP_SELF'];
            isset($paginadora['parametros'])?$this->parametros=$paginadora['parametros']:$this->parametros='';
            isset($paginadora['gridAMostrar'])?$this->gridAMostrar=$paginadora['gridAMostrar']:$this->gridAMostrar='';
        }//final if
        $this->query=$query;
    }//final funcion constructora 
    #---------------------------------------------------
    function crearPaginador(){
    
        if(isset($_GET['pag'])){
            $this->paginaActual=$_GET['pag'];
        }//final if
        $cn= new c_query;
        $result = $cn->ejecutarQuery($this->query);
        $this->totalRegistros=$cn->totalRows($result);
        $this->totalPaginas=ceil($this->totalRegistros/$this->filas_pag);
        #echo $this->totalPaginas."<hr>$this->totalRegistros/$this->filas_pag<hr>";
        
        #-----------------------------------------------
        $numeroParcial=ceil(($this->numMostrado-1)/2);
        $band=1;
        $finPag=$this->totalPaginas;
        
        if($this->totalPaginas>$this->numMostrado){
            #-------------------------------------------
            if($this->paginaActual >1){
                $band= $this->paginaActual -$numeroParcial;
                if($band<=0)
                    $band=1;
                
            }else {
                $band=1;
            }//fin if
            #-------------------------------------------
        
        $finPag=$this->paginaActual+$numeroParcial;
        #-------------------------------------------
        if($finPag >= $this->totalPaginas)
            $finPag=$this->totalPaginas;
        #-------------------------------------------
        }//fin if
        $paginas="<div class=\"$this->s_paginador\">\n";
        #-----------------------------------------------
        //inicializo variables vacias por si no existen parametros
        $gets="";
        //Aqui capturo los parametros, envio a funcion que crear el javascript/Ajax. y los devuelvo en arreglo
        #---------------------------------------------------------
        if($this->parametros){
            $gets = $this->crearParametros($this->parametros);
        }//final if
        #---------------------------------------------------------
        echo $this->funcionJS($gets);
        if($this->nextprev==TRUE and $this->paginaActual>1){
                $link=$this->paginaActual-1;
                $paginas.="<a onClick=\"pagina($link)\" class='$this->st_prev'>$this->prevConten</a>";
            }
        #----------------------------------
        for($i=$band;$i<=$finPag;$i++){
            if($this->paginaActual==$i){
                $paginas.="<B class=\"$this->s_linkActual\">$i</b>";
            }else   
                $paginas.="<a onClick=\"pagina($i)\" class='$this->s_linkPaginas'>$i</a>";
        }//final for
        #----------------------------------
        if($this->nextprev==TRUE and $this->paginaActual<$this->totalPaginas){
                $link=$this->paginaActual+1;
                $paginas.="<a onClick=\"pagina($link)\" class='$this->st_next'>$this->nextConten</a>";
                
        }
        #-----------------------------------------------
        $paginas .="<span>P&aacute;gina $this->paginaActual de $this->totalPaginas</span>\n</div><!--Cierre Div Paginador-->";
        
        return $paginas;
    }//final funciion crearPAginador
    #---------------------------------------------------
    function queryPagina($q){
        if(isset($_GET['pag'])){
            $this->paginaActual=$_GET['pag'];
        }//final if
        $in = $this->paginaActual -1;
        
        
        $this->inicio=($in) * $this->filas_pag;
        $qpagina = $q." limit $this->filas_pag offset $this->inicio";
        return $qpagina;

    }//fin funcion queryPagina
    #---------------------------------------------------
    function crearParametros($get){

        $parametros=array();
        foreach ($get as $key => $value) {
            if($key!='pag'){
                array_push($parametros,array('nombre'=>$key,'valor'=>$value));
                    
            }//fin if
            
            
            //$parametros.=$value.",";
        }//fin foreach
#       array_push($parametros,$nombres,$valores);
        return $parametros;
        
    }//fin funcion crearParametros
    #---------------------------------------------------
    function funcionJS($gets){
        
        $datos="";
        
        if($gets){
            foreach ($gets as $key =>$value) {
                $values=array_values($value);
                $datos.="\"$values[0]=\"+encodeURI('$values[1]')+\"&\"+";
                    
        
            }//final foreach
            
        }//final if

        $funcion= "<SCRIPT type=\"text/javascript\">";
        if(isset($parametros))
            $funcion.="function pagina(pag){";
        else {
            $funcion.="function pagina(pag){";
        }
        $funcion.="
                datos=$datos\"pag=\"+encodeURI(pag);
                
                var \$oajax= new jdAjax.cargarAjax('".$this->paginaPag."?'+datos,'GET',function(ajax){
                            nodoTexto=this.obAjax.responseText;
                            
                            var panel =document.getElementById('panelC');
                            if(panel){
                                \$(\"#".$this->gridAMostrar."\").html(nodoTexto);
                            }else
                                \$(\"#".$this->gridAMostrar."\").html(nodoTexto);
                    });//fin funcion oajax
            
            }//fin funcion pagina
        
        </script>";
        return $funcion;
    }
}//final clase paginador


?>