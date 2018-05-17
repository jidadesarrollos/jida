/**
 * Plugin para carga de archivos
 *
 * jCargaFile
 * @author julio Rodriguez @jr0driguez
 * @version 0.1 2017
 */
(function ($) {

    var jCargaFile = function (objeto, config, event) {
        this.objeto = objeto;
        this.configuracion = config;
        this.$obj = $(this.objeto);
        this.init();
        this._FileReader = new FileReader();
        this._data = {};
        var that = this;
    };
    var selector = '[data-jida="cargaFile"]';

    jCargaFile.prototype = {
        regExps: {
            'imagen': /\.(jpe?g|png|gif)$/i,

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
        _archivosSeleccionados: 0,
        _data: {},

        _obtConfiguracion: function () {
            var defaultConfig = {
                'preCarga': function () {
                },
                'onLoadArchivo': this._defaultOnload,
                'postCarga': function () {
                    console.log("carga default");
                },
                'multiple': false,
                'name': "_jcargaArchivo",
                'btnCarga': false,
                'onLoad': false
            };
            this._configuracion = $.extend(defaultConfig, this.configuracion);
        },

        init: function () {

            this._obtConfiguracion();

            $file = $('<input>').attr({
                'type': 'file',
                'id': this._configuracion.name,
                'name': this._configuracion.name,
                'style': 'display:none',
                'multiple': this._configuracion.multiple

            });
            this.$obj.after($file);
            this.$file = $file;
            this.file = $file.get(0);
            this._manejarEventos();

        },
        /**
         *
         */
        _manejarEventos: function () {

            var plugin = this;

            this.$obj.on('click', function (e) {
                this.$file.off();
                this.$file.trigger('click')
                    .on('change', this._managerChange.bind(this));

            }.bind(this));
        },
        /**
         *
         */
        _managerChange: function (e) {

            var ele = e.target;
            var plugin = this;
            this._archivosSeleccionados = ele.files.length;
            this._defaultPrecarga.call(plugin, e);

            //this.$file.off();
        },
        _managerLoadEnd: function (e) {
            var ele = e.currentTarget;
            var plugin = this;
            ++plugin._archivosCargados;
            if (!!this._configuracion.loadend) {
                this._configuracion.loadend.call(plugin, e);
            }
            if (this._configuracion.btnCarga) {

                $(this._configuracion.btnCarga).on('click', this._postData.bind(this));

            }
            else if (
                (plugin._archivosCargados == plugin._archivosSeleccionados)
                && this._configuracion.url
            ) {

                this._postData();

            }
        },
        _managerOnLoad: function (e) {
            var ele = e.target;
            var plugin = this;

            ele.removeEventListener('load', plugin._managerOnLoad);

            plugin._configuracion.onLoad.call(plugin, e);
        },
        _postData: function () {


            var form = new FormData();
            var plugin = this;
            var name = (plugin._archivosCargados > 1) ? plugin._configuracion.name + "[]" : plugin._configuracion.name;

            [].forEach.call(plugin._archivos, function (archivo) {

                form.append(name, archivo);
            });

            for (key in plugin._data) {
                form.append(key, plugin._data[key]);
            }
            $.ajax({
                'url': this._configuracion.url,
                'type': 'post',
                'processData': false,
                'contentType': false,
                'data': form,
                'dataType': 'json',
                'success': function (r) {
                    plugin.file.value = '';
                    plugin._archivosCargados = 0;
                    plugin._configuracion.postCarga(r);
                },
                'error': function (e) {
                    console.log("error", e);
                }
            });

        },


        _defaultPrecarga: function (event) {

            var ele = event.target;
            var plugin = this;
            var archivos = ele.files;
            this._archivos = archivos;

            var continuar = true;
            if (!!plugin._configuracion.preCarga) {
                continuar = !!plugin._configuracion.preCarga.call(plugin, event);

            }

            if (archivos && continuar) {

                band = 0;
                [].forEach.call(archivos, function (archivo) {

                    archivo.id_app = band;
                    ++band;

                    var reader = new FileReader();

                    reader.addEventListener('load', this._managerOnLoad.bind(plugin), false);
                    reader.addEventListener('loadend', this._managerLoadEnd.bind(plugin), false);
                    reader.readAsDataURL(archivo);

                }.bind(plugin));

            } else {
                this.file.value = "";
            }

        },

        _defaultOnload: function (e) {
            var image = new Image();
            var ele = e.target;
            var plugin = this;

            image.height = 150;
            image.title = ele.title;
            image.src = ele.result;
            $li = $('<li>').html(image);
            $('#imagenes').append($li);
            ++this._archivoCargados;

        },
        _previewImage: function () {

        },
        limpiar: function () {

        }
    };

    /**
     *   =============================
     *   DECLARACION DEL PLUGIN
     *  ============================
     *
     */
    function jPlugin(config, e) {

        var $this = $(this);
        return this.each(function (i, ele) {

            v = new jCargaFile(ele, config, e);

        });
    };
    $.fn.jCargaFile = jPlugin;
    $(selector).each(function (i, elem) {
        new jCargaFile(elem);
    });

})(jQuery);

