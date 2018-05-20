/**
 * Plugin para carga de archivos
 * 
 * jCargaFile
 * @author julio Rodriguez @jr0driguez
 * @version 0.1 2017 
 */
(function($){
	
	var jCargaFile = function(objeto,config,event){
		this.objeto = objeto;
		this.configuracion = config;
		this.$obj = $( this.objeto );
		this.init();
		this._FileReader = new FileReader();
		this._data={};
		var that = this;
	};
	var selector = '[data-jida="cargaFile"]';
	 
	jCargaFile.prototype = {
		regExps:{
			'imagen' : /\.(jpe?g|png|gif)$/i,
			
		},
		/**
		 * Numero de archivos cargados exitosamente
		 * @property int _archivosCargados 
		 */
		_archivosCargados: 0,
		/**
		 * Nro de archivos seleccionados por el usuario
		 * @property int _archivosSeleccionados default 0 
		 */
		_archivosSeleccionados:0,
		_data:{},
		
		_obtConfiguracion:function(){
			var defaultConfig ={
				'preCarga':function(){},
				'onLoadArchivo':this._defaultOnload,
				'postCarga':function(){console.log("carga default");},
				'multiple':false,
				'name': "_jcargaArchivo",
				'btnCarga':false,
				'onLoad':false
			} ;
			this._configuracion = $.extend(defaultConfig,this.configuracion);
		},
		
		init:function(){
			
			this._obtConfiguracion();
			
			$file = $('<input>').attr({
				'type':'file',
				'id':this._configuracion.name,
				'name':this._configuracion.name,
				'style':'display:none',
				'multiple':this._configuracion.multiple
				
			});
			this.$obj.after($file);
			this.$file = $file;
			this.file = $file.get(0);
			this._manejarEventos();
			
		},
		/**
		 * 
		 */
		_manejarEventos:function(){
			
			var plugin = this;

			this.$obj.on('click',function(e){
				this.$file.off();
				this.
					$file.trigger('click')
					.on('change',this._managerChange.bind(this));
				
			}.bind(this));
		},
		/**
		 * 
		 */
		_managerChange:function(e){
			
			var ele = e.target;
			var plugin = this;
			this._archivosSeleccionados = ele.files.length;
			this._defaultPrecarga.call(plugin,e);
			
			//this.$file.off();
		},
		_managerLoadEnd:function(e){
			var ele = e.currentTarget;
			var plugin = this;
			++plugin._archivosCargados;
			console.log("load end", this._configuracion.btnCarga);
			console.log(plugin._archivosCargados +" == "+plugin._archivosSeleccionados);
			console.log('configuracion url :', this._configuracion.url);
			if(this._configuracion.btnCarga){
				
				$(this._configuracion.btnCarga).on('click',this._postData.bind(this));
				
			}else
			if((plugin._archivosCargados == plugin._archivosSeleccionados) && this._configuracion.url){
				console.log("aki papi");
				this._postData();
				
			}
		},
		_managerOnLoad:function(e){
			var ele = e.target;
			var plugin = this;
			
			ele.removeEventListener('load',plugin._managerOnLoad);
			
			plugin._configuracion.onLoad.call(plugin,e);
		},
		_postData : function(){
			console.log("post data");
			var form = new FormData();
			var plugin = this;
			var name = (plugin._archivosCargados>1)?plugin._configuracion.name+"[]":plugin._configuracion.name;
			
			[].forEach.call(plugin._archivos,function(archivo){
				
				form.append(name,archivo);
			});
			
			for(key in plugin._data){
				form.append(key,plugin._data[key]);
			}
			$.ajax({
				'url':this._configuracion.url,
				'type':'post',
				'processData':false,
				'contentType': false,
				'data':form,
				'dataType':'json',
				'success':function(r){
					plugin.file.value='';
					plugin._archivosCargados = 0;
					plugin._configuracion.postCarga(r);
				},
				'error':function(e){
					console.log("error",e);
				}
			});
			
		},

		
		_defaultPrecarga:function(event){

			var ele = event.target;
			var plugin = this;
			var archivos = ele.files;
			this._archivos = archivos;
			plugin._configuracion.preCarga.call(plugin,event);
			
			if(archivos){
				
				band = 0;	
				[].forEach.call(archivos,function(archivo){
				
					archivo.id_app = band;
					++band;
					var reader = new FileReader();		
					reader.addEventListener('load',this._managerOnLoad.bind(plugin),false);
					reader.addEventListener('loadend',this._managerLoadEnd.bind(plugin),false);
					reader.readAsDataURL(archivo);
					
				}.bind(plugin));
				
			}
					 
		},
		
		_defaultOnload:function(e){
			var image = new Image();
			var ele = e.target;
			var plugin = this;
			
			image.height=150;
			image.title = ele.title;
			image.src=ele.result;
			$li = $('<li>').html(image);
			$('#imagenes').append($li);
			++this._archivoCargados;
			
		},
		_previewImage:function(){
			
		}
	};
	    /**
     *   =============================
     *   DECLARACION DEL PLUGIN 
     *  ============================
     * 
     */
    function jPlugin(config,e){
        
        var $this = $(this);
        return this.each(function(i,ele){    
  
            v = new jCargaFile(ele,config,e);
            
        });
    };
	$.fn.jCargaFile = jPlugin;
	$(selector).each(function(i,elem){
		new jCargaFile( elem );
	});
	
})(jQuery);


/**
 * ObjetoAjax
 * 
 * Objeto prototype javascript que controla todo el manejo normal del funcionamiento 
 * ajax.
 * Utiliza libreria jQuery 1.8+
 * 
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @version 1.0
 * @fecha : 14/07/2014 
 */

function jd (){
 return true;   
};
//Definiendo constantes-----------------------------------------------
jd.inicializandoAjax=0;
jd.cargandoAjaxUno=1;
jd.cargandoAjaxDos=2;
jd.listoInteraccionAjax=3;
jd.listoAjaxCompleto=4;
jd.contentTypeForm="application/x-www-form-urlencoded";

