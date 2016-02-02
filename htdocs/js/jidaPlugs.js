if(!jd) var jd = Object();
/**
 * Conjunto de funcionalidades para las vistas
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 1.2
 * @fecha : 13/11/2013 
 */
 jd.vista = function(nombreVista,tipoControl){
      /**
      * Nombre de la vista
      */
     this.nombreVista = nombreVista;
     /**
      * Objeto del selector padre de la vista
      */
     this.seccionVista = $( "#"+this.nombreVista );
     this.pageToCall = this.seccionVista.data('sitio');
     this.tipoControl =(!tipoControl)?1:tipoControl;
     
 };
  
 jd.vista.prototype={
     ini:function(){
         this.armarOpcionesOrden();
         this.seleccionarAllRows();
         this.validarBotones();
         this.validarSeleccion();
         //this.activarBusqueda();
         this.validarOpcionesNoLink();
     },
     
     armarVista: function(){
         
         this.vistaHTML="";
         this.ini();
     },
     /**
      * Valida si la vista permite seleccionar todas las columnas.
      */
     seleccionarAllRows:function(){
         var opcSeleccionar = $( "[data-jvista=seleccionarTodas]").size();
         if(opcSeleccionar>0){
                var ctrlAllColumns = $( "[data-jvista=seleccionarTodas]" );
                ctrlAllColumns.on('click',function(){
                    bool = ($( this ).prop('checked')==true)?true:false;
                    $("input[type=checkbox]").each(function(){
                        $ ( this ).prop('checked',bool);
                    });
                });    
         }
     },
     /**
      * Verifica condición de los botones según tipo de vista
      * 
      * Si el tipo de control es checkbox, la funcion verifica si
      * los botones están disponibles como opción para una opción multiple,
      * si los botones no lo están [deben tener data-multiple=false], entonces los
      * deshabilita.
      */
     validarBotones:function(){
       if(this.tipoControl==2){
           $("input:checkbox").on('click',function(){
               var size = $( 'input:checkbox:checked' ).size();
               var btnNoDisponibles = $("[data-multiple=false]");
               
               if(size>1){
                btnNoDisponibles.attr('disabled',true);
               }else{
                
                btnNoDisponibles.attr('disabled',false);
               }
           });
       }
       
     },
     /**
      * Habilita opción para ordenar vista por los titulos.
      */
     armarOpcionesOrden:function(){
         var arrayTitles = this.seccionVista.find('TH');
         $.each(arrayTitles,function(nroCelda,celda){
             
             
             if($( this ).data('jvista')=='orden'){
                 $( this ).on('click',function(){
                     nombreCelda = $( this ).data('name');
                     indice = $( this ).data('indice');
                     order = $( this ).data('order-celda');
                     if(!vista)
                        vista = new jVista(this.nombreVista);  
                        datos = "jvista=orden";
                        datos +="&campo="+encodeURIComponent(nombreCelda);
                        datos +="&numeroCampo="+encodeURIComponent(indice);
                        datos +="&order="+encodeURIComponent(order);
                        vista.armarVistaToAjax(datos);
                        
                        
                 });
             }//Solo se agrega el evento a los TH con data en orden
         });
     },//fin armarOpcionesOrden
     /**
      * Crea llamada ajax para modificar la vista según la funcionalidad
      * seleccionada
      * 
      */
     armarVistaToAjax:function(datos){
         
         seccionVista = this.seccionVista;
         new jd.ajax({
             metodo:"POST",
             parametros:datos,
             respuesta:"html",
             funcionCarga:function(respuesta){
               seccionVista.html(this.respuesta);  
             },
             url:this.pageToCall
             
         });
     },
     /**
      * Muestra la vista obtenida por medio de llamadaAjax
      */
     mostrarVistaToAjax:function(){
         seccionVista.data("div-eliminar","1");
         
         seccionVista.html( this.vistaHTML);
         $("[data-div-eleminiar=1]").remove();
     },
     checkSeleccion:function(campo,funcionCallback){
        opcion="seleccionar";
        if(campo)
            opcion=campo;
         
         seleccion  = validarRadio(opcion);
        
        if(seleccion===false){
            return false;
        }else{
            return seleccion;
            
        }
     },
     /**
      * Agrega validacion de seleccion al hacer click
      *  
      */
     validarSeleccion:function(usoInterno){
        
        var opcion = "seleccionar";
        if(usoInterno===1){
            
        }
         $ctrl = $("[data-jvista=seleccion]");
         
         if($ctrl.size()>0){
             
             $ctrl.on('click',function(e){
                
                var $this = $( this );
                var msj = ($this.data('msjerror'))?$this.data('msjerror'):"Debe seleccionar un campo";
                if($this.data('jopcion')){
                    opcion = $this.data('jopcion');
                }
                seleccion  = validarRadio(opcion);
                if(seleccion===false){
                    if(typeof bootbox != 'undefined')
                        bootbox.alert(msj);
                    else console.log("Debe seleccionar un campo a modificar");
                    return false;
                }else{
                    key =($( this ).data('jkey'))?$( this ).data('jkey'):'id'; 
                    href = $( this ).prop('href');
                    $( this ).prop('href',href+"/"+key+"/"+seleccion);
                } 
             });
         }
             
     },
     /**
      * Valida si existen opciones en las filas que ameriten el uso de javascript.
      * 
      * Las funciones deben tener un objeto data-jvista=opcion para que funcione.
      * Las opciones disponibles son : modal y confirm
      * para los confirms se debe agregar:
      * <ul>
      * <li> un <strong>data-msj</strong> para los mensajes</li>
      * <li><strong>data-select</strong> Si se desea validar la seleccion de alguna fila de la vista</li>
      * <li><strong>data-jkey</strong> si desea cambiarse el nombre de los parametros a pasar, por defecto será id</li>
      */
     validarOpcionesNoLink:function(){
        
        var opciones = $("[data-jvista=modal]");
        
            $.each(opciones,function(indice,opcion){
                
                $( this ).on('click',function(e){
                    $this = $( this );
                    e.preventDefault();
                    var url = $this.data('link')?$this.data('link'):$this.attr('href');
                          new jd.ajax({
                                url:url,
                                metodo:'GET',
                                respuesta:"html",
                                funcionCarga: function(){
                                    bootbox.dialog({message:this.respuesta});
                                },    
                          });       
                    
                });
                
            });//fin creacion modales
            
        var confirms = $("[data-jvista=confirm]");
        $.each(confirms,function(indice,opcion){
            
            $( this ).on('click',function(e){
                e.preventDefault();
                var link = $( this ).data('link');
                
                var msj =  $(this).data('msj');
                
                if(!link)
                    var link =$(this).attr('href');
                //Se valida si debe haber alguna selección previa
                if($(this).data('select')){
                    vista = new jd.vista;
                    seleccion = vista.checkSeleccion();
                    if(seleccion===false){
                        bootbox.alert("Debe seleccionar un campo");
                        return false;
                    }else{
                        key =($( this ).data('jkey'))?$( this ).data('jkey'):'id';
                        link = link+"/"+key+"/"+seleccion;
                    }
                    
                }
                
                bootbox.confirm(msj,function(response){
                    if(response===true){
                        window.location.href=link;
                    }
                });
            });
        });
     }
     
 };/*fin prototype*/

