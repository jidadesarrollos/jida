/**
 * Plugin para manejo de formularios
 * @author Julio Rodriguez
 * @version 0.1 15/11/2014
 */


+function () {

    ControlCarga = function (ele, opcs) {

        this.inicializarValores(opcs);
        this.init(ele, opcs);
    };
    ControlCarga.prototype = {
        inicializarValores: function (opciones) {
            var valores = {
                multiple: false,

            };
            if (typeof opciones == 'string') {
                this.botonCarga = opciones;
                console.log(this.botonCarga);
                this.conf = valores;
                if ($(this.botonCarga).length < 1)
                    throw console.log("No se encuentra definido el boton de envio");
            } else {

                this.conf = $.extend(valores, opciones);
            }


        },
        init: function (ele, opciones) {
            obj = this;

            var ele = $(ele);
            //Creacion de objeto file
            var sCarga = $('<input type="file"/>').css({'display': 'none', 'bottm': '0', 'position': 'absolute'});
            if (obj.conf.multiple) {
                sCarga.prop('multiple', true);
                sCarga.attr('name', sCarga.attr('name') + "[]");
            }
            ele.after(sCarga);
            ele.on('click', function () {
                sCarga.click();
            });

            //validacion de cambios en el objeto file
            sCarga.on('change', function () {
                var archivos = this.files;

            });
            //carga del archivo
            console.log(obj.botonCarga);
            $(obj.botonCarga).on('click', function (e) {
                e.preventDefault();

                archivos = sCarga[0].files;
                console.log(archivos);
                formData = new FormData();
                for (i = 0; i < archivos.length; ++i) {
                    formData.append('archivos[]', archivos[i], archivos[i].name);
                }
                new jd.ajax({
                    url: '/excel/carga-archivo',
                    file: formData,
                    respuesta: "html",
                    funcionCarga: function () {
                        $("#respuestaCarga").html(this.respuesta);
                    }
                });
            });

        }
        //      padre.html(ele.html()+"<button>Cargar Archivo</button>");
//        ele.css({"display":"none","position":"absolute","bottom":"0"});


    };
    cargaArchivo = function (opciones) {
        jd.cargador(this, opciones);
    };

    function Plugin(opciones) {
        return this.each(function () {
            var $this = $(this);
            new ControlCarga($this, opciones);
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
