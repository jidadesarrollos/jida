var guardarMedia = function(){
//	console.log("en guardar media");
	var $form = $('#formGestionObjetoMedia');
	var $btn = $('#btnGestionObjetoMedia');
	var data = {'btnGestionObjetoMedia':true,'id_objeto_media':$btn.data('id')};
	console.log($btn.data('id'));
	var dataS = $form.serializeArray();
	 
	for(key in dataS){
		
		data[dataS[key].name] = dataS[key].value;
	} 
	
	// var formData = new FormData(document.getElementById('formGestionObjetoMedia'));
	jd.ajax({
		data:{
			url:'/jadmin/galeria/editar-media',
			data:data,
			type:'POST',
			method:'post',
			dataType:'json',
		},
		done:function(resp){
			//console.log(typeof resp,resp)
			$('.alert').remove();
			alert = (resp.ejecutado)?'alert-success':'alert-warning';
			$form.before('<div class="alert '+alert+'">'+resp.msj+'</div>');
			
		}
	});
};
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
		
		console.log('click data-galeria',$this,$this.data('galeria'));
		
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
				$("btnFormGestionMedia").on('click',guardarMedia);
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
   				console.log('postCarga',respuesta.data);
			}
		});
	}
	
})();