jd.ajax=function(json){
    try{
        this.parametros = json;
        this.valores = this.inicializarValores();
        this.enviarData();  
    }catch(err){
        console.error(err);
    }
    
};


jd.ajax.prototype = {
    
     valoresPredeterminados:{
      "contentType":"application/x-www-form-urlencoded",
      "metodo":"POST",
      "funcionCarga":null,
      "contentype":true,
      "parametros":null,
      "respuesta":"html",
      "cargando" :"<div class='cargaAjax'> Cargando...</div>",
      "funcionProgreso":false,
      "pushstate":false
    },
    inicializarValores:function(){
        var valores = $.extend(this.valoresPredeterminados,this.parametros);
        
        return valores;
        
    },
    httpr:function(){
        var xmlhttp=false;
      try {
          xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
      try {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
          xmlhttp = false;
      }
    
      }//fin catch.
      if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
          xmlhttp = new XMLHttpRequest();
      }
      return xmlhttp;
    },
    enviarData:function(){
        var data="s-ajax=true";
        this.obAjax=this.httpr();
        objeto = this;
        ajax = this.obAjax;
        
        ajax.onreadystatechange=function(){
            objeto.Listo.call(objeto);
        };
        
        if(typeof this.valores.parametros=='object' && this.valores.parametros!=null){
            data+="&";
            $.each(this.valores.parametros,function(key,value){
                data+="&"+encodeURI(key)+"="+encodeURI(value);
            });
        }else if(typeof this.valores.parametros=='string'){
            data +="&"+this.valores.parametros;
        }
        
        if(this.valores.metodo=='get' || this.valores.metodo=='GET')
        	this.valores.url+='?'+data;

        ajax.open(this.valores.metodo,this.valores.url,true);
        //validar contentype
        if(this.valores.contentype==true){  
            ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            ajax.setRequestHeader('HTTP_X_REQUESTED_WITH','XMLHttpRequest');
            ajax.setRequestHeader('X-Requested-With','XMLHttpRequest');
            
        }//fin if
        
        
        // else{
            // throw "No se encuentra definido correctamente el objeto parametros";
        // }
        ajax.send(data);
        setTimeout(function(){
            console.log(ajax.readyState);
            if(ajax.readyState==1 || ajax.readyState==0){
                ajax.abort();
                console.log("La llamada ajax a tardado demasiado");    
            }
        },15000);
        
    },
    Listo:function(){
        
        ajax = this.obAjax;
        if(ajax.readyState==jd.cargandoAjaxUno || ajax.readyState==jd.cargandoAjaxDos){
            $(".cargaAjax").remove();
            $('body').prepend(this.valores.cargando);
        }
        if(ajax.readyState==jd.listoAjaxCompleto){
            $(".cargaAjax").remove();
            var httpStatus= ajax.status;
            if(httpStatus==200 || httpStatus ==0){
                //this.valores.funcionCarga.call(this);
                this.procesarRespuesta();
                
                if(typeof this.valores.pushState!==false){
                    this.validarPushState();
                }
                this.valores.funcionCarga.call(this);
                
            }else{
                this.errorCarga();
            }//fin if httpStatus
        }//fin if readyState
    },//fin funcion Listo
    validarPushState:function (){
        
        if(typeof this.valores.pushState=='object'){
            pushStateDefault = {'id':null,'title':null,'url':null};
            state = $.extend(pushStateDefault,this.valores.pushState);
            history.pushState(state.id,state.title,state.url);
        }else
        if(typeof this.valores.pushState=='string'){
            history.pushState(null,null,this.valores.pushState);
        }
        window.addEventListener('popstate',function(e){
          console.log('im here');  
        });
    },
    /**
     * Muestra un error posible en la ejecución de la llamada ajax. 
     */
    errorCarga:function(){
        window.location.href=window.location.pathname+"#error";
        console.log("Error estatus: "+this.obAjax.status);
    },
    /**
     * Procesa la respuesta obtenida del servidor
     * 
     */
    procesarRespuesta:function(){
        var respuesta;
        switch(this.valores.respuesta){
            case 'json':
                respuesta = JSON.parse(this.obAjax.responseText);
                break;
            default:
                respuesta = this.obAjax.responseText;
                break;
        }//fin switch
        this.respuesta = respuesta;
    }
};//fin prototype.



/**
 * Plugin para manejo de formularios
 * @author Julio Rodriguez
 * @version 0.1 15/11/2014 
 */


+function(){
    
    ControlCarga = function(ele,opcs){
        
        this.inicializarValores(opcs);    
        this.init(ele,opcs);  
    };
    ControlCarga.prototype ={
        inicializarValores:function(opciones){
            var valores = {
                multiple:false,
                
            };
            if (typeof opciones == 'string'){
                this.botonCarga = opciones;
                console.log(this.botonCarga);
                this.conf=valores;
                if($(this.botonCarga).length<1)
                    throw console.log("No se encuentra definido el boton de envio");
            }else{
            
                this.conf= $.extend(valores,opciones);
            }
                
            
        },
        init: function(ele,opciones){
            obj = this;
               
            var ele = $(ele);
            //Creacion de objeto file
            var sCarga = $('<input type="file"/>').css({'display':'none','bottm':'0','position':'absolute'});
            if(obj.conf.multiple){
                sCarga.prop('multiple',true);
                sCarga.attr('name',sCarga.attr('name')+"[]");
            }            
            ele.after(sCarga);
            ele.on('click',function(){
               sCarga.click(); 
            });
            
            //validacion de cambios en el objeto file
            sCarga.on('change',function(){
                var archivos = this.files;
                
            });
        //carga del archivo
        console.log(obj.botonCarga);
            $( obj.botonCarga ).on('click',function(e){
                e.preventDefault();
                
                archivos = sCarga[0].files;
                console.log(archivos);
                formData = new FormData();
                for(i=0;i<archivos.length;++i){
                 formData.append('archivos[]',archivos[i],archivos[i].name);   
                }
                new jd.ajax({
                    url:'/excel/carga-archivo',
                    file:formData,
                    respuesta:"html",
                    funcionCarga:function(){
                        $("#respuestaCarga").html(this.respuesta);
                    }
                });
            });
            
        }
  //      padre.html(ele.html()+"<button>Cargar Archivo</button>");
//        ele.css({"display":"none","position":"absolute","bottom":"0"});
        
        
        
        
    };
        cargaArchivo =function(opciones){
          jd.cargador(this,opciones);
        };
    
    function Plugin(opciones) {
        return this.each(function () {
          var $this = $(this);
          new ControlCarga($this,opciones);
        });
    }
    
    $.fn.controlCarga = Plugin;
    $.fn.controlCarga.Contructor = ControlCarga;
    
    jQuery.fn.jd = new jd();    
}(jQuery);


