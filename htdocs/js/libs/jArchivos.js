
(function($){
	
	var jCargaFile = function(objeto,config,event){
		this.objeto = objeto;
		this.configuracion = config;
		this.$obj = $( this.objeto );
		this.init();
		this._FileReader = new FileReader();
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
		
		_obtConfiguracion:function(){
			var defaultConfig ={
				'preCarga':this._defaultPrecarga,
				'onLoadArchivo':this._defaultOnload,
				'postCarga':function(){},
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
		_managerChange:function(e){
			var ele = e.target;
			var plugin = this;
			this._archivosSeleccionados = ele.files.length;
			plugin._configuracion.preCarga.call(plugin,e);
		},
		_managerLoadEnd:function(e){
			var ele = e.target;
			var plugin = this;
			++plugin._archivosCargados;
			
			if(this._configuracion.btnCarga){
				$(this._configuracion.btnCarga).on('click',this._postData.bind(this));
			}else
			if(plugin._archivosCargados == plugin._archivosSeleccionados && this._configuracion.url){
				
				this._postData();
				
			}
		},
		_postData : function(){
				var form = new FormData();
				var plugin = this;
				var name = (plugin._archivosCargados>1)?plugin._configuracion.name+"[]":plugin._configuracion.name;
				[].forEach.call(plugin._archivos,function(archivo){
					
					form.append(name,archivo);
				});
				$.ajax({
					'url':this._configuracion.url,
					'type':'post',
					'processData':false,
					'contentType': false,
					'data':form,
					'dataType':'json',
					success:plugin._configuracion.postCarga.bind(plugin)
				});
		},
		_manejarEventos:function(){
			
			var plugin = this;
			
			this.$obj.on('click',function(e){
				 plugin._configuracion.preCarga.call(plugin,e);
				this.$file.trigger('click');
				this.file.addEventListener('change',this._managerChange.bind(this));
				
			}.bind(this));
		},
		
		_defaultPrecarga:function(event){

			var ele = event.target;
			var plugin = this;
			var archivos = ele.files;
			this._archivos = archivos;
			if(archivos){
				band = 0;
				[].forEach.call(archivos,function(archivo){
					archivo.id_app = band;
					++band;
					var plugin = this;
					var reader = new FileReader();
					reader.addEventListener('load',plugin._configuracion.onLoad.bind(plugin),false);
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
		console.log(i,elem);
		new jCargaFile( elem );
	});
	
})(jQuery);

