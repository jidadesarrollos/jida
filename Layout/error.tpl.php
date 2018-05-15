<?PHP 
/**
 * Layout por defecto para modulo jadmin del framework
 * @author Julio Rodriguez
 */


?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">

        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
        Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <?=$this->imprimirMeta();?>

        <?=$this->imprimirLibrerias('css',$this->moduloCss)?>
    </head>

    <body>
        
     <div  class="jida-container">
         <div class="container">
             
             <div class="row">
            
                <div class="col-md-12 col-md-12 contenido-principal">
                    <!-- <div class="row"> -->
            			
                     <?=$contenido?>
        
                    <!-- </div> -->
                    <!--Cierre col-lg-9 del contenido-->
                </div><!--Cierre col-lg-9 del contenido-->
            </div>                    
                <div class="separador-footer">
                    
                </div>
            </div><!--Cierre div full-container-->
        </div>
		<?=$this->printJS($this->moduloJS)?>
    </body>
</html>