// jd.prototype.form ={
//   
  // cargaArchivo : function(){
//        
  // }
//     
// };


/**
 * 
 *  jidaControl Arranque de mascara de campos
 * 
 * Plugin Jquery y HTML5 para manejo de controles de formularios
 * 
 * @author : Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * 
 * 
 * Requiere : Jquery 1.9+, jqueryui 1.10,
 * 
 * @example: 
 * 
 * $( this ).jidaControl();
 * <input type='text' data-jidacontrol='rif' id='rif' name='rif'> 
 * 
 *  
 */
//========================================================
function replaceAll(value,charte){
    var result = value;
    var posi = value.indexOf(charte);
    if(posi > -1) {
        while(posi > -1){
            result = value.substring(0,posi);
            result = result + value.substring(posi+1);
            posi = result.indexOf(charte);
            value = result;
        }
    }
    return(result);
}//final funcion
//========================================================
(function($){
    
    $.fn.jidaControl = function() {
        elemento = this;
        // $( "body" ).on('click','[data-jidacontrol]',function(){
//         	
//         	
		// a = new jd.controladorInput( this );
		//             
// });
		
	       elemento.each(function(){
	       		if(!$(this).data('jidacontrolaply')){
	       			new jd.controladorInput( this );
	       		}
	       		
	       	});
     
    };//final $.fn.jidaControl
    
})(jQuery);


/**
 * Función contructora del jidaControlador
 *  Agrega una mascara a cada elemento pasado de acuerdo con el valor agregado en el atributo data jidacontrol,
 * Para manejo interno el constructor agrega un data "jidacontrolaply".
 * 
 */
