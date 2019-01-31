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

namespace Jida\Medios\Archivos;

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Archivo;
use Jida\Medios\Debug;

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

        if (strpos(strtolower($path), strtolower(Estructura::$directorio)) !== false)
            $archivoDir = $path;
        elseif (strpos(strtolower($path), "{raiz}") !== false) {
            $archivoDir = str_replace("{raiz}", Estructura::$directorio, $path);
        }
        else {
            $archivoDir = $path;
        }
        if (!file_exists($archivoDir)) {
            $msj = "El archivo {$archivoDir} que usted indica no existe.";
            Excepcion::procesar($msj, self::$_ce . 001);
        }

        $finfo = new \finfo();
        $fileinfo = explode(";", $finfo->file($path, FILEINFO_MIME));
        $mime = $fileinfo[0];

        if (!array_key_exists($mime, $this->mimesAceptados)) {
            $msj = "El archivo {$archivoDir} de tipo {$mime} usted indica no es aceptado por el Medio.";
            Excepcion::procesar($msj, self::$_ce . 002);
        }

        $this->_path = $path;
        $infoData = getimagesize($path);
        $this->_ancho = $infoData[0];
        $this->_alto = $infoData[1];
        $this->_tipo = $infoData['mime'];
        $this->_atributos = $infoData[3];
        $this->_peso = filesize($path);
        $this->extension = array_slice(explode(".", $path), -1)[0];

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

    /**
     * Redimenciona una imagen
     * @method redimencionar
     *
     * @param $nuevoAncho Nuevo ancho de la imagen
     * @param $nuevoAlto Nuevo alto de la imagen
     * @param $sobreescribir (opcional) indica si el archivo se va sobreescribir.
     * @return array
     *
     */

    private function _calcularRedimension($dimension) {

        $partes = explode("x", $dimension);
        $proporcionActual = $this->_ancho / $this->_alto;

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
        Debug::imprimir([1, ['ancho' => $anchoRedimension, 'alto' => $altoRedimension]]);
        return ['ancho' => $anchoRedimension, 'alto' => $altoRedimension];

    }

    function redimensionar($dimensiones, $sobreescribir = false) {

        if (is_string($dimensiones)) $dimensiones = (array)$dimensiones;

        foreach ($dimensiones as $item => $dimension) {

            $calculos = $this->_calcularRedimension($dimension);

            $imagen = $this->crearLienzo($this->_path);
            $lienzo = imagecreatetruecolor($calculos['ancho'], $calculos['alto']);

            imagecolortransparent($lienzo, imagecolorallocate($lienzo, 0, 0, 0));
            imagealphablending($lienzo, false);
            imagesavealpha($lienzo, true);
            imagecopyresampled(
                $lienzo, $imagen, 0, 0, 0, 0,
                $calculos['ancho'],
                $calculos['alto'],
                $this->_ancho,
                $this->_alto
            );

        }
        $nuevoDir = $this->_path;

        if (!$sobreescribir) {
            $dirs = explode("/", $this->_path);
            $file = array_pop($dirs);
            $file = explode(".", $file);
            $actualDir = implode("/", $dirs);
            $nuevoDir = "$actualDir /${file[0]}-{$dimension}.{$this->extension}";

        }

        return $this->salvarImagen($lienzo, $nuevoDir);

    }

    private function crearLienzo($url) {

        switch ($this->_tipo) {
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

    private function salvarImagen($lienzo, $url, $nombreImagen = "") {

        if (!empty($nombreImagen)) {
            $url = $url . $nombreImagen;
        }

        switch ($this->_tipo) {
            case "image/jpg":
            case "image/jpeg":
                $imagen = imagejpeg($lienzo, $url, 90);
                break;
            case "image/png":
                $imagen = imagepng($lienzo, $url, 2);
                break;
            case "image/gif":
                $imagen = imagegif($lienzo, $url);
                break;
        }

        return true;
    }

    function recortar($alto, $ancho, $x, $y, $w, $h, $sobreescribir = false) {

        if (!$sobreescribir) {
            $dirs = explode("/", $this->_path);
            $file = array_pop($dirs);
            $file = explode(".", $file);
            $actualDir = implode("/", $dirs);
            $nuevoDir = $actualDir . "/" . $file[0] . "-" . $ancho . "x" . $alto . "." . $this->extension;
        }
        else {
            $nuevoDir = $this->_path;
        }

        $lienzo = $this->crearLienzo($this->_path);
        $nuevaImg = imagecreatetruecolor($ancho, $alto);
        imagecopyresampled($nuevaImg, $lienzo, 0, 0, $x, $y, $ancho, $alto, $w, $h);

        if ($this->salvarImagen($nuevaImg, $nuevoDir)) {
            return $nuevoDir;
        }
    }
}