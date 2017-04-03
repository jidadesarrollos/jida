jd.ajax = function(params,callback){
	$.ajax(params.data).done(params.done);
};


(function(){
	console.log("modulo galeria");
	//================================================
	// Formulario de galeria
	//================================================
	$('.jida-galeria-media').on('click','[data-galeria]',function(){
		
		var $this = $( this );
		
		jd.ajax({
			data:{
				url:'/jadmin/galeria/gestion-media',
				data:{'id':$this.data('galeria')}	
			},
			done:function(resp){
			
				bootbox.dialog({
					message:resp,
					className:'dialog-lg'
				});
				
			}
		});
			
	});
	
	if($('#btnCargaFile').length){
			
		$('#btnCargaFile').jCargaFile({
			url: '/jadmin/galeria/imagen-ajax',
			name:'archivoGaleria',
			multiple:true,
			onLoad:function(e){
				console.log('on load carga file ',e);
				var image = new Image();
				var ele = e.target;
				//this._data.testing = 'prueba julio';
				var $tpl = $('#mediaTemplate').html();
				var render = Mustache.render($tpl,{
					src: ele.result,
					alt:'Imagen Preview',
					id: '#'
				});
				
				$('.jida-galeria-media').append(render);
				
			},
			'postCarga':function(respuesta){
   				if(respuesta.error){
   					$listaImagenes.before('<div class="alert alert-warning">'+respuesta.msj+'</div>');
   					$('.jcargafile').remove();
   				}
			}
		});
	}
	
})();
