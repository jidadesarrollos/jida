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
        <?=$this->imprimirLibrerias('css','jadmin')?>

        <link rel="shortcut icon" href="/htdocs/img/jIcon.jpg">
        <!--libs-->
    </head>

    <body>

     <div  class="jida-container">
     	<nav class="navbar navbar-default navbar-fixed-top">
			  <div class="container-fluid">
			    <div class="navbar-header">
			    	<a href="#" class="navbar-brand">
			    		JIDAFramework.
			    	</a>
			    </div>
			  </div>
		</nav>
         <div class="container-fluid">


            <div class="row">
	            <aside class="col-md-2 aside">
	                    <?PHP

	                    $menuControl  = new MenuHTML('Principal');
	                    $menuControl->configuracion['ul'][0]=array("class"=>"nav nav-aside");
	                    $menuControl->configuracion['li'][0]=array('class'=>"li-parent",'data-liparent'=>'true');
	                    $menuControl->configuracion['li']['caret']="li-caret";
	                     echo $menuControl->showMenu();
	                    ?>

	            </aside>
	            <main class="col-md-offset-2 col-lg-10 col-md-10 col-sm-12 col-xs-12 cp">
	                <!-- <div class="row"> -->

	                 <?=$contenido?>
	            </main><!--Cierre col-lg-9 del contenido-->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <a class="pull-right" href="#" data-jida="goback">P&aacute;gina Anterior</a>
                </div>
            </div>
            <div class="separador-footer"></div>
            </div><!--Cierre div full-container-->
        </div>

         <?=$this->printJS()?>
    </body>
</html>
