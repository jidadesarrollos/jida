(function($){
	function addSortable(){
    
     $("#listCamposFormulario > li").addClass('selecionable');
     $("#listCamposFormulario").sortable();
	
	}
	function guardarOrden(){
		
		var orden = $("#listCamposFormulario").sortable('toArray').toString();
				
		new jd.ajax({
		    url:"/jadmin/formularios/ordernar-campos/",
		    parametros:{'campos':orden,'ambito':$("#jidaConfiguracion").data('formulario')},
		    respuesta:'json',
		    funcionCarga:function(){
		    	
		        $("#jidaFormConfiguracion").html(this.respuesta);
		        
		        if(this.respuesta.ejecutado==true){
		        	
		            $("#jidaFormConfiguracion").html(this.respuesta.msj);
		            $("#listCamposFormulario > li").removeClass('selecionable');
		            $("#listCamposFormulario").sortable("destroy");
		            
		        }

		    }
		});
        return true;
	
	}//fin guardarOrden
	'use strict';
	$("#btnEditOrden").on('click',function(){
		$this = $( this );
		   
		if(this.value==1){
	   		
        	addSortable();
			$this.html("<span class=\"fa fa-save fa-lg\"></span> Finalizar").val(2);

		}else if(this.value==2){
	
	   		$(this)
	   			.val(1)
	   			.html('<span class=\"fa fa-edit fa-lg\"></span> Editar Orden');
	   		guardarOrden();
		}
	
	});
	
	$("#listCamposFormulario a").on('dblclick',function(e){
	
		var valorSeleccion = $( this ).data('id-campo');
		var accion 	= $( this ).attr('name');
		var $this 	= $( this );
		var $ul 	= $this.parent();
		var form	= $ul.data('form');
		var urlCall	= $ul.data('url');
		
		e.preventDefault();
	
		if(valorSeleccion){
		
			var parametros ={
				'accion': 2,
				'idCampo': valorSeleccion,
				'form': form
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
})(jQuery);
