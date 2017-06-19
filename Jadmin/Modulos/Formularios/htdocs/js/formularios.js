(function($) {
	'use strict';

    const URL_BASE = $('body').data('url');
	var $container = $('#jidaGestionCampos');

	var $listaCampos = $container.find('#listaCamposFormulario');
	var $formContainer = $container.find('#formCampos');
	var $btnOrden = $container.find('#btnEditOrden');

	
	function addSortable() {
		
		
		$listaCampos.find('li').addClass('selecionable');
		$listaCampos.sortable();

	}

	function guardarOrden() {

		var orden = $listaCampos.sortable('toArray');
		var listadoOrdenado={};
		orden.forEach(function(item,key){
			
			var $elemento = $('#'+item);
			if($elemento.data('campo')!=undefined){
				listadoOrdenado[$elemento.data('campo')] = key;
				
			}
			
		});
		var parametros = {'campos':listadoOrdenado,'formulario':$container.data('formulario')};
		
		$.ajax({
			url : URL_BASE + "jadmin/formularios/campos/ordenar/",
			method : 'post',
			dataType : 'json',
			data : parametros
		}).done(function(r) {

			$("#jidaFormConfiguracion").html(r.msj);

			if (r.ejecutado == true) {

				$formContainer.html(r.msj);
				$listaCampos.fin('li').removeClass('selecionable');
				$listaCampos.sortable("destroy");

			}
		}).fail(function(r){
			console.log("error?",r);
		});

		return true;
	}//fin guardarOrden

	var ordenar = function(e){
		
		var $this = $( this );
		if(this.value==1){
	   		
        	addSortable();
			$this.html("<span class=\"fa fa-save fa-lg\"></span> Finalizar").val(2);

		}else if(this.value==2){
	
	   		$(this)
	   			.val(1)
	   			.html('<span class=\"fa fa-edit fa-lg\"></span> Editar Orden');
	   		guardarOrden();
		}
	
	};
	var abrirFormulario = function(e) {
		
		e.preventDefault();
		var valorSeleccion = $(this).data('campo');
		var accion = $(this).attr('name');
		var $this = $(this);
		var $ul = $this.parent();
		var form = $container.data('formulario');
		var urlCall = $ul.data('url');

		if(URL_BASE){
			urlCall = URL_BASE + urlCall;
		}
		e.preventDefault();

		if (valorSeleccion) {

			var parametros = {
				'accion' : 2,
				'idCampo' : valorSeleccion,
				'form' : form
			};
			$.ajax({

				url : urlCall,
				method : 'post',
				dataType : 'html',
				data : parametros

			}).done(function(data) {
				$("#formCampos").html(data);
			});
		}
	};
	
	$btnOrden.on('click',ordenar);
	
	$listaCampos.find('a').on({
		'dblclick': abrirFormulario,
		'click': function (e) {
			e.preventDefault();
		}
	});

})(jQuery);
