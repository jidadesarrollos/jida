<?php

/**
 * Clase Helper de Imagenes
 *
 *
 * ce: 2
 *
 * @package Framework
 * @subpackage Helpers
 * @author  Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @category [Helper]
 * @since 0.1
 */

namespace Jida\Medios\Archivos;

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Archivo;
use Jida\Medios\Debug;

class Imagen extends Archivo {

    private static $_ce = 50001;
    /**
     * Información de la imagen accesible vía getimagedata
     */
    var $ancho;
    var $alto;
    var $tipo;
    var $atributos;
    var $peso;
    private $mimes = [
        'image/gif'                     => 'gif',
        'image/jpeg'                    => 'jpeg',
        'image/png'                     => 'png',
        'image/psd'                     => 'psd',
        'image/bmp'                     => 'bmp',
        'image/tiff'                    => 'tiff',
        'image/jp2'                     => 'jp2',
        'image/iff'                     => 'iff',
        'image/xbm'                     => 'xbm',
        'image/vnd.wap.wbmp'            => 'bmp',
        'image/vnd.microsoft.icon'      => 'ico',
        'application/x-shockwave-flash' => 'swf'
    ];
    private $mimesAceptados = [
        'image/gif'  => 'gif',
        'image/jpeg' => 'jpeg',
        'image/png'  => 'png'
    ];
    /**
     * @var array $_urls Mapa de urls publicas de las redimensiones o recortes de la imagen
     */
    private $_urls = [];
    /**
     * @var array Mapa de directorio fisico de las redimensiones o recortes de la imagen
     */
    private $_directorios = [];
    /**
     * @var array Data del modelo
     */
    private $data;

    function __construct($directorio = null) {

        if (is_array($directorio) and isset($directorio['directorio'])) {
            $this->data = $directorio;
            $directorio = $directorio['directorio'];
        }

        parent::__construct($directorio);

        if (!$this->_directorio) return false;

        if ($this->data) {
            $this->procesarImagen();
        }
        //if (!self::$valido) return;
        $finfo = new \finfo();
        $fileinfo = explode(";", $finfo->file($directorio, FILEINFO_MIME));
        $mime = $fileinfo[0];

        if (!array_key_exists($mime, $this->mimesAceptados)) {
            $msj = "El archivo {$directorio} de tipo {$mime} usted indica no es aceptado por el Medio.";
            self::$errores[] = $msj;
        }

        $this->_directorios['original'] = $this->ruta;
        $this->_urls['original'] = str_replace(Estructura::$directorio, Estructura::$urlBase, $directorio);

        $this->_obtInformacion();

    }

    private function procesarImagen() {
        if (isset($this->data['meta_data'])) {

        }
    }

    private function _obtInformacion() {

        $infoData = getimagesize($this->ruta);

        $this->ancho = $infoData[0];
        $this->alto = $infoData[1];
        $this->tipo = $infoData['mime'];
        $this->atributos = $infoData[3];
        $this->peso = filesize($this->ruta);

        $this->extension = array_slice(
            explode(".", $this->ruta), -1
        )[0];

    }

    /**
     * Obtiene las dimensiones de la imagen original instanciada
     * @method dimensiones
     *
     * @return array
     *
     */
    function dimensiones() {

        $data = [
            'ancho' => $this->ancho,
            'alto'  => $this->alto,
            'tipo'  => $this->tipo,
            'attr'  => $this->atributos,
        ];

        return $data;

    }

    /**
     * Redimensiona una imagen manteniendo la proporción
     *
     * @param mixed $dimensiones Puede ser un string o un arreglo con varias dimensiones.
     * Las dimensiones deben ser pasadas con la estructura {ancho}x{alto}
     *
     * @param bool $sobreescribir
     * @return bool
     */
    function redimensionar($dimensiones, $sobreescribir = false) {

        if (is_string($dimensiones)) $dimensiones = (array)$dimensiones;

        $response = true;

        foreach ($dimensiones as $item => $dimension) {

            $calculos = $this->_calcularRedimension($dimension);

            $imagen = $this->_crearLienzo($this->ruta);
            $lienzo = imagecreatetruecolor($calculos['ancho'], $calculos['alto']);

            imagecolortransparent($lienzo, imagecolorallocate($lienzo, 0, 0, 0));
            imagealphablending($lienzo, false);
            imagesavealpha($lienzo, true);
            imagecopyresampled(
                $lienzo, $imagen, 0, 0, 0, 0,
                $calculos['ancho'],
                $calculos['alto'],
                $this->ancho,
                $this->alto
            );

            $response = $this->_guardar(
                $lienzo,
                $this->_crearNombre($dimension, $sobreescribir),
                $dimension
            );

        }

        return $response;

    }

