var jModulo="/jadmin/";
$(function(){
    //Add imagenes
    var $listaImagenes = $('#lista-imagenes');
    $("#btnAddImg").on('click',function(){
       var $lista = $(".ui-widget-content.ui-selected");
       if($lista.length>0){
            var arrayImagenes= new Array();
            var arrayData  = new Array();
            var $tiny = $("#contenido");
            var data="";
            $lista.each(function() {
                $this = $(this);
                id = $this.data('imagen');
                arrayImagenes.push(id);
                infoImg = $this.data('inf');
                data += '<div class="row"><div class="col-md-12 col-xs-12 col-sm-12">';
                data +='<figure id="figureImg-'+infoImg.id+'">';
                data +='<img src="'+infoImg.html+'" alt="'+infoImg.alt+'" data-inf=\''+JSON.stringify(infoImg)+'\' class="img-center img-responsive"/>';
                if(infoImg.descripcion!='' && infoImg.descripcion!=null && infoImg!='null'){
                    data +='<figcaption>'+infoImg.descripcion+'</figcaption>';
                }

                data +='</figure>';
                data+='</div></div><p>&nbsp;</p>';
            });
            tinymce.get('contenido').insertContent(data);
            bootbox.hideAll();
       }else{
           bootbox.alert("Debes seleccionar al menos una imagen para poder agregarla");
       }

    });
    $('#btnAddPortada').on('click',function(){
        var $lista = $(".ui-widget-content.ui-selected");
        var $data = $lista.data('inf');
        var $panel = $("#panelImgPortada");
        var htmlImg = '<figure><img src="'+$data.html+'" alt="'+data.alt+'" class="img-responsive"/>';
        htmlImg+='<div class="text-img"><a href="#" data-accion="remover"><span class="fa fa-trash" title="Eliminar Portada"></span></a></div></figure>';
        $("#id_media_principal").val($data.id);
        $panel.html(htmlImg);
        if(parseInt($panel.data('post'))>0){
            new jd.ajax({
                url:jModulo+'posts/guardar/',
                parametros:{'elemento':'id_media_principal','post':$panel.data('post'),'id':$data.id},
                funcionCarga:function(){

                }
            });
        }
        bootbox.hideAll();

    });

    $listaImagenes.selectable({

        selecting:function(event,ui){
            if(!$listaImagenes.data('multiple')){
                $('.ui-selected').removeClass('ui-selected');
             }

        },
        selected:function(event,ui){

            var $imagen = $(ui.selected);
            var id = $imagen.data('imagen');
            var $lista = $("#lista-imagenes");
            var $btnPortada = $("#btnAddPortada");
            console.log("llego ak ."+$(".ui-selected").length);
            if($(".ui-selected").length==1){
                $("#btnAddPortada").attr('readonly',false);
                if(typeof id!='undefined'){
                    new jd.ajax({
                       url:jModulo+"galeria/form-media/",
                       respuesta:"html",
                       contexto:'#data-imagen',
                       parametros:{media:id},
                       funcionCarga:function(){
                           $("#data-imagen").html(this.respuesta);
                       }
                    });
                }
            }else{
                if(!$btnPortada.hasClass('oculto')){
                    $btnPortada.addClass('oculto').fadeOut();
                }
                $("#btnAddPortada").attr('readonly',true).fadeOut();

            }

        },
        unselected:function(){
            var $btnPortada = $("#btnAddPortada");
            if($(".ui-selected").length==1){
                if($btnPortada.hasClass('oculto')){
                    $btnPortada.removeClass('oculto').fadeIn();
                }
                $btnPortada.attr('readonly',false);
            }
        }

    });
    cargarArchivo({
     btn:'btnCargaImagen',
     url:jModulo+'galeria/cargar-imagen',
     nombreArchivo:"imagenesGaleria",
     data:'s-ajax:true',
     callback:function(file,respuesta){

        var $divGalery = $( "#galeria-media");
        var dataImagenes = $divGalery.html();
        if($divGalery.find('ul').length==0){
            $divGalery.html('<ul class="list-inline" id="lista-imagenes"></ul>');
        }
        var $list = $("#lista-imagenes");
        var resp = JSON.parse(respuesta);

         $("#btnCargaImagen").html("Agregar m&aacute;s Imagenes");
        this.enable();
        $("#btnCargaImg").html("Cargar Otra");
        if(!resp.error){
            imas = "";

            $.each(resp.imagenes, function(id,imagen) {
                imas+='<li ';
                imas+='data-inf=\'{'+'"html":"'+resp.directorio+resp.dataImg.objeto_media+'","id":"'+resp.dataImg.id+'","descripcion":"","alt":""}\' ';
                imas+='class="ui-widget-content" data-imagen="'+resp.dataImg.id+'"><figure><img src="'+resp.directorio+imagen.nombre+"-min."+imagen.ext+'"';
                imas+=' alt="img add"></figure></li>';
            });
            $list.append(imas);

        }else{
            $('#galeria-media').before('<div class="alert alert-danger">'+resp.msj+'</div>');
        }
     }
    });

});

