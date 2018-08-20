<?php

/**
 * Helper para manejo de Archivos y directorios
 *
 * @package  Framework
 * @category Helpers
 * @author   Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package  Framework
 * @category Helpers
 * @version  0.1
 */

namespace Jida\Helpers;

class Directorios extends \Directory {

    /**
     * @internal Verifica si un directorio existe, hace uso de funcion file_exists de PHP
     * @method validar
     *
     * @param string $dir Ubicacion de la carpeta o archivo
     *
     * @see      PHP file_exists
     * @access   public
     * @since    0.1
     *
     */
    static function validar ($dir) {

        if (file_exists($dir)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @internal Crea un directorio
     * @method crear
     *
     * @param mixed $directorio String o Arreglo de Directorios a crear
     *
     * @access   public
     * @since    0.1
     *
     */
    static function crear ($directorio, $mode = 0777) {

        if (is_array($directorio)) {

            foreach ($directorio as $key => $dir) {
                if (!self::validar($dir)) {
                    mkdir($dir, $mode, true);
                }
            }

        }
        else {
            if (!file_exists($directorio)) {
                mkdir($directorio, $mode, true);
            }
        }
    }

    /**
     * @internal muestra todos los archivos de un directorio
     * @method listar
     *
     * @param string direccion donde se va a buscar
     *
     * @return array todos los archivos y carpetaS
     * @access   public
     * @since    0.1
     *
     */
    static function listar ($ruta) {

        $listado = [];
        if (is_dir($ruta)) {
            if ($directorio = opendir($ruta)) {
                while (($file = readdir($directorio)) !== false) {
                    if ($file != "." and $file != '..') {
                        $listado[] = $file;
                    }
                }
            }
        }

        return $listado;
    }

    /**
     * Funcion que recorre y lista todos archivos segun el patron contenido en $expReg
     * @method  listarDirectoriosRuta
     *
     * @param string $ruta Directorio a recorrer
     * @param string $arr Arreglo que guarda los archivos recorridos
     * @param string $expReg Expresion regular para filtrar por el nombre del archivo
     * @param string $i Indice
     *
     * @return $arr Array con todos las coincidencias de $expReg
     * @access public
     * @since  0.1
     *
     */
    static public function listarDirectoriosRuta ($ruta, &$arr, $expReg = '', &$i = 0) {

        if (is_dir($ruta)) {
            if ($directorio = opendir($ruta)) {

                while (($file = readdir($directorio)) !== false) {

                    // Se lista lo que hay en el directorio, mostraría tanto archivos como directorios
                    if (empty($expReg)) {
                        // Guarda los archivos recorridos
                        if ($file != "." and $file != "..") {
                            $arr[$i] = $file;
                        }
                        ++$i;
                    }
                    else {
                        // Guarda los archivos que coincidan con la expresion regular
                        $esCoincidencia = (preg_match($expReg, $file)) ? 1 : 0;
                        if ($esCoincidencia) {
                            $arr[$i] = Cadenas::removerAcentos($file);
                            ++$i;
                        }
                    }
                    if (is_dir($ruta . $file) && $file != "." && $file != "..") {
                        // Solo si el archivo es un directorio, distinto a "." y ".."
                        self::listarDirectoriosRuta($ruta . $file . "/", $arr, $expReg, $i);
                    }
                }

                closedir($directorio);
            }

        }
        else {
            throw new \Exception("La ruta a listar no es una ruta valida $ruta", 333);
        }

        return $arr;
    }

    /**
     * Elimina un directorio y su contenido
     *
     * @internal Se debe tener cuidado de su uso pues elimina absolutamente todo lo contenido en la
     *  carpeta pasada
     * @method eliminar
     * @access   public
     * @since    0.1
     *
     */
    static function eliminar ($dir) {

        foreach (glob($dir . "/*") as $files) {
            if (is_dir($files)) {
                self::eliminar($files);
            }
            else {
                unlink($files);
            }
        }

        if (rmdir($dir)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Limpia un directorio
     *
     * @internal Elimina todo lo que exista dentro de un directorio
     * @method limpiar
     *
     * @param url $directorio Ubicación del directorio a limpiar
     *
     * @access   public
     * @since    0.1
     *
     */
    static function limpiar ($dir) {

        foreach (glob($dir . "/*") as $files) {
            if (is_dir($files)) {
                self::eliminar($files);
            }
            else {
                unlink($files);
            }
        }
    }

    /**
     * Cuenta los archivos en un directorio
     * @method getTotalArchivos
     *
     * @param string $ruta Ruta del directorio
     *
     * @access public
     * @since  0.1
     *
     */
    static function getTotalArchivos ($ruta) {

        $totalArchivos = 0;
        if ($handle = opendir($ruta)) {
            while (($file = readdir($handle)) !== false) {
                if (!in_array($file,
                              [
                                  '.',
                                  '..'
                              ]) && !is_dir($ruta . $file)
                )
                    $totalArchivos++;
            }
        }

        return $totalArchivos;
    }

    /**
     * Copia el contenido de un directorio a otro
     * @method Copiar
     *
     * @param string $origrn archivo donde se encuentra el archivo
     * @param string $origrn archivo donde se encuentra el archivo
     *
     * @patrom Patron para contar Ejemplo {*.jpg,*.gif,*.png}
     * @access public
     * @since  0.1
     *
     */
    static function copiar ($origen, $destino) {

        if (is_dir($origen) and is_readable($origen)) {

            if (!self::validar($destino))
                self::crear($destino);
            $origenDir = dir($origen);
            while (($file = $origenDir->read()) !== false) {
                if ($file == '.' or $file == '..')
                    continue;

                if (is_dir($origenDir->path . $file)) {

                    self::copiar($origen . '/' . $file, $destino . '/' . $file);
                    continue;

                }
                else copy($origen . '/' . $file, $destino . '/' . $file);
            }
        }
    }

}