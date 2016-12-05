
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
       $this = $( this );
       console.log(this.value);
       if(this.value==1){
            addSortable();
            console.log("aqui");
            $this.html("<span class=\"fa fa-save fa-lg\"></span> Finalizar").val(2);
       }else
       if(this.value==2){

           $(this).val(1).html('<span class=\"fa fa-edit fa-lg\"></span> Editar Orden');
           guardarOrden();
       }

   });
    $("#listCamposFormulario li").on('dblclick',function(e){

        var valorSeleccion = $( this ).data('id-campo');
        var accion 	= $( this ).attr('name');
        var $this 	= $( this );
        var $ul 	= $this.parent();
        var form	= $ul.data('form');
        var urlCall	= $ul.data('url');
        console.log($ul.data());
        e.preventDefault();

        if(valorSeleccion){

			parametros ={
				'accion':2,
				'idCampo' :valorSeleccion,
				'form':form
			};
			$.ajax({
				url : urlCall,
				method: 'post',
				dataType:'html',
				data: parametros
			}).done(function(data){
			$("#jidaFormConfiguracion").html(data);
			});
		}


    });
     if($('[data-selectall]').length>0){
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
