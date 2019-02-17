<?php
/**
 * Clase Manejadora de archivos
 *
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace Jida\Core\GeneradorCodigo;

trait GeneradorArchivo {

    /**
     * Nombre del Archivo
     */
    var $nombreArchivo;
    /**
     * Extension del archivo
     */
    var $extension;
    /**
     * @property $dir Directorio de ubicacion del archivo;
     */
    var $dir;
    /**
     * Define el contenido del Archivo
     *
     * @var string $contenido ;
     */
    protected $contenido;

    /**
     * Crea un archivo
     * @method public crear
     *
     * @param string $archivo Nombre del Archivo
     */
    function crear($archivo = "", $modo = "w+") {

        if (!empty($archivo)) {
            $this->nombreArchivo = $archivo;
        }

        $this->archivo = fopen($this->nombreArchivo, $modo);

        return $this;
    }

    /**
     * Permite definir u obtener el directorio del archivo
     *
     * Si es pasado un parametro, será definido como el directorio del archivo.
     * Caso contrario se devolvera el directorio existente
     * @method directorio
     *
     * @param url $dir Directorio a definir
     * @return mixed Objeto Instanciado o directorio
     */
    function directorio($dir = "") {

        if (!empty($dir)) {
            $this->dir = $dir;

            return $this;
        }
        else {
            return $this->dir;
        }

    }

    function escribir($contenido = "") {

        if (!empty($contenido))
            $this->contenido = $contenido;
        fwrite($this->archivo, $this->contenido);

        return $this;
    }

    function cerrar() {

        return fclose($this->archivo);
    }

    function saltodeLinea($total = 1) {

        $saltos = "";
        for ($i = 0; $i <= $total; ++$i) {
            $saltos .= "\n";
        }

        return $saltos;

    }

    /**
     * Agrega tabulaciones al archivo
     * @method tab
     *
     * @access protected
     * @param int $cantidad Define el número de tabulaciones a ingresar
     */
    function tab($cantidad = 1) {

        $tabs = "";
        for ($i = 0; $i < $cantidad; ++$i)
            $tabs .= "\t";

        return $tabs;
    }

    function retorno() {

        $this->contenido .= "\r";

        return $this;
    }

    function addContenido($contenido) {

        $this->contenido .= $contenido;

        return $this;

    }

    function imprimirContenido($contenido = "") {

        echo "<pre>";
        echo $contenido;
        echo "</pre>";
    }
}
