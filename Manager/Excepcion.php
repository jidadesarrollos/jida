<?php
/**
 * Created by PhpStorm.
 * User: Rosmy Rodriguez
 * Date: 23/5/2018
 * Time: 11:32 AM
 */

namespace Jida\Manager;

use App\Config\Configuracion;
use Jida\Componentes\Correo;
use Jida\Configuracion\Config;
use Jida\Core\GeneradorCodigo\GeneradorCodigo;
use Jida\Manager\Excepcion\Log;
use Jida\Manager\Vista\Layout;
use Jida\Manager\Vista\Tema;
use Jida\Manager\Vista\Vista;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

class Excepcion {

    use GeneradorCodigo, Log;
    protected $ruta;
    protected $excepcion;
    protected $txtLog;

    private static $contador;

    const PLANTILLAS_APP = 'Aplicacion/plantillas/';

    function __construct(\Exception $e) {

        $this->nombreArchivo = "error.log";
        $this->ruta = Estructura::path();
        $this->excepcion = $e;

    }

    private function _enviarEmail() {

        $destinatario = Configuracion::EMAIL_SOPORTE;
        $detalle_error = str_replace("\r\n", "<br/>", $this->txtLog);

        $correo = new Correo();
        $correo->plantilla("error");
        $correo->data([
            'aplicacion'    => Configuracion::NOMBRE_APP,
            'detalle_error' => $detalle_error
        ]);
        $correo->enviar($destinatario, "Error generado en " . Configuracion::NOMBRE_APP);

        return $this;

    }

    public static function procesar($msj, $codigo) {
        try {
            throw new \Exception($msj, $codigo);
        }
        catch (\Exception $exception) {
            self::controller($exception);
        }

    }

    private static function imprimir(\Exception $excepcion) {

        $traza = $excepcion->getTrace();
        array_pop($traza);

        $impresion = [
            'message' => $excepcion->getMessage(),
            'code'    => $excepcion->getCode(),
            'trace'   => $traza
        ];

        exit(json_encode($impresion, JSON_PRETTY_PRINT));

    }

    public static function controller($exception) {

        if (!is_a($exception, '\\Exception') and !is_a($exception, '\\Error')) {
            Debug::imprimir(["No se puede procesar la excepcion", $exception], true);
        }

        $config = Config::obtener();
        if ($config::TIPO !== 'WEB') {
            return self::imprimir($exception);

        }

        $layout = Layout::obtener();

        $configuracion = Tema::$configuracion;

        $marco = isset($configuracion->layoutError) ? $configuracion->layoutError : $configuracion->layout;

        $layout->_definirPlantilla("$marco.tpl.php");
        $layout::definirDirectorio(Tema::$directorio);
        $plantilla = self::_obtenerPlantilla($layout, $exception->getCode());

        $data = new \stdClass();
        $data->mensaje = $exception->getMessage();
        $data->codigo = $exception->getCode();
        $traza = $exception->getTrace();
        array_pop($traza);
        $data->traza = $traza;
        $vista = new Vista($data);

        $layout->renderizarExcepcion($vista->obtenerPlantilla($plantilla));

    }

    private static function _obtenerPlantilla(&$layout, $codigo) {

        $directorio = Tema::$directorio;

        $rutaPlantilla = "$directorio/plantillas/$codigo.php";
        $vista = "$codigo.php";

        if (Directorios::validar($rutaPlantilla)) {
            return $rutaPlantilla;
        }

        if (Directorios::validar("$directorio/plantillas/error.php")) {
            return "$directorio/plantillas/error.php";
        }

        $rutaPlantilla = Estructura::$rutaJida . "/plantillas/error/";
        $vista = Directorios::validar($rutaPlantilla . "$codigo.php") ? "$codigo.php" : "error.php";

        return $rutaPlantilla . $vista;

    }

    public static function capturar(\Exception $e) {
        Debug::imprimir(["Capturada Excepcion", $e]);
    }

}