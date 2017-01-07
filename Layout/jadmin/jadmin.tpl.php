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

    <body class="fixed">
        
     <div  class="jida-container">
     	<nav class="navbar navbar-default navbar-fixed-top navbar-top">
			  <div class="container-fluid">
			    <div class="navbar-header">
			    	<a href="#" class="navbar-brand">
			    		<?php if (defined('LOGO_APP')): ?>
							<img src="<?=LOGO_APP?>" alt="<?=NOMBRE_APP?>"  class="logo-admin top-nav"/>
						<?php else: ?>
								<?=NOMBRE_APP?>	
						<?php endif ?>
			    		
			    	</a>
			    </div>
			  </div>
		</nav>
		<div id="content-wrapper" class="">
	         
	            
            <aside class="aside row-offcanvas-left">
            	
                    <?PHP 
                    
                    $menuControl  = new \Jida\RenderHTML\MenuHTML('Principal');
                    $menuControl->configuracion['ul'][0]=array("class"=>"nav nav-aside menu",'id'=>'step1','name'=>'step1');
                    $menuControl->configuracion['li'][0]=array('class'=>"li-parent",'data-liparent'=>'true');
                    $menuControl->configuracion['li']['caret']="li-caret";
                     echo $menuControl->showMenu();
                    ?>
                <hr />
                <ul class="nav nav-aside menu">
                	<li>
                		<a href="#" class="menu-toggle"><span class="fa fa-arrow-right"></span>
                			<span class="inner-text">Cerrar Men&uacute;</span>
                		</a>		
                	</li>
                
                </ul>
            </aside>
            <main class="main-panel">
            	<div class="container-fluid">
	            	
	            		<div class="row">
	            			<div class="col-md-12 col-xs-10">
	            				<?=$contenido?>			
	            			</div>
	            		</div>
	            	
             	</div>
            </main><!--Cierre col-lg-9 del contenido-->
                            
	            
	        
	    </div>
    </div>
        
         <?=$this->imprimirLibrerias('js','jadmin')?>
    </body>
</html>