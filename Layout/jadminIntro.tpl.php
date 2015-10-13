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

        <?=$this->printHeadTags()?>
        
        <?=$this->printCss()?>
    </head>

    <body>
        
     <div  class="jida-container">
         <div class="container-fluid">
             
             <div class="row">
                <nav id="nav-top" class="navbar bg-jida navbar-fixed-top">    
                    <a class="navbar-brand pull-right" href="#">Jida-Framework Desarrollo</a>
                </nav>
            </div>
            
                <div class="col-md-12 col-md-12 contenido-principal">
                    <!-- <div class="row"> -->
            
                     <?=$contenido?>
        
                    <!-- </div> -->
                    <!--Cierre col-lg-9 del contenido-->
                </div><!--Cierre col-lg-9 del contenido-->                    
                <div class="separador-footer">
                    
                </div>
            </div><!--Cierre div full-container-->
        </div>    
        <footer class="footer container-fluid">
            <p>
                &copy; Copyright  by jirc Prueba
            </p>
        </footer>
        
		<?=$this->printJS()?>
    </body>
</html>
