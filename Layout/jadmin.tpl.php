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

        <title>
            <?PHP echo $dataArray['title'];?>
        </title>
        <meta name="description" content=""  charset="utf-8">
        <meta name="author" content="jirc">
        <link href="/htdocs/css/bootstrapDefault.css" rel="stylesheet">
        <link href="/htdocs/css/f-a.css" rel="stylesheet">
        <link href="/htdocs/css/estiloDefault.css" rel="stylesheet">
        <link href="/htdocs/css/jida.css" rel="stylesheet">
        <link href="/htdocs/css/jida-common.css" rel="stylesheet">
        <link rel="stylesheet/less" type="text/css" href="/htdocs/less/menu.less">
        <link rel="shortcut icon" href="/htdocs/img/jIcon.jpg">         
        <!--libs-->
        <script src="/htdocs/js/libs/jq2.0.3.js"></script>
        <script src="/htdocs/js/libs/jqui1.10.3.js"></script>
        <script src="/htdocs/js/libs/bootstrap.min.js"></script>
        <script src="/htdocs/js/libs/bootbox.min.js"></script>
        <script src="/htdocs/js/libs/tipsy.js"></script>
        <script src="/htdocs/js/libs/ajaxupload.js"></script>
        <script src="/htdocs/js/libs/less.js"></script>
        <!--jidalibs-->
        <script src="/htdocs/js/jida/objetoAjax.js"></script>
        <script src="/htdocs/js/jida/validadorJida.js"></script>
        <script src="/htdocs/js/jida/jidaPlugs.js"></script>
        <script src="/htdocs/js/jida/jidaControlCampos.js"></script>
        <!--custom files-->
        <script src="/htdocs/js/funcionesGenerales.js"></script>
        
    </head>

    <body>
        
     <div  class="jida-container">
         <div class="container-fluid">
             
             <div class="row">
                <nav id="nav-top" class="navbar bg-jida navbar-fixed-top">    
                    <a class="navbar-brand pull-right" href="#">Jida-Framework Desarrollo</a>
                </nav>
            </div>
            <aside class="col-md-2 aside">
                    <?PHP 
                    
                    $menuControl  = new MenuHTML('principal');
                    $menuControl->configuracion['ul'][0]=array("class"=>"nav nav-aside");
                    $menuControl->configuracion['li'][0]=array('class'=>"li-parent",'data-liparent'=>'true');
                    $menuControl->configuracion['li']['caret']="li-caret";
                     echo $menuControl->showMenu();
                    ?>
                
            </aside>
                <div class="col-md-offset-2 col-lg-10 col-md-10 contenido-principal">
                    <!-- <div class="row"> -->
            
                     <?=$contenido?>
        
                    <!-- </div> -->
                    <!--Cierre col-lg-9 del contenido-->
                </div><!--Cierre col-lg-9 del contenido-->                    
                <div class="separador-footer">
                    
                </div>
            </div><!--Cierre div full-container-->
        </div>                
        <?PHP
        
        if(entorno_app =='dev'){
          #  echo debug();
        }
        ?>    
        <footer class="footer container-fluid">
            <p>
                &copy; Copyright  by jirc Prueba
            </p>
        </footer>
        

    </body>
</html>
