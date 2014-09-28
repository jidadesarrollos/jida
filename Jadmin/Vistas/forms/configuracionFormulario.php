<?PHP 
 /**
 * 
 */
 $data =& $dataArray;
?>
<style>
    textarea{
     
        height:150px;     
        width:100%;
        resize: vertical;
    }
    .enlace-form{
        position:relative;
        clear:both;
    }
</style>
<h1>Configuracion de Formularios...</h1>
<div class="alert alert-info">
    <h3>Ten en cuenta:</h3>
    <ol>
        <li><strong>Si deseas editar el orden de los campos</strong> Haz click en el bot&oacute;n <strong>Editar Orden</strong> que se
            encuentra al final de la lista de campos y luego haciendo click en cada campo colocalo en la posicion que deseas.</li>
        <li><strong>Si deseas editar la conf. del campo</strong> Debes hacer <i>doble click</i> sobre el elemento</li>
    </ol>
</div>
<?PHP
    echo Mensajes::imprimirMsjSesion();
?>

<article id="jidaConfiguracion" data-formulario="<?=$data['formFramework']?>">
    
    <div class="row">
        <section id="jidaCampos" class="col-lg-3">
            
            <div class="row top-15">
              
                <div class="col-md-6">
                    <h4>Campos</h4>        
                </div>
                  <div class="col-md-6">
                    <button id="btnEditOrden" title="editar orden" class="btn btn-primary pull-right" value="1">
                        <span class="fa fa-edit fa-lg"></span>Editar orden</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    
                    
                    <ul class="list-form-item" id="listCamposFormulario" data-form="<?=$data['formFramework']?>">
                     
                    <?PHP foreach($data['camposFormulario'] as $key =>$campo):?>
                        <li id="campoform-<?=$campo['id_campo']?>" data-id-campo="<?=$campo['id_campo']?>"><?=$campo['name']?></li>
                    <?PHP endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>
        <section id="jidaFormConfiguracion" class="col-lg-9">
            <?PHP 
                if(isset($dataArray['formCampo'])){
                    echo $dataArray['formCampo'];
                }
            ?>
        </section>
     </div>
     
     
</article>
<script>

function addSortable(){
     $("#listCamposFormulario > li").addClass('selecionable');
     $("#listCamposFormulario").sortable();
}


function guardarOrden(){
        var orden = $("#listCamposFormulario").sortable('toArray').toString();
            
        data = "s-ajax=true&campos="+orden;
        
        
        new jd.ajax({
            url:"/jadmin/forms/ordernar-campos/",
            parametros:{'campos':orden,'ambito':$("#jidaConfiguracion").data('formulario')},
            respuesta:'json',
            funcionCarga:function(){
                $("#jidaFormConfiguracion").html(this.respuesta);
                if(this.respuesta.ejecutado==true){
                    $("#jidaFormConfiguracion").html(this.respuesta.msj);
                    $("#listCamposFormulario > li").removeClass('selecionable');
                    $("#listCamposFormulario").sortable("destroy");
                }else{
                    
                }
            }
        });
        return true;
        
}//fin guardarOrden

$( document ).ready(function(){
   $("#btnEditOrden").on('click',function(){
       if(this.value==1){
        addSortable();
        $(this).html("<span class=\"fa fa-save fa-lg\"></span> Finalizar").val(2);        
       }else if(this.value==2){
           
           $(this).val(1).html('<span class=\"fa fa-edit fa-lg\"></span> Editar Orden');
           guardarOrden();
       }
       
   })
    $("#listCamposFormulario li").on('dblclick',function(){
        
        var valorSeleccion = $( this ).data('id-campo');
        var accion = $( this ).attr('name');
        var form =  $( "#listCamposFormulario" ).data('form');
        if(valorSeleccion){
            
            data = "accion=2&idCampo="+encodeURIComponent(valorSeleccion)+"&form="+form;
            var jdajax = new jd.ajax(
                {
                    url:'/jadmin/forms/configuracion-campo/',
                    metodo:'POST',
                    respuesta:"html",
                    funcionCarga:   function(ajax){
                        nodoTexto=this.obAjax.responseText;
                        $("#jidaFormConfiguracion").html(nodoTexto);
                    },
                    parametros:data,    
                });
        }
        return false;
    })
    
})    
</script>

