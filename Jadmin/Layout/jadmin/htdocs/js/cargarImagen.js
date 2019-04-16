(function ($) {

    // Boton de Carga en Formulario
    
    $('#btnCargar').jCargaFile({
        name: 'imagen',
        multiple: false,
        onLoad: function (e) {
            var image = new Image();
            var ele = e.target;
            image.src = ele.result;
            image.className = 'responsive';
            $('#preview-img').html(image);
        }
    });
    
    // Boton de Galeria 
    
    var $btnCargaGaleria = $('#btnCargarGaleria');
    
    if ($btnCargaGaleria.length) {
        
        var urlEnvio = $btnCargaGaleria.data('url');
		
		function onload(e) {
            var image = new Image();
            var ele = e.target;
            image.src = ele.result;
            image.className = 'responsive';
            
            $('#mensaje-carga').after('<span id="spanCargaImg" class="label label-info">Guardando Imagen...</span>');
            
            $('#preview-img').html(image);

            var $tpl = $('#imgTemplate').html();
            var render = Mustache.render($tpl, {
                src: ele.result,
                alt: 'Imagen Preview',
                id: '#'
            });

            $('.jida-galeria-media').append(render);
        };
        $('#btnCargarGaleria').jCargaFile({
			
			name: 'imagen',
            multiple: false,
            parametros: {'modelo':'este es el modelo'},
            url: urlEnvio,
            onLoad: onload,
            'postCarga': function (respuesta) {
            	
            	// console.log('postCarga- ',respuesta);
            	$('#spanCargaImg').remove();
            	if(respuesta.error){
   					$listaImagenes.before('<div class="alert alert-warning">'+respuesta.msj+'</div>');
   					$('.jcargafile').remove();
   				}else{
	   				
	   				total = $('#total-imas').data('total')+respuesta.data.length;
			    	$('#total-imas').attr('data-total',total);
			    	$('#total-imas').html(total);
			    	
					$('.jcargafile').each(function(key,item){
						
						if(key in respuesta.data){
							
							var $item = $(item);							
							var img = JSON.parse(respuesta.data[key].meta_data);
							
							// console.log('$item',$item,'img.sm',img.sm);
							
							parametros = respuesta.ids[key];
							$item.attr('data-imagen',img.sm);
							$item.attr('data-parametros',parametros);
							
							$item.removeClass('jcargafile');
							
						}
					});
   				}
            }
        });
    }
    
})(jQuery);