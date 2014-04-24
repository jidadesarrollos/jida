
<h1>Configuracion de Formularios...</h1>
<?PHP
    echo Mensajes::imprimirMsjSesion();
?>
<style>
    
   
    #jidaCampos li:hover{
        cursor:pointer;
        font-weight: bolder;
    }
   
    textarea{
        min-width: 500px;
        max-width:500px;
        height: 100px;
    }
    .enlace-form{
        position:relative;
        clear:both;
    }
</style>
<article id="jidaConfiguracion">
    <div class="row">
        <section id="jidaCampos" class="col-lg-3">
          <?= $dataArray['vistaCampos']?>
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
$( document ).ready(function(){
    
    $("#listaCampos li").on('click',function(){
        
        var valorSeleccion = $( this ).data('id-campo');
        var accion = $( this ).attr('name');
        if(valorSeleccion){
            data = "accion=2&idCampo="+encodeURIComponent(valorSeleccion);
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

