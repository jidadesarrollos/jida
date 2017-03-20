<?php
/**
 * Archivo Vista
 * @category Jida - view
 */
$data =& $this->data;
//Debug::mostrarArray($_POST);
?>
<div class="row">
	<div class="col-md-12">
	    <h1>Ajustes de imagen</h1>
	</div>
</div>
<article class="row">
	<section class="col-md-4 col-sm-4 col-xs-12">
	    
		<!-- <div class="col-md-12"> -->
		    <figure><img src="<?=$data->srcImagen?>" id="imgEnEdicion"alt="" height="300" class="<?=$data->data->classCss?>" data-id="<?=$data->data->id?>" data-inf='<?=json_encode($data->data)?>'/></figure>
		<!-- </div> -->
	</section>
	<section class="col-md-8 col-sm-8 col-xs-12">
        <fieldset>
            <form action="#" method="post">
                <div class="form-group">
                    <label for="txtLeyenda">Descripci&oacute;n</label>
                    <textarea name="txtLeyenda" id="txtLeyenda" class="form-control"><?=$data->data->descripcion?></textarea>
                </div>
                <div class="form-group">
                    <label for="txtAlternativo">Texto Alternativo</label>
                    <input type="text" class="form-control"  value="<?=$data->data->alt?>" id="txtAlternativo"/>
                </div>
                <div class="row">
                    
                    <fieldset class="col-md-6 col-sm-6 col-xs-12">
                        <legend>Alineaci&oacute;n</legend>
                        <div class="btn-group" role="group" data-toggle="buttons">
                            
                            <label for="align" class="btn btn-default">
                                <input type="radio" id="alignIzquierda" name="align" autocomplete="off" value="left" <?php if("left"==$data->align) echo "checked"?>/>
                                Izquierda
                            </label>
                            <label for="align" class="btn btn-default">
                                <input type="radio" id="alignCenter" name="align" autocomplete="off" value="center" <?php if("center"==$data->align) echo "checked"?>/>
                                Centrada
                            </label>
                            <label for="align" class="btn btn-default">
                                <input type="radio" id="alignDerecha" name="align" autocomplete="off" value="right" <?php if("right"==$data->align) echo "checked"?>/>
                                Derecha
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="col-md-6 col-sm-6 col-xs-12">
                        <legend>Estilo imagen</legend>
                        <div class="btn-group" role="group" data-toggle="buttons">
                            
                          <label for="classImg" class="btn btn-default">
                                <input type="radio" id="classImgNormal" name="classImg" autocomplete="off" <?php if($data->class=="img-post") echo "checked"?> value="img-post"/>
                                Normal
                            </label>
                            <label for="classImg" class="btn btn-default">
                                <input type="radio" id="classImgRounded" name="classImg" autocomplete="off" value="img-rounded" <?php if("img-rounded"==$data->class) echo "checked"?>/>
                                Rounded
                            </label>
                            <label for="classImg" class="btn btn-default">
                                <input type="radio" id="classImgThumbnail" name="classImg" autocomplete="off" value="img-thumbnail" <?php if("img-thumbnail"==$data->class) echo "checked"?>/>
                                Thumbnail
                            </label>
                            <label for="classImg" class="btn btn-default">
                                <input type="radio" id="classImgCircle" name="classImg" autocomplete="off" value="img-circle" <?php if("img-circle"==$data->class) echo "checked"?>/>
                                Circular
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div class="row">
                	<fieldset class="col-md-12 oculto" id="infoColImagen">
                	    
                	        <legend for="cols_imagen">Columnas</legend>
                	        <div class="btn-group" data-toggle="buttons">
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="1" name="nro_cols" id="nro_cols1" />1</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="2" name="nro_cols" id="nro_cols2" />2</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="3" name="nro_cols" id="nro_cols3" />3</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="4" name="nro_cols" id="nro_cols4" />4</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="5" name="nro_cols" id="nro_cols5" />5</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="6" name="nro_cols" id="nro_cols6" />6</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="7" name="nro_cols" id="nro_cols7" />7</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="8" name="nro_cols" id="nro_cols8" />8</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="9" name="nro_cols" id="nro_cols9" />9</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="10" name="nro_cols" id="nro_cols10" />10</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="11" name="nro_cols" id="nro_cols11" />11</label>
                	        	<label for="nro_cols" class="btn btn-default">
                	        	    <input type="radio" value="12" name="nro_cols" id="nro_cols12" />12</label>
                	        </div>
                	    
                	</fieldset>
                </div>  
            </form>
        </fieldset>	    
	</section>
</article>
<article class="row">
	<div class="col-md-12 col-sm-12 col-xs-12 top-30">
	    <button class="btn btn-primary pull-right" id="btnAjustarImagen">Ajustar Imagen</button>
	</div>
