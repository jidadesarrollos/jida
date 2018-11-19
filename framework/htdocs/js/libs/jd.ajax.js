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

function jd() {
    return true;
};
//Definiendo constantes-----------------------------------------------
jd.inicializandoAjax = 0;
jd.cargandoAjaxUno = 1;
jd.cargandoAjaxDos = 2;
jd.listoInteraccionAjax = 3;
jd.listoAjaxCompleto = 4;
jd.contentTypeForm = "application/x-www-form-urlencoded";

jd.ajax = function (json) {
    try {
        this.parametros = json;
        this.valores = this.inicializarValores();
        this.enviarData();
    } catch (err) {
        console.error(err);
    }

};


jd.ajax.prototype = {

    valoresPredeterminados: {
        "contentType": "application/x-www-form-urlencoded",
        "metodo": "POST",
        "funcionCarga": null,
        "contentype": true,
        "parametros": null,
        "respuesta": "html",
        "cargando": "<div class='cargaAjax'> Cargando...</div>",
        "funcionProgreso": false,
        "pushstate": false
    },
    inicializarValores: function () {
        var valores = $.extend(this.valoresPredeterminados, this.parametros);

        return valores;

    },
    httpr: function () {
        var xmlhttp = false;
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                xmlhttp = false;
            }

        }//fin catch.
        if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
            xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
    },
    enviarData: function () {
        var data = "s-ajax=true";
        this.obAjax = this.httpr();
        objeto = this;
        ajax = this.obAjax;

        ajax.onreadystatechange = function () {
            objeto.Listo.call(objeto);
        };

        if (typeof this.valores.parametros == 'object' && this.valores.parametros != null) {
            data += "&";
            $.each(this.valores.parametros, function (key, value) {
                data += "&" + encodeURI(key) + "=" + encodeURI(value);
            });
        } else if (typeof this.valores.parametros == 'string') {
            data += "&" + this.valores.parametros;
        }

        if (this.valores.metodo == 'get' || this.valores.metodo == 'GET')
            this.valores.url += '?' + data;

        ajax.open(this.valores.metodo, this.valores.url, true);
        //validar contentype
        if (this.valores.contentype == true) {
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax.setRequestHeader('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
            ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        }//fin if


        // else{
        // throw "No se encuentra definido correctamente el objeto parametros";
        // }
        ajax.send(data);
        setTimeout(function () {
            console.log(ajax.readyState);
            if (ajax.readyState == 1 || ajax.readyState == 0) {
                ajax.abort();
                console.log("La llamada ajax a tardado demasiado");
            }
        }, 15000);

    },
    Listo: function () {

        ajax = this.obAjax;
        if (ajax.readyState == jd.cargandoAjaxUno || ajax.readyState == jd.cargandoAjaxDos) {
            $(".cargaAjax").remove();
            $('body').prepend(this.valores.cargando);
        }
        if (ajax.readyState == jd.listoAjaxCompleto) {
            $(".cargaAjax").remove();
            var httpStatus = ajax.status;
            if (httpStatus == 200 || httpStatus == 0) {
                //this.valores.funcionCarga.call(this);
                this.procesarRespuesta();

                if (typeof this.valores.pushState !== false) {
                    this.validarPushState();
                }
                this.valores.funcionCarga.call(this);

            } else {
                this.errorCarga();
            }//fin if httpStatus
        }//fin if readyState
    },//fin funcion Listo
    validarPushState: function () {

        if (typeof this.valores.pushState == 'object') {
            pushStateDefault = {'id': null, 'title': null, 'url': null};
            state = $.extend(pushStateDefault, this.valores.pushState);
            history.pushState(state.id, state.title, state.url);
        } else if (typeof this.valores.pushState == 'string') {
            history.pushState(null, null, this.valores.pushState);
        }
        window.addEventListener('popstate', function (e) {
            console.log('im here');
        });
    },
    /**
     * Muestra un error posible en la ejecución de la llamada ajax.
     */
    errorCarga: function () {
        window.location.href = window.location.pathname + "#error";
        console.log("Error estatus: " + this.obAjax.status);
    },
    /**
     * Procesa la respuesta obtenida del servidor
     *
     */
    procesarRespuesta: function () {
        var respuesta;
        switch (this.valores.respuesta) {
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


