<?php
/**
 * Clase Modelo
 * @author Julio Rodriguez
 * @package Framework
 * @version 0.1
 * @category Elemento
 * @since 1.4
 */

namespace Jida\Elementos;

use Jida\Core as Core;

class Contenido extends Core\Elemento {

    var $nombre = "Contenido";
    var $id = "Jida.Contenido";
    var $descripcion = "Código HTML o Texto";

    function elemento () {

    }

    function form ($numero = 1) {

        ob_start();
        include_once 'tpls/formContenido.tpl.php';
        $contenido = ob_get_clean();

        return $contenido;
    }

    function gestion ($data) {

        $retorno = [
            'ejecutado' => false,
            'error'     => 'Los datos no son validos'
        ];
        if (
            array_key_exists('titulo-texto', $data) and array_key_exists('contenido-texto', $data) and
            \Validador::validar('obligatorio', $data['contenido-texto'])) {

            $elemento = new Elemento();
            $info = [
                'elemento'      => $data['titulo-texto'],
                'data'          => $data['contenido-texto'],
                'area'          => $data['area'],
                'identificador' => $this->id
            ];
            if ($elemento->salvar($info)->ejecutado()) {
                $retorno = [
                    'ejecutado' => true,
                    'msj'       => "Contenido guardado"
                ];
            }
            else {
                $retorno['error'] = "No se pudo realizar el guardado";
            }

        }

        return array_merge($retorno, $data);

    }
}