jd.controladorInput = function(control,elemento){
    /**
     * Referencia al control sobre el cual se aplicará el
     * controlador de formato
     */
    
    this.control = control;
    
    /**
     * Objeto Jquery sobre el cual se aplica el controlador de formato.
     */
    this.controlObject = $(control);
    /**
     * Alias al jidaControlador (this)
     * @var objeto
     */
    objeto = this;
    this.validacion = this.controlObject.data('jidacontrol');
    
    this.inicializador();
    
};
jd.controladorInput.prototype={
    /**
     * Funcion que inicializa la validacion
     */
    inicializador:function(){
        formato="";
        /*Validar que exista la validación capturada en el data-jidacontrol*/
        if(this.validaciones[this.validacion]){
        	
            /**
             * Identifica cual sera el controlador a utilizar
             */
            patronXDefault = {tipo:1}; 
            /**
             * JSON con validación con keys de expresión regular aplicada
             * y tipo de controlador a aplicar
             */
            patronDeValidacion = this.validaciones[this.validacion];
            
            patron = $.extend(patronXDefault,patronDeValidacion);
            //Se determina el controlador a utilizar
            controladores = ['controlador','controladorCaracter','controladorDecimal'];
            /**
             * Nombre del controlador a utilizar
             */
            funcionControlador = controladores[patron.tipo];
            var idControl ="#"+this.controlObject.prop('id');
            /**
             * Se llama a la funcion controladora en el evento keypress para que evalue
             * el formato
             */
            
            $( this.controlObject ).data('jidacontrolaply',true);            
            $( this.controlObject ).on('keypress',
            
                    {
                        validacion: objeto.validaciones[this.validacion].cadena,
                        formato:objeto.formatosDisponibles[this.validacion]
                    },
                    objeto[funcionControlador]);
             /**
              * Se agrega un llamado al formateador en el evento keyup para 
              * @see formateador
              */
            $( this.controlObject ).on('keyup',
                                
//                              idControl,
                                {
                                	formato:objeto.formatosDisponibles[this.validacion]
                                },
                                this.formateador);

            
        }//fin chequo validaciones
    },//fin metodo inicializador
    /**
     * Metodo controlador que valida  las entradas del teclado
     * para asegurar el cumplimiento de la validación agregada al campo por medio del
     * jidacontrol usando expresiones regulares que vienen desde el json validaciones del objeto.
     * 
     * Agrega formato numerico con separador de miles y decimales si es requerido
     * @param : Rec
     */
    controladorDecimal:function(e){
        
        tecla = String.fromCharCode(e.which);
        key = e.which;
        isCtrl=false;
        if(e.which==8 || e.which==9 || e.keyCode==9 || e.which==37 || e.which==38 || e.which==39 || e.which==40 || e.keyCode==222
            || e.which==222
            ) return true;
        if(key==17) isCtrl=true;
        
        if(isCtrl==true &&(key==37 || key==39 || key==46 || key==161 || key==225 || key==17 || key==18)){
            e.preventDefault();
    
        }else{
            //-------------------------
            
            patron=e.data.validacion;
            if(!patron.test(tecla)){
                e.preventDefault();
            }else{
                //Definir cantidad de decimales
                
                decimal = $(this).data('decimal');
                decimal = (typeof(decimal)=="undefined")?0:decimal;
                elemento = $(this);
                //obtener valor del elemento con formato
                valorNumero = elemento.val();
                tamValorNumero = valorNumero.length+1;
                
                if(tamValorNumero>=decimal+1){
                    //eliminar formato de miles al value
                    numeroSinFormato=replaceAll(valorNumero,'.');
                    if(valorNumero.indexOf(",")>=0)
                    //eliminar coma de decimales si existe
                        numeroSinFormato=valorNumero.replace(",",'');
                    numeroSinFormato=numeroSinFormato+tecla;
                    
                    numA = numeroSinFormato.substr(numeroSinFormato.length - decimal);
                    
                    //volver a validar el formato y eliminarlo
                    numSinPunto = replaceAll(numeroSinFormato.substr(0,numeroSinFormato.length - decimal),'.');
                    //agregar el numero seleccionado para la validacion
                    numSinPunto = numSinPunto;
                    
                    numB="";
                    i=1;
                    //----------------------
                    while(numSinPunto.length>3){
                        numB="."+numSinPunto.substr(numSinPunto.length-3) + numB;
                        numSinPunto=numSinPunto.substring(0, numSinPunto.length - 3);
                        
                    }//fin while
                    //----------------------
                    numB = numSinPunto+numB;
                    if(decimal>0){
                        
                        numeroFinal=numB+","+numA;
                    }else
                        numeroFinal = numB;
                    elemento.val(numeroFinal);
                    e.preventDefault();
                }//fin mayor a 3 sin decimales
                else{
                //	console.log("nosilve");
            	}
            }//fin if validacion cadena
            //-------------------------
        }//final if...else
    },
    /**
     * Arranque  por caracteres
     * TIPO : 1
     * Valida un campo, evaluando solamente el caracter ingresado en el momento
     */
    controladorCaracter:function(e){
        
        
        tecla = String.fromCharCode(e.which);
        key = e.which;
        isCtrl=false;
        /*Permitir borrar y tab*/
        
        if(e.which==8 || e.which==9 || e.keyCode==9 || e.keyCode==37 || e.keyCode==39 || e.keyCode==46 || e.keyCode==222
            || e.which==222) return true;
                
                
        if(key==17) isCtrl=true;
        if(isCtrl==true &&(key==37 || key==39 || key==46 || key==161 || key==225 || key==17 || key==18)){
            e.preventDefault();
    
        }else{
            patron=e.data.validacion;
            if(!patron.test(tecla)){
                e.preventDefault();
            }else{
                decimal = $(this).data('decimal');
                if(decimal){
                    
                }
            }//fin if validacion cadena
        }
        return this;
    },
    /**
     * Evalua la cadena completa ingresada en el control HTML, incluyendo
     * el caracter ingresado en el momento
     * 
     * @param event e
     */
    controlador:function(e){
        
        tecla = String.fromCharCode(e.which);
        key = e.which;
        isCtrl=false;
            //PERIMITIR BORRAR
            
            if(e.which==8 || e.which==9 || e.keyCode==9 || e.which==37 || e.which==38 || e.which==39 || e.which==40 || e.keyCode==222
                || e.which==222) return true;
            if(key==17) isCtrl=true;
            
            if(isCtrl==true &&(key==37 || key==39 || key==46 || key==161 || key==225 || key==17 || key==18)){
                e.preventDefault();
        
            }else{
                
                patron=e.data.validacion;
                
                formato  =e.data.formato;
                cadenaInsertada = this.value+tecla;
                tamCadena = cadenaInsertada.length;
                //Merge de cadena insertada con el "formato" esperado
                cadenaValidada =cadenaInsertada+formato.substr(tamCadena);
                
                //Comparamos el patron de la Exp Regular con la cadena insertada
                if(patron.test(cadenaValidada)){
                    return true;
               }else{
                   e.preventDefault();
               }//final if
               
            }//fin validacion keycodes no permitidos
            return this;
    },
    /**
     * FORMATEADOR de selector
     * 
     * Verifica el formato de la expresión requerida en el selector y agrega automaticamente
     * los caracteres de formato requeridos (guión y punto), en caso de haberlos
     */
    formateador:
    function(e){
        /**
         * Cadena escrita por el usuario
         */
        cadenaInsertada = $(this).val().toUpperCase();
        
        //tamanio de la cadena actual
        tamCadena = cadenaInsertada.length;
        //formato de la cadena requerida
        if(e.data.formato){
            formato =e.data.formato;
            //tamaño de la cadena requerida.
            tamanioFormato = formato.length;    
        }else{
            tamanioFormato=100;
        }
        
        
        if(e.which!=8){
            caracteresDeSeparacion=/^[\/.\-]{1}$/;
            proximoCaracter=formato[tamCadena];
            
            if(tamanioFormato>=tamCadena){
                
                caracterEsperado = formato[tamCadena-1];
                caracterIngresado = cadenaInsertada[tamCadena-1];
                 //console.log("dentro del primer if "+ caracterEsperado);
                if(caracteresDeSeparacion.test(caracterEsperado)){
                
                   cadenaInsertada[tamCadena-1] = caracterEsperado;
                   
                   caracterIngresado=(caracterIngresado!=caracterEsperado)?caracterIngresado:"";
                   $( this ).val(cadenaInsertada+caracterIngresado);
                   
                }else if(caracteresDeSeparacion.test(proximoCaracter) && proximoCaracter!=caracterEsperado){
                    $(this).val(cadenaInsertada+proximoCaracter);
                } 
            }//fin if tamanio cadena
            
            
            
            
         }
         return this;
    },//final funcion
    
    /**
     * JSON con todas las validaciones disponibles del PluG-IN
     * cada key tiene como valor otro json que contiene de forma obligatoria cadena: expReg.
     * puede pasarsele como segundo parametro "tipo" para indicar el controlador a utilizar (por defecto es el 1)
     * Controladores :  0.[controlador] 
     *                     aplica expresion a todo el valor del campo.
     *                  1. [controladorCaracter]
     *                     aplica expresion sobre el valor ingresado al momento sin evaluar lo que ya se haya ingresado
     *                     [controladorDecimal]
     *                  2. aplica expresion sobre el campo y agrega formato numerico con separador de miles y decimales si se requiere.
     */
    validaciones:{
        numerico: {cadena : /^[0-9]*$/,tipo:1},
        cedula: {cadena : /^([V|E|G|J|P|N]\-{1}\d{8})*$/,tipo:0},
        rifConFormato :  {cadena : /^([V|E|G|J|P|N]\-{1}\d{8}-{1}\d{1})*$/,tipo:0},
        rif:{cadena:/^([V|v|E|e|G|g|J|j|P|p|N|n]\d{9})*$/,tipo:0},
        telefono: {cadena : /^(\d{11})*$/,tipo:1},
        miles : {cadena:/^[0-9]*$/,tipo:2},
        caracteres : {cadena : /^[A-ZñÑa-z ]*$/},
        alfanumerico : {cadena: /^[0-9A-ZñÑa-z ]*$/},
        coordenada : {cadena: /^\-?[0-9]{2}\.[0-9]{3,15}/,tipo:0},
        fecha:{cadena:/^\d{2,4}[\-|\/]{1}\d{2}[\-|\/]{1}\d{2,4}$/,tipo:0}
        //cedula : {cadena:/^([V|E]\-{1}\d{8})*$/},
        //cedula : /^([VEJG]\d{7,8})$/,
    },
    /**
     * Formatos a aplicar a cada validación que utilice el
     * formateador tipo 0. 
     *
     * */
    formatosDisponibles:{
        
         rif : 'J123456789',
         rifConFormato : 'J-12345678-9',
         fecha:'00-00-0000'
        
    }
    
    
};//final prototype jidaControlador



