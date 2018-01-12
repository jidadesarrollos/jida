/**
 *
 *  jidaControl Controlador de mascara de campos
 *
 * Plugin Jquery y HTML5 para manejo de controles de formularios
 *
 * @author : Julio Rodriguez <jirc48@gmail.com>
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
function replaceAll(value, charte) {
    var result = value;
    var posi = value.indexOf(charte);
    if (posi > -1) {
        while (posi > -1) {
            result = value.substring(0, posi);
            result = result + value.substring(posi + 1);
            posi = result.indexOf(charte);
            value = result;
        }
    }
    return (result);
}//final funcion
//========================================================
(function ($) {

    $.fn.jidaControl = function () {
        elemento = this;
        // $( "body" ).on('click','[data-jidacontrol]',function(){
//         	
//         	
        // a = new jd.controladorInput( this );
        //
// });

        elemento.each(function () {
            if (!$(this).data('jidacontrolaply')) {
                new jd.controladorInput(this);
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
jd.controladorInput = function (control, elemento) {
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
jd.controladorInput.prototype = {
    /**
     * Funcion que inicializa la validacion
     */
    inicializador: function () {
        formato = "";
        /*Validar que exista la validación capturada en el data-jidacontrol*/
        if (this.validaciones[this.validacion]) {

            /**
             * Identifica cual sera el controlador a utilizar
             */
            patronXDefault = {tipo: 1};
            /**
             * JSON con validación con keys de expresión regular aplicada
             * y tipo de controlador a aplicar
             */
            patronDeValidacion = this.validaciones[this.validacion];

            patron = $.extend(patronXDefault, patronDeValidacion);
            //Se determina el controlador a utilizar
            controladores = ['controlador', 'controladorCaracter', 'controladorDecimal'];
            /**
             * Nombre del controlador a utilizar
             */
            funcionControlador = controladores[patron.tipo];
            var idControl = "#" + this.controlObject.prop('id');
            /**
             * Se llama a la funcion controladora en el evento keypress para que evalue
             * el formato
             */

            $(this.controlObject).data('jidacontrolaply', true);
            $(this.controlObject).on('keypress',

                {
                    validacion: objeto.validaciones[this.validacion].cadena,
                    formato: objeto.formatosDisponibles[this.validacion]
                },
                objeto[funcionControlador]);
            /**
             * Se agrega un llamado al formateador en el evento keyup para
             * @see formateador
             */
            $(this.controlObject).on('keyup',

//                              idControl,
                {
                    formato: objeto.formatosDisponibles[this.validacion]
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
    controladorDecimal: function (e) {

        tecla = String.fromCharCode(e.which);
        key = e.which;
        isCtrl = false;
        if (e.which == 8 || e.which == 9 || e.keyCode == 9 || e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40 || e.keyCode == 222
            || e.which == 222
        ) return true;
        if (key == 17) isCtrl = true;

        if (isCtrl == true && (key == 37 || key == 39 || key == 46 || key == 161 || key == 225 || key == 17 || key == 18)) {
            e.preventDefault();

        } else {
            //-------------------------

            patron = e.data.validacion;
            if (!patron.test(tecla)) {
                e.preventDefault();
            } else {
                //Definir cantidad de decimales

                decimal = $(this).data('decimal');
                decimal = (typeof(decimal) == "undefined") ? 0 : decimal;
                elemento = $(this);
                //obtener valor del elemento con formato
                valorNumero = elemento.val();
                tamValorNumero = valorNumero.length + 1;

                if (tamValorNumero >= decimal + 1) {
                    //eliminar formato de miles al value
                    numeroSinFormato = replaceAll(valorNumero, '.');
                    if (valorNumero.indexOf(",") >= 0)
                    //eliminar coma de decimales si existe
                        numeroSinFormato = valorNumero.replace(",", '');
                    numeroSinFormato = numeroSinFormato + tecla;

                    numA = numeroSinFormato.substr(numeroSinFormato.length - decimal);

                    //volver a validar el formato y eliminarlo
                    numSinPunto = replaceAll(numeroSinFormato.substr(0, numeroSinFormato.length - decimal), '.');
                    //agregar el numero seleccionado para la validacion
                    numSinPunto = numSinPunto;

                    numB = "";
                    i = 1;
                    //----------------------
                    while (numSinPunto.length > 3) {
                        numB = "." + numSinPunto.substr(numSinPunto.length - 3) + numB;
                        numSinPunto = numSinPunto.substring(0, numSinPunto.length - 3);

                    }//fin while
                    //----------------------
                    numB = numSinPunto + numB;
                    if (decimal > 0) {

                        numeroFinal = numB + "," + numA;
                    } else
                        numeroFinal = numB;
                    elemento.val(numeroFinal);
                    e.preventDefault();
                }//fin mayor a 3 sin decimales
                else {
                    //	console.log("nosilve");
                }
            }//fin if validacion cadena
            //-------------------------
        }//final if...else
    },
    /**
     * Controlador  por caracteres
     * TIPO : 1
     * Valida un campo, evaluando solamente el caracter ingresado en el momento
     */
    controladorCaracter: function (e) {


        tecla = String.fromCharCode(e.which);
        key = e.which;
        isCtrl = false;
        /*Permitir borrar y tab*/

        if (e.which == 8 || e.which == 9 || e.keyCode == 9 || e.keyCode == 37 || e.keyCode == 39 || e.keyCode == 46 || e.keyCode == 222
            || e.which == 222) return true;


        if (key == 17) isCtrl = true;
        if (isCtrl == true && (key == 37 || key == 39 || key == 46 || key == 161 || key == 225 || key == 17 || key == 18)) {
            e.preventDefault();

        } else {
            patron = e.data.validacion;
            if (!patron.test(tecla)) {
                e.preventDefault();
            } else {
                decimal = $(this).data('decimal');
                if (decimal) {

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
    controlador: function (e) {

        tecla = String.fromCharCode(e.which);
        key = e.which;
        isCtrl = false;
        //PERIMITIR BORRAR

        if (e.which == 8 || e.which == 9 || e.keyCode == 9 || e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40 || e.keyCode == 222
            || e.which == 222) return true;
        if (key == 17) isCtrl = true;

        if (isCtrl == true && (key == 37 || key == 39 || key == 46 || key == 161 || key == 225 || key == 17 || key == 18)) {
            e.preventDefault();

        } else {

            patron = e.data.validacion;

            formato = e.data.formato;
            cadenaInsertada = this.value + tecla;
            tamCadena = cadenaInsertada.length;
            //Merge de cadena insertada con el "formato" esperado
            cadenaValidada = cadenaInsertada + formato.substr(tamCadena);

            //Comparamos el patron de la Exp Regular con la cadena insertada
            if (patron.test(cadenaValidada)) {
                return true;
            } else {
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
        function (e) {
            /**
             * Cadena escrita por el usuario
             */
            cadenaInsertada = $(this).val().toUpperCase();

            //tamanio de la cadena actual
            tamCadena = cadenaInsertada.length;
            //formato de la cadena requerida
            if (e.data.formato) {
                formato = e.data.formato;
                //tamaño de la cadena requerida.
                tamanioFormato = formato.length;
            } else {
                tamanioFormato = 100;
            }


            if (e.which != 8) {
                caracteresDeSeparacion = /^[\/.\-]{1}$/;
                proximoCaracter = formato[tamCadena];

                if (tamanioFormato >= tamCadena) {

                    caracterEsperado = formato[tamCadena - 1];
                    caracterIngresado = cadenaInsertada[tamCadena - 1];
                    //console.log("dentro del primer if "+ caracterEsperado);
                    if (caracteresDeSeparacion.test(caracterEsperado)) {

                        cadenaInsertada[tamCadena - 1] = caracterEsperado;

                        caracterIngresado = (caracterIngresado != caracterEsperado) ? caracterIngresado : "";
                        $(this).val(cadenaInsertada + caracterIngresado);

                    } else if (caracteresDeSeparacion.test(proximoCaracter) && proximoCaracter != caracterEsperado) {
                        $(this).val(cadenaInsertada + proximoCaracter);
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
    validaciones: {
        numerico: {cadena: /^[0-9]*$/, tipo: 1},
        cedula: {cadena: /^([V|E|G|J|P|N]\-{1}\d{8})*$/, tipo: 0},
        rifConFormato: {cadena: /^([V|E|G|J|P|N]\-{1}\d{8}-{1}\d{1})*$/, tipo: 0},
        rif: {cadena: /^([V|v|E|e|G|g|J|j|P|p|N|n]\d{9})*$/, tipo: 0},
        telefono: {cadena: /^(\d{11})*$/, tipo: 1},
        miles: {cadena: /^[0-9]*$/, tipo: 2},
        caracteres: {cadena: /^[A-ZñÑa-z ]*$/},
        alfanumerico: {cadena: /^[0-9A-ZñÑa-z ]*$/},
        coordenada: {cadena: /^\-?[0-9]{2}\.[0-9]{3,15}/, tipo: 0},
        fecha: {cadena: /^\d{2,4}[\-|\/]{1}\d{2}[\-|\/]{1}\d{2,4}$/, tipo: 0}
        //cedula : {cadena:/^([V|E]\-{1}\d{8})*$/},
        //cedula : /^([VEJG]\d{7,8})$/,
    },
    /**
     * Formatos a aplicar a cada validación que utilice el
     * formateador tipo 0.
     *
     * */
    formatosDisponibles: {

        rif: 'J123456789',
        rifConFormato: 'J-12345678-9',
        fecha: '00-00-0000'

    }


};//final prototype jidaControlador


