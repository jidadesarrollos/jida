/**
 * Funciones Generales Utilizadas
 * 
 * @author Julio Rodriguez <jirodriguez@jidadesarrollos.com.ve>
 */
// (function($) {
    // $.countdown.regionalOptions['pt-ES'] = {
        // labels: ['Años', 'Meses', 'Semanas', 'Dias', 'Horas', 'Minutos', 'Segundos'],
        // labels1: ['Año', 'Mês', 'Semana', 'Dia', 'Hora', 'Minuto', 'Segundo'],
        // compactLabels: ['a', 'm', 's', 'd'],
        // whichLabels: null,
        // digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        // timeSeparator: ':', isRTL: false};
//         
        // $.countdown.setDefaults($.countdown.regionalOptions['pt-ES']);   
// })(jQuery);


/**
 * Verifica si un objeto Radio se encuentra seleccionado
 * @param string nombreRadio Atributo "NAME" del control
 */
function validarRadio(nombreRadio){
    
    var nombreRadio=(nombreRadio);
    
    var control = "input[name="+nombreRadio+"]";
    control = ($(control).size()>0)?control:"input[name=\""+nombreRadio+"[]\"]";
    var type = $(control).prop('type');
    var cont=0;
    var dataSeleccionada = new Array;
    if($(control+":checked").length>0){
    	radiosSeleccionados = $(control+":checked");
    	$.each(radiosSeleccionados,function(){
    		dataSeleccionada.push(this.value);
    		cont++;
    	});//final foreach
    	if(cont==1){
    		
    		return dataSeleccionada[0];
    	}else{
    		
    		//return serializar(dataSeleccionada);
    		return dataSeleccionada.join(",");	
    	}
    	
    	
    }else{
    	return false;
    }
    
                 
}//final función
function serializar(arr){
	var res = 'a:'+arr.length+':{';
		for(i=0; i<arr.length; i++){
				res += 'i:'+i+';s:'+arr[i].length+':"'+arr[i]+'";';
		}
	res += '}';
return res;
}

/**
 * Crea un campo tinyMCE
 *  
 * @param string nobreControl 
 */

function armarTiny(nombreControl){
	if(!nombreControl){
		nombreControl="textarea.tiny";
	}
	valoresTiny={
					//mode : "exact",
				    selector: nombreControl,
				    language : 'es',
				    plugins: [
				        //"image contextmenu autolink"  //eliminados
				        " link charmap print preview anchor",
				        "searchreplace code fullscreen",
				        "insertdatetime table paste"
				    ],
				    toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
			};
	tinymce.init(valoresTiny);
}

function convertirByteAMb(bytes){
    return parseInt(((bytes/1024)/1024).toFixed(2));
}
/**
 * Inicializa el objeto AjaxUpload en un botón determinado
 *  
 * @param {Object} $nombreBoton
 * @param {Object} $url
 * @param string $funcionOnComplete Nombre de  la función a ejecutar al finalizar la carga
 */
function cargarArchivo(config){
    var defaultConfig=  {
        btn : 'btnImagen',
        nombreArchivo:'imagen',
        callback:false,
        multiple:true,
        
        
        onsubmit:function(file, ext){
            
            if (ext && data.extensiones.test(ext)){
                button.text('Cargando');
                this.disable();
            }else{
                //En caso de no tener un formato valido
                //alert(\"no es una imagen\")
                return false;
            }
        },
        url:false,
        data:false,
        extensiones: /^(jpg|png|jpeg|gif|JPG|PNG|JPEG)$/
        
    };
    data = $.extend(defaultConfig,config);
    
	var button = $("#"+data.btn), interval;
	if(!data)
		data = "";
	new AjaxUpload(button, {
				multiple:true,
				action: data.url, 
				name: data.nombreArchivo,
				data:data,
				onSubmit : data.onsubmit,
				onComplete:data.callback
			
			});
}

function scroll(){
      $("[data-scroll=true]").on('click',function(e) {
             e.preventDefault();
             
             var $target = $(this.hash);
             $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
             if ($target.length) {
                 var targetOffset = $target.offset().top;
                 $('html,body').animate({scrollTop: targetOffset}, 900);
                 return false;
            }
      });
}


function initCarousel(){    
    $("#j-carousel").owlCarousel({
            singleItem: true,
            
            //Basic Speeds
            slideSpeed : 700,
            paginationSpeed : 700,
            rewindSpeed : 700,
            lazyEffect: 'fade',
            
            // Navigation
            navigation : false,
            
            //Pagination
            pagination : false,
            paginationNumbers: false,
            
            //Auto height
            autoHeight : false,
            
            addClassActive: true,
            
        });
        
}

function getActiveIndex(){
    link = window.location.pathname;
    idSeccion = jd.replaceAll(link,'/');    
    var item = $(".owl-item .active > .item");
    $("[data-id="+idSeccion+"]").data('owlslide',1);
    
    
}



