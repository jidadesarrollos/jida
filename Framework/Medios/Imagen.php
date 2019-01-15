<?php

/**
 * Clase Helper de Arreglos
 *
 * @package Framework
 * @subpackage Helpers
 * @author  Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @category [Helper]
 * @since 0.1
 */

namespace Jida\Medios;

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;

class Imagen extends Archivo {

    private static $_ce = 50001;

    private $_path;

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
     * Información de la imagen accesible vía getimagedata
     */
    private $_ancho;
    private $_alto;
    private $_tipo;
    private $_atributos;
    private $_peso;

    function __construct($path) {

        $archivoDir = $path; //tratar de manejar casos de dir completo y relativo
        if (!file_exists($archivoDir)) {
            $msj = "El archivo {$archivoDir} que usted indica no existe.";
            Excepcion::procesar($msj, self::$_ce . 001);
        }
        $this->_path = $path;
    }

    /**
     * Obtiene las dimensiones de una imagen
     * @method obtDimensiones
     *
     * @param $img Ruta de la imagen
     * @return array
     *
     */
    function obtDimensiones() {

        $data = [
            'ancho' => $this->_ancho,
            'alto'  => $this->_alto,
            'tipo'  => $this->_tipo,
            'attr'  => $this->_atributos,
        ];

        return $data;

    }

    function redimencionar($nuevoAncho, $nuevoAlto, $nuevoDir = "") {

        $infoImagen = getimagesize($this->_path);

        $anchoActual = $infoImagen[0];
        $altoActual = $infoImagen[1];
        $tipoImagen = $infoImagen['mime'];

        $proporcionActual = $anchoActual / $altoActual;
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

        $imagen = $this->crearLienzo($tipoImagen, $this->_path);
        $lienzo = imagecreatetruecolor($anchoRedimension, $altoRedimension);
        imagecolortransparent($lienzo, imagecolorallocate($lienzo, 0, 0, 0));
        imagealphablending($lienzo, false);
        imagesavealpha($lienzo, true);
        imagecopyresampled($lienzo,
            $imagen,
            0,
            0,
            0,
            0,
            $anchoRedimension,
            $altoRedimension,
            $anchoActual,
            $altoActual);

        if (empty($nuevoDir)) {
            $dirs = explode("/", $this->_path);
            $file = array_pop($dirs);
            $actualDir = implode("/", $dirs);
            $nuevoDir = $actualDir . "/" . $nuevoAncho . "x" . $nuevoAncho . "-" . $file;
        }

        if ($this->salvarImagen($tipoImagen, $lienzo, $nuevoDir)) {
            return $nuevoDir;
        }
    }

    private function crearLienzo($tipoImagen, $url) {

        switch ($tipoImagen) {
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

    private function salvarImagen($tipoImagen, $lienzo, $url, $nombreImagen = "") {

        if (!empty($nombreImagen)) {
            $url = $url . $nombreImagen;
        }

        switch ($tipoImagen) {
            case "image/jpg":
            case "image/jpeg":
                $imagen = imagejpeg($lienzo, $url, 90);
                break;
            case "image/png":
                $imagen = imagepng($lienzo, $url, 2);
                break;
            case "image/gif":
                $imagen = imagegif($lienzo, $url, 90);
                break;
        }

        return true;
    }
}