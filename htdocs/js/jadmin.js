
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
       
   });
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
    });
     if($('[data-selectall]').size()>0){
    	$seleccionador = $('[data-selectall]');
    	$seleccionador.on('click',function(){
	    	console.log("click");
	    	var $this = $( this );
	    	var seleccion = $this.data('selectall');
			$( seleccion ).each(function(){
				this.checked=$this.prop('checked');
			});
	    	
	    });
	    $($seleccionador.data('selectall')).on('click',function(){
	    	
	    	if($($seleccionador.data('selectall')+':checked').lenght== $($seleccionador.data('selectall')).length )
	    	$seleccionador.prop('checked',true);
	    	else
	    	$seleccionador.prop('checked',false);
	    });
    }
    
});    