(function($){
    var contenedor= '[data-liparent]';
    var menu = function(ele){
        $(contenedor).on('click',this.checksubnivel);
    };
    menu.prototype.checksubnivel=function(){
        var ele = $( this );
        
        if(ele.children('ul').length>0){
            if(ele.children('ul').hasClass('show')){
                $("ul.show").removeClass('show');
                ele.removeClass('selected');
            }else{

                $("ul.show").removeClass('show');
                $("li").removeClass('selected');
                ele.addClass('selected');
                ele.children('ul').addClass('show');
            }
        }
    };

})(jQuery);
/**
 * JidaFramework : validador v1.0
 * 
 * Copyright 2012 - 2017
 *  
 */
(function($){
     /**
     * 
     *  Objeto jValidador
     */
    jValidador = function(ele,config,e){
        this._default ={
           funcionError : this.mensajeError,
           totalError:false, //si se coloca en true se mostrará un solo sms con todos los errores,
           divError:false,
           cssError:'div-error',
           vControl:true,
           post:false,
           prev:false,
           validaciones:false,
           form:false,
           campo:false,
           viaData:false
        };
        
        if(ele){
            this.$ele = $(ele);
            this.config = config;
            this.errores = new Object();    
    
            
            if(typeof this.config== 'object') this.config = $.extend(this._default,this.config);
            
            else this.config = this._default;
            if(this.config.campo){
                
                this.$ele = $( ele );
                var jvalidador = this;
                var vj = jvalidador;
                this.initInput();
            }else{
                
                this.$ele.data('config',this.config);
                if(!this.config.form){
                    this.$form = $(this.$ele[0].form);
                    
                }else
                    this.$form = $("#"+this.config.form);
                
                this.$ele.data('jd.validador',this);
                this.$form.data('jd.validador',this);
                var vj = jvalidador;
                if(!this.init()) e.preventDefault();
            }
            
                
            
        }else{
            console.log("ele is FALSE");
        }        
        
        
    };
    jValidador.VERSION='1.1';
    var jdValidador = '[data-jida="validador"]';
    jValidador.replaceAll = function(value,charte,valorReplace){
    	if(!value) return value;
        if(!valorReplace)
            valorReplace ="";
        var result = value;
        var posi = value.indexOf(charte);
        if(posi > -1) {
            while(posi > -1){
                result = value.substring(0,posi);
                result = result + valorReplace +value.substring(posi+1);
                posi = result.indexOf(charte);
                this.errores=true;
                value = result;
            }
        }
            return(result);
    };//final funcion
    $.fn.validadorJida = jPlugin;
    jValidador.prototype = {
        /**
         * Esta funcion es ejecutada solo si el validador es instanciado sobre
         * un solo campo a ser validado. 
         */
        initInput:function(e){
            $ele = this.$ele;
            bandera=true;
            jv = this;
            
            if($ele.data('validacion') && bandera===true){
            
                if(!jv.validar($ele,jv.verificarValidaciones($ele.data('validacion')))){
                    eval(jv.config.funcionError).call(this,$ele);
                    jv.erroresCampo=true;
                    bandera=false;
                    return bandera;
                }
            }
            //console.log($ele.data('validacion'));
            return bandera;
        },
        erroresCampo:false,
        init :function(e){
            
            var $btn = this.$ele;
            jv = $btn.data('jd.validador');
            
            jv.errores = new Object();
            bandera=true;
            
            if(jv.validarFuncionesExternas('prev')){
                var $formulario = this.$form;
                /**
                 * @var boolean bandera Encendida si no se encuentran errores 
                 */
                var bandera = true;
                
                if(typeof $formulario != 'undefined' || typeof $formulario.elements!='undefined'){
                    $.each($formulario[0].elements, function(index, ele) {
                        var $ele = $( ele );
                        var validacionesCampo = jv.obtValidacionesCampo($ele);    
                        if(validacionesCampo && bandera===true){
                            if(!jv.validar($ele,jv.verificarValidaciones(validacionesCampo))){
                                                                       
                                if(!jv.totalErrores) jv.config.funcionError.call(this,$ele,jv);
                                
                                bandera=false;
                                return bandera;
                            }
                        }
                    });
                    if(bandera) bandera = jv.validarFuncionesExternas('post');
                    
                }
            }else bandera=false;
            return bandera;
        },
        /**
         * Obtiene las validaciones de un campo dado
         * @method obtValicacionCampo
         *  
         */
        obtValidacionesCampo : function($ele){
            
            if(this.config.viaData){
            	validaciones =$ele.data('validacion');
            	 if(typeof(validaciones)=='string'){
            	 	validaciones = JSON.parse(validaciones);
            	 }
                return validaciones;
            }else{
                if(this.config.validaciones[$ele.attr('id')]!='undefined')
                    return this.config.validaciones[$ele.attr('id')];
                else{
                    return false;
                }
            }
        },
        /**
         * Valida si se debe ejecutar una función antes del validador
         */
        validarFuncionesExternas : function(tipo){
            
            if(this.config[tipo]!=false  && typeof(this.config[tipo]) !='undefined'){
                if(typeof this.config[tipo]=='string'){
                    result = eval(this.config[tipo]).call(this,jv.$form);
                }else{   
                    result = this.config[tipo].call(this);
                }
     
                return result;
            }else{
                return true;
            }
            
        },
        validar:function($ele,validaciones){
            
            var bandera=true;
            if(validaciones.obligatorio==true || typeof validaciones.obligatorio=='object'){
                bandera = jv.obligatorio($ele,validaciones.obligatorio);
                 
            }
            if(bandera){
                $.each(validaciones,function(validacion,params){
                    type = typeof(parametros);
                    if(params!=false && bandera==true && validacion!='obligatorio'){
                        if(jv[validacion]){
                            if(!jv[validacion]($ele,validacion,params)){
                                jv.errores[$ele.attr('id')]=validacion;
                                bandera=false;
                            }
                        }else
                        if(jValidador.validaciones[validacion]){
                        	
                            if(!jv.ejecutarValidacion($ele,validacion)){
                                jv.errores[$ele.attr('id')]=validacion;
                                bandera=false;
                            }
                        }else{
                          console.log(validacion,params);
						
						
                          console.error("No existe la validacion solicitada "+ validacion+" para el campo "+$ele.attr('id'));   
                        }
                        
                    }
                });
                
            }else{
                
                jv.errores[$ele.attr('id')]='obligatorio';
            }
            
            return bandera;
            
         },
         /**
          * Ejecuta una funcion externa como parte del validador
          * @method externa
          *  
          */
         externa:function($ele,validacion,params){
            
            if(params.funcion){ 
                return params.funcion.call(this,$ele,validacion,params);
            }if(params.expr){
            
                return this.ejecutarValidacion($ele,validacion,params.expr);
            }else{
                throw new Error("No se ha formulado correctamente la validacione xterna"+ validacion);   
           }
         },
         expr:function($ele,validacion,params){
            
             return this.ejecutarValidacion($ele,validacion,params.expr);
             
         },
         /**
         * Ejecuta las validaciones estandar
         * 
         * Hace uso de la expresión regular correspondiente a la validacion
         * la expresión regular debe encontrarse en el objeto validaciones 
         *
         */
        ejecutarValidacion:function($campo,validacion,expresion){

            if(!expresion) expresion = jValidador.validaciones[validacion].expr;

            var valorCampo = $campo.val();
            if(validacion=='numerico' || validacion=='decimal'){
                //Si el campo es numerico se eliminan los formatos de miles
                valorCampo=jValidador.replaceAll(valorCampo,'.');
                if(validacion=='decimal'){
                //Si el campo es decimal se cambia la coma de decimal por el punto
                    valorCampo.replace(",",".");
                }
            }
            if(valorCampo!=""){
                if(expresion.test(valorCampo)) return true; 
                else return false;
            }else{
                return true;
            }//fin validacion
            
        },
        /**
         * Función por defecto para mostrar mensaje de error del formulario
         * @method mensajeError
         * @param object $campo Objeto jQuery del campo validado
         * @params object objeto jValidador
         */
        mensajeError:function($campo,vj){
            
            
            if($campo){
                
                $input = $campo;
                
                var errorCampo = jv.errores[$campo.attr('id')];
                
                var validacionesCampo = vj.obtValidacionesCampo($campo);
                var msj="";
                var divError = vj.$form.find("."+vj.config.cssError);
       
                if(validacionesCampo[errorCampo]!= undefined){
                
                    msj = (validacionesCampo[errorCampo].mensaje)?validacionesCampo[errorCampo].mensaje:jValidador.validaciones[errorCampo].mensaje;    
                }else{
                    msj = jValidador.validaciones[errorCampo].mensaje;
                    
                }
                if(divError.length>0){
            
                    //$input = this.$form.find('[name="'+$campo.attr('name')+'"]');
                    if($input.parent().hasClass('control-multiple')){
                        $input.focus().parent().before(divError.html(msj).show());
                        $('html,body').animate({
                            scrollTop: $input.offset().top - 200
                        });    
                    }else{
                        $input.focus().before(divError.html(msj).show());
                        $('html,body').animate({
                            scrollTop: $input.offset().top - 200
                        });    
                    }
                        
                }else{
                    $divError = $("<div></div>").addClass(jv.config.cssError).html(msj);
                    vj.$form.on('click',function(e){
                    
                        if($(e.target).attr('id')!= jv.$ele.attr('id')){
                            $divError.fadeOut();
                        }
                    });
                    //$input = this.$form.find('[name="'+$campo.attr('name')+'"]');
                    if($input.parent().hasClass('control-multiple')){
                        $input.focus().parent().before($divError).show();
                        //$(window).scrollTop($input.position().top - 200);
                        $('html,body').animate({
                            scrollTop: $input.offset().top - 200
                        });
                    }else{
                        $input.focus().before($divError).show();
                        $('html,body').animate({
                            scrollTop: $input.offset().top - 200
                        });    
                    }                        
                }

            }
            
            
        },
        /**
         * Verifica las validaciones pasadas para el campo y estandariza la forma
         * @method verificarValidaciones 
         */
        verificarValidaciones:function(validaciones){
            var validacionesDefault = {
                numerico:false,
                documentacion:false,
                obligatorio:false,
                caracteres:false
            };
            
            if(typeof validaciones!='undefined'){
                
                if(validaciones instanceof Array){
                    newObject =Object();
                    $.each(validaciones,function(key,val){
                       newObject[val]=true; 
                    });
                    validaciones = newObject;
                }
                
                return $.extend(validacionesDefault,validaciones);    
            }else{
                return validacionesDefault;
            }
            
            
        },
       /**
         * Validar si el control ha sido llenado
         * 
         * Verifica que se haya ingresado algún dato en el control
         * @return array resp arreglo{
         *      @var boolean resp.val false=>Error true=>bien
         *      @var string message => Mensaje de error
         * }
         */
        obligatorio:  function($campo,arr){//VALIDAR SI UN CAMPO ESTA VACIO;
            
            if(typeof arr.evaluacion =='undefined') arr.evaluacion = 'igual';
            var tipoCampo = $campo.attr('type');
            var condicion=true;
            if(arr.condicional){
		        var valor;
		        var $condicional =  $("#"+arr.condicional);
		        
		        if(arr.tipo && arr.tipo=="radio" || $condicional.attr('type')=='radio'){  
	              
					if($condicional.length<1){
						
					 	$condicional = $("input[name="+arr.condicional+"]");
					}
					valor = $("input[name="+arr.condicional+"]:checked").val();
					
					var nombreCampo =$condicional.attr('name');
					if(typeof nombreCampo=='undefined'){
						console.error("El condicional para "+$campo.attr('name')+" no ha sido definido correctamente",$condicional,arr);
					}

	            }else{
	              valor = $condicional.val();
	            }
		        condicion=false;
		        switch(arr.evaluacion){
		            case 'igual':
		            	
		                if(valor==arr.condicion) condicion=true;    
		            break;
		            case 'diff':
		                if(valor!=arr.condicion) condicion=true;
		            break;
		            case 'mayor':
		                if(valor>arr.condicion) condicion=true;
		            break;
		            case 'menor':
		            if(valor<arr.condicion) condicion=true;
		            break;
		        }    
            }else condicion=true;
            
            if(condicion===true){
                switch (tipoCampo){
                    case 'RADIO':
                    case 'radio':
                    case 'CHECKBOX':
                    case 'checkbox':
                        var nombreCampo = $campo.attr('name');
                        
                        if($('input[name="'+nombreCampo+'"]:checked').length>0){  
                            
                            resp= true;
                        }else{    
                            resp=false;
                        }
                        
                        break;
                    default:
                    	
                        if($campo.val().trim()=="") resp=false;
                        else  resp=true;
                        
                    break;  
                }//final switch========================
                    
            }else  resp=true;
            
            return resp;
        },
        documentacion:function($campo,validacion,parametros){
                
            var expresion = jValidador.validaciones[validacion].expr;
            var valorCampo = $campo.val();
            if(parametros.campo_codigo){
                valorCampo = $campo.prev().val()+valorCampo;   
            }
            
            if(valorCampo!=""){
                
                if(expresion.test(valorCampo)) return true; 
                else    return false;    
            }else{
                return true;
            }//fin validacion
            
        },
        telefono:function($campo,validacion,parametros){
            var totalDigitos =11;
            var codigo="";
            var extension="";
            var expresionTlf=jValidador.validaciones['telefono'].expr;
            var expresionCel=jValidador.validaciones['celular'].expr;
            var expresionInter=jValidador.validaciones['internacional'].expr;
            
            var valorCampo = $campo.val();
            
            if(parametros.code){
            	
                codigo = $("#"+$campo.attr('id')+"-codigo").val();
                if(codigo=='undefined') codigo="";
                jv.divMsjError  = "#box"+jValidador.replaceAll($campo.attr('id'),"#","");
            } 
            if(parametros.ext){
                totalDigitos+=4;
                extension=$("#"+$campo.attr('id')+"-ext").val();
            }
    
            valorCampo = codigo+valorCampo+extension;
            if(valorCampo!=""){
                
            	
                var celularValido = (expresionCel.test(valorCampo))?1:0;
                var TelefonoValido = (expresionTlf.test(valorCampo))?1:0;
                var internacionalValido =(expresionInter.test(valorCampo))?1:0;
                console.log("valido?",celularValido,TelefonoValido,valorCampo,parametros.tipo);
                if( 
                	parametros.tipo && (parametros.tipo=='telefono' && TelefonoValido==1 ||    
                    parametros.tipo=='celular' && celularValido==1 ||
                    parametros.tipo=='internacional' && internacionalValido==1 || 
                    parametros.tipo=="multiple" && (TelefonoValido==1 || celularValido==1))
                ){
                        
                     return true;
                 }else{
                     return false;
                 }
            }else{
                return true;
            }
        },
        /**
         * verifica la igualdad entre dos campos 
         */
        igualdad:function($campo,validacion,parametros){
            campo = $("#"+parametros.campo);
            
            if($campo.val()==campo.val())
                return true;
            else
                return false;
        },
        /*------------------------------------------------------**/
        contrasenia:function($campo,validacion,parametros){
            
            var expresionMin = jValidador.validaciones['minuscula'].expr;
            var expresionMay = jValidador.validaciones['mayuscula'].expr;
            var expresionNum = jValidador.validaciones['numero'].expr;
            var expresionCaractEsp = jValidador.validaciones['caracteresEsp'].expr;        
            var valorCampo =  $campo.val();
    
            if(valorCampo!=""){
            
                var minuscula = (expresionMin.test(valorCampo))?1:0;
                var mayuscula = (expresionMay.test(valorCampo))?1:0;
                var numero = (expresionNum.test(valorCampo))?1:0;
                var caracterEsp = (expresionCaractEsp.test(valorCampo))?1:0;
                
                if( minuscula==1 && mayuscula==1 && numero==1 && caracterEsp==1 && valorCampo.length >= 8){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        },
        obtErrores:function(){
            return jv.errores;
        }
    };
    
    jValidador.validaciones = {
          obligatorio:{mensaje:"El campo no puede estar vacio"},
          //email:{   expr:/^[_a-zA-Z0-9-]+(.[_a-z0-9-]+)*@[a-zA-Z0-9-]+(.[a-zA-Z0-9-]+)*(.[a-zA-Z]{2,3})$/, mensaje:"El campo debe ser un mail"},
          email:{   expr:/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/, mensaje:"El campo debe ser un mail"},
          numerico:{expr:/^\d+$/,mensaje:"El campo debe ser numerico"},
          moneda : {expr:/^\d+$/,mensaje:"El campo debe ser numerico"},
          decimal:{ expr:/^([0-9])*[.|,]?[0-9]*$/,mensaje :"El campo debe ser numerico con decimales"},
          caracteres:{expr: /^[A-ZñÑa-záéíóúÁÉÍÓÚ ]*$/,mensaje:'El campo solo puede contener caracteres'},
          celular:{expr:/^0?(412|416|414|424|426)\d{7}$/,mensaje:"El formato del celular no es valido"},
          telefono:{expr:/^0?2[0-9]{9,13}$/,mensaje:"El formato del telefono no es valido"},
          caracteresEspeciales:{expr:/^([^(*=;\\)])*$/,mensajes:"Caracteres invalidos en el campo"},      
          tiny:{mensaje:"El campo es obligatorio"},
          alfanumerico:{expr:/^[\dA-ZñÑa-záéíóúÁÉÍÓÚ.,\' ]*$/,mensaje:"El campo solo puede contener letras y numeros"},
          documentacion:{expr:/^(([V|v|E|e|G|g|J|j|P|p|N|n]{1})?\d{7,10})*$/,mensaje:"El campo debe tener el siguiente formato J18935170 o 18935170"},
          programa:{expr:/^[\d\/\.A-Za-z_-]*$/,mensaje:"El campo solo puede contener letras, guion y underscore"},
          minuscula:{expr:/[a-z]/,mensaje:"La contraseña debe tener al menos una minuscula"},
          mayuscula:{expr:/[A-Z]/,mensaje:"La contraseña debe tener al menos una mayuscula"},
          numero:{expr:/[0-9]/,mensaje:"La contraseña debe tener al menos un número"},
          caracteresEsp:{expr:/(\||\!|\"|\#|\$|\%|\&|\/|\(|\)|\=|\'|\?|\<|\>|\,|\;|\.|\:|\-|\_|\*|\~|\^|\{|\}|\+)/,mensaje:"La contraseña debe tener al menos un caracter especial"},
          coordenada:{expr:/^\-?[0-9]{2}\.[0-9]{3,15}/,mensaje:"La coordenada debe tener el siguiente formato:"},
          internacional:{expr:/^\d{9,18}$/,mensaje:"El telefono internacional no es valido"},
          igualdad:{'mensaje':'Los campos no pueden ser iguales'}
            
    }; 
    /**
     *   =============================
     *   DECLARACION DEL PLUGIN 
     *  ============================
     * 
     */
    function jPlugin(config,e){
        
        var $this = $(this);
        return this.each(function(k,v){
            $(this).on('click',function(e){
                v = new jValidador(this,config,e);
            });
            
        });
    };
    /**
     *Inicialización via js 
     */
    $.fn.jValidador = jPlugin;
    //Inicialización via data
    $( document ).on('click',jdValidador,function(e){
        
        if($(this).data('config')!=undefined){
          var  data = $(this).data('config');
        }else{
          var  data = new Object();
        }
        data.viaData=true;
        new jValidador(this,data,e);
    });    
})(jQuery);
/**
 * Verifica si un objeto Radio se encuentra seleccionado
 * @param string nombreRadio Atributo "NAME" del control
 */
function validarRadio(nombreRadio) {

    var nombreRadio = (nombreRadio);

    var control = "input[name=" + nombreRadio + "]";
    control = ($(control).length > 0) ? control : "input[name=\"" + nombreRadio + "[]\"]";
    var type = $(control).prop('type');
    var cont = 0;
    var dataSeleccionada = new Array;
    if ($(control + ":checked").length > 0) {
        radiosSeleccionados = $(control + ":checked");
        $.each(radiosSeleccionados, function () {
            dataSeleccionada.push(this.value);
            cont++;
        });//final foreach
        if (cont == 1) {

            return dataSeleccionada[0];
        } else {

            //return serializar(dataSeleccionada);
            return dataSeleccionada.join(",");
        }


    } else {
        return false;
    }


}//final función
function serializar(arr) {
    var res = 'a:' + arr.length + ':{';
    for (i = 0; i < arr.length; i++) {
        res += 'i:' + i + ';s:' + arr[i].length + ':"' + arr[i] + '";';
    }
    res += '}';
    return res;
}

/**
 * Crea un campo tinyMCE
 *
 * @param string nobreControl
 */

function armarTiny(nombreControl) {
    if (!nombreControl) {
        nombreControl = "textarea.tiny";
    }
    valoresTiny = {
        //mode : "exact",
        selector: nombreControl,
        language: 'es',
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

function convertirByteAMb(bytes) {
    return parseInt(((bytes / 1024) / 1024).toFixed(2));
}

function scroll() {
    $("[data-scroll=true]").on('click', function (e) {
        e.preventDefault();

        var $target = $(this.hash);
        $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
        if ($target.length) {
            var targetOffset = $target.offset().top;
            $('html,body').animate({scrollTop: targetOffset}, 900);
            return false;
        }
    });
}