    /**
     * Redimenciona una imagen
     * @method _calcularRedimension
     *
     * @internal
     * @param $dimension
     * @return array
     */

    private function _calcularRedimension($dimension) {

        $partes = explode("x", $dimension);
        $proporcionActual = $this->ancho / $this->alto;

        if (count($partes) < 2) {
            Excepcion::procesar("Las dimensiones pasadas no son correctas", self::$_ce . 1);
        }

        $nuevoAncho = $partes[0];
        $nuevoAlto = $partes[1];
        $proporcionRedimension = $nuevoAncho / $nuevoAlto;

        if ($proporcionActual > $proporcionRedimension) {
            $anchoRedimension = $nuevoAncho;
            $altoRedimension = $nuevoAncho / $proporcionActual;
        }
        else {

            if ($proporcionActual < $proporcionRedimension) {
                $anchoRedimension = $nuevoAncho * $proporcionActual;
                $altoRedimension = $nuevoAlto;
            }
            else {
                $anchoRedimension = $nuevoAncho;
                $altoRedimension = $nuevoAlto;
            }

        }

        return ['ancho' => $anchoRedimension, 'alto' => $altoRedimension];

    }

    private function _crearLienzo($url) {

        switch ($this->tipo) {
            case "image/jpg":
            case "image/jpeg":
                $imagen = imagecreatefromjpeg($url);
                break;
            case "image/png":
                $imagen = imagecreatefrompng($url);
                break;
            case "image/gif":
                $imagen = imagecreatefromgif($url);
                break;
        }

        return $imagen;
    }

    private function _guardar($lienzo, $directorio, $dimensiones = "") {

        switch ($this->tipo) {
            case "image/jpg":
            case "image/jpeg":
                imagejpeg($lienzo, $directorio, 90);
                break;
            case "image/png":
                imagepng($lienzo, $directorio, 2);
                break;
            case "image/gif":
                imagegif($lienzo, $directorio);
                break;
        }
        if ($dimensiones) {
            $this->_directorios[$dimensiones] = $directorio;
            $url = str_replace(Estructura::$directorio, Estructura::$urlBase, $directorio);
            $this->_urls[$dimensiones] = $url;
        }

        return true;
    }

    private function _crearNombre($dimension, $sobreescribir = false) {

        if ($sobreescribir) return $this->ruta;

        $dirs = explode("/", $this->ruta);
        $file = array_pop($dirs);
        $file = explode(".", $file);
        $actualDir = implode("/", $dirs);
        $path = "$actualDir/${file[0]}-{$dimension}.{$this->extension}";

        return $path;

    }

    function recortar($alto, $ancho, $x, $y, $w, $h, $sobreescribir = false) {

        if (!$sobreescribir) {
            $dirs = explode("/", $this->_directorio);
            $file = array_pop($dirs);
            $file = explode(".", $file);
            $actualDir = implode("/", $dirs);
            $nuevoDir = $actualDir . "/" . $file[0] . "-" . $ancho . "x" . $alto . "." . $this->extension;
        }
        else {
            $nuevoDir = $this->ruta;
        }

        $lienzo = $this->_crearLienzo($this->_directorio);
        $nuevaImg = imagecreatetruecolor($ancho, $alto);
        imagecopyresampled($nuevaImg, $lienzo, 0, 0, $x, $y, $ancho, $alto, $w, $h);

        if ($this->_guardar($nuevaImg, $nuevoDir)) {
            return $nuevoDir;
        }
    }

    public function obtUrls() {
        return $this->_urls;
    }

    function url($dimension) {

        if (isset($this->_urls[$dimension]))
            return $this->_urls[$dimension];

        return false;

    }

    function editarUrls($dimensiones) {

        foreach ($dimensiones as $dimension => $url) {

            $this->_urls[$dimension] = Estructura::$urlBase . $url;
            $this->_directorios[$dimension] = Estructura::$directorio . $url;

        }

    }

    function eliminar() {

        foreach ($this->_directorios as $archivo) {
            //todo: mover a medio padre Archivos;
            if (file_exists($archivo)) unlink($archivo);
        }

        return true;

    }

    private function _definirURL() {

    }
}