</article>
<script>
    $( document ).ready(function(){
        
        $tiny = $tiny = tinymce.get('contenido').dom.domQuery;
        var $this = $( this );
        var $img = $("#imgEnEdicion");
        var $imgData = $img.data('inf');
        
        var nuevoContenido,figcaption;
        
        $('label[for="classImg"]').on('click',function(){
           $this = $( this ).find('input');
           $img.removeClass().addClass('img-responsive '+$this.val());
        });
        
        $('input[type="radio"]:checked').parent().addClass('active');
        
        $('#btnAjustarImagen').on('click',ajustarImagen)
        
        //alineacion events
        $('[name="align"]').parent().on('click',function(){
            $this = $( this );
            $divCols=$("#infoColImagen");
            $radio = $this.find('input');
            if($radio.val()!='center'){
                
                if($divCols.hasClass('oculto')){
                    $divCols.removeClass('oculto').fadeIn();
                    $divCols.find('label').removeClass('active').find('input').prop('checked',false);
                    
                    $cols = $divCols.find('#nro_cols6').prop('checked',true).parent().addClass('active');    
                }else{
                    
                    if($('input[name="nro_cols"]:checked').size()<1){
                        $cols = $divCols.find('#nro_cols6').prop('checked',true).parent().addClass('active');
                    }
                }
                    
            }else{
                if(!$divCols.hasClass('oculto')){
                    $divCols.fadeOut().addClass('oculto');
                    
                    
                }
            }
        });
        $('[name="nro_cols"]').parent().on('click',function(){
            $this = $( this );
            $radio = $this.find('input');
             
        });
    })
    
    
    function createTinyElement(element,$valores){
        
        
    }
    /**
     * Se ejecuta al momento de finalizar la configuración de una imagen
     */
    function ajustarImagen(){
           var $img = $("#imgEnEdicion");
           var $imgData = $img.data('inf');
           var cssImg = $img.attr('class');
           var $class = $('input[name="align"]:checked');
           var $inputClass = $('input[name="classImg"]:checked');
           
           $figure = tinymce.get('contenido').$('#figureImg-'+$imgData.id);
           //$figure.removeClass().addClass($('input[name="align"]:checked').val())
           
           if($("#txtLeyenda").val()!=""){
                $imgData.descripcion=$.trim($("#txtLeyenda").val());
                figcaption='<figcaption>'+$("#txtLeyenda").val()+'</figcaption>';    
           }
           
           $imgData.classCss = $img.attr('class');
           $imgData.align=$class.val();
           if($inputClass.size()>0){
                console.log($inputClass.val()+" ac");  
            $imgData.classImg=$inputClass.val();
            }
           else
            $imgData.classImg="img-post";
           if($("#txtAlternativo").val()!=""){
                $imgData.alt =$("#txtAlternativo").val();     
                nuevoContenido = '<img src="'+$imgData.img+'" data-mce-src="'+$imgData.img+'" alt="'+$imgData.alt+'" data-inf=\''+JSON.stringify($imgData)+'\' class="'+cssImg+'"/>';    
           }else{
                nuevoContenido = '<img src="'+$imgData.img+'" data-mce-src="'+$imgData.img+'" data-inf=\''+JSON.stringify($imgData)+'\' alt="'+$("#txtAlternativo").val()+'" class="'+cssImg+'" />';
           }
           $figure.html(nuevoContenido+figcaption);
           $colFigure = $($figure.parent());
           $colLateral = $colFigure.siblings();
           
           
           if($class.val()!='center'){
               $cols = $('input[name="nro_cols"]:checked');
               if($cols.size()<1){
                $colFigure.removeClass().addClass('col-md-6 col-xs-12 col-sm-6');   
               }else{
                  var md,sm,xs=12,mdAlt,smAlt,xsAlt=12;
                  
                  md = $cols.val();
                  
                  if(md<=4){
                    sm=parseInt(md)+2;    
                    
                  }else if(md>8) sm=12
                  else{
                      sm=md;
                  }
                  mdAlt=12-parseInt(md);
                  smAlt=(sm==12)?sm:12-parseInt(sm);
                  $colFigure.removeClass().addClass('col-md-'+md+' col-xs-'+xs+' col-sm-'+sm);
               }
                
                if($colLateral.size()>0) 
                    $divTxtAlternativo=$colFigure.siblings();
                else 
                    $divTxtAlternativo = $("<div/>").addClass('col-md-'+mdAlt+' col-xs-'+xsAlt+' col-sm-'+smAlt).html("<p>Texto Lateral</p>");
                
                if($class.val()=='left'){
                    $divTxtAlternativo.removeClass().addClass('col-md-'+mdAlt+' col-xs-'+xsAlt+' col-sm-'+smAlt).insertAfter($colFigure);    
                }else{
                    $divTxtAlternativo.insertBefore($colFigure);
                }
           }else{
               console.log($colLateral.size()+" de texto lateral");
               $colFigure.removeClass().addClass('col-md-12 col-sm-12 col-xs-12');
               if($colLateral.size()>0){
                   
                   $p = $("<p>").html($colLateral.html());
                   $colLateral.remove();
                   $p.insertAfter($colFigure.parent());
               }
           }
           bootbox.hideAll();
       };
</script>