+function($){
    var contenedor= '[data-liparent]';
    var menu = function(ele){
        $(contenedor).on('click',this.checksubnivel);    
    };
    menu.prototype.checksubnivel=function(){
        var ele = $( this );
        if(ele.children('ul').size()>0){
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
    
    
    var vSelector = '[data-jida="vista"]';
    
    $(vSelector).each(function(){
        var $vista = $(this);
        var id = $vista.attr('id');
        var tipoControl = $vista.data('tipocontrol');
        jdVista = new jd.vista(id,tipoControl);
        jdVista.armarVista();
    });
    
    
    
}(jQuery);


 $( document ).ready(function(){
    
    if( $("[data-dependiente]").size()>0){
        $("[data-dependiente]").each(function(valor,campo){
            
            $campo = $( campo );
            var padre = $campo.data('dependiente');
            var urlAccion = $campo.data('accion');
             
            $("#"+padre).on('change',function(){
                var $padre = $( this );
                var id= $padre.attr('id');
                var v = $padre.val();
                var data = new Object();
                data[id]=v;
                new jd.ajax({
                    metodo:"POST",
                    url: urlAccion,
                    parametros:data,
                    funcionCarga:function(ajax){
                        $(campo).html(this.respuesta);
                    }
                });
            });
        }) ;
    }
    activarPaginador();


    $("[data-liparent] > a").on('click',function(){
        
        ele = $( this ).parent();
        console.log("hola");
        if(ele.children('ul').size()>0){
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
    });
    $('[data-jida="goback"]').on('click',function(){
         window.history.back();
    });
});

function activarPaginador(){
     $("[data-paginador]" ).on('click',function(e){
        var obj = $( this );
        var ul = obj.parents('ul');
        var pagina = location.href;
        e.preventDefault();
        if(pagina.search('pagina')!=-1){
            pagina=pagina.substr(0,pagina.search('pagina')-1);
            
        }
        history.pushState(null,null,pagina+'/pagina/'+obj.data('paginador'));
        
        datos = {'jvista':'paginador','pagina':obj.data('paginador')};
        new jd.ajax({
           parametros:datos,
           url : ul.data('page'),
           
           funcionCarga:function(){
                $("#"+ul.data('selector')).html(this.respuesta);
                activarPaginador();
            }
        });
    });
}

var activarJidaDependientes = function (){
        
        $("[data-dependiente]").each(function(valor,campo){
            
            $campo = $( campo );
            var padre = $campo.data('dependiente');
            var urlAccion = $campo.data('accion');
            $("#"+padre).off();
            $("#"+padre).on('change',function(){
                var $padre = $( this );
                var id= $padre.attr('id');
                var v = $padre.val();
                var data = new Object();
                data[id]=v;
                new jd.ajax({
                    metodo:"POST",
                    url: urlAccion,
                    parametros:data,
                    funcionCarga:function(ajax){
                        $(campo).html(this.respuesta);
                    }
                });
            });
        }) ;
    
};
