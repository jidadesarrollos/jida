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

    <body class="fixed" data-url="<?=URL_BASE?>">
        
     <div  class="jida-container">
     	<?php include_once 'elementos/navbar.tpl.php'?>
		<div id="content-wrapper" class="short-menu">
   
            <aside class="aside row-offcanvas-left">
            	
                <?PHP 
                    $showMenu = (array_key_exists('showmenu', $_GET)  and $_GET['showmenu']=='true')?'long-menu 1':'short-menu';
                    
                    $menuControl  = new \Jida\RenderHTML\MenuHTML('Principal');
                    $menuControl->configuracion['ul'][0]=array("class"=>"nav nav-aside menu $showMenu",'id'=>'step1','name'=>'step1');
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