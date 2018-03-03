<?php


namespace Jida\Jadmin\Controllers;

use Jida\Render as Render;
use \Jida\helpers AS Helpers;

class ModulosController extends JController {


    var $manejoParams = TRUE;

    function __construct() {
        parent::__construct();
        $this->layout = "jadmin.tpl.php";
        $this->url = "/jadmin/Modulos/";
    }


    /**
     *
     * @internal esta funcion recorre el directorio de modulos de forma recursiva encontrando
     * todos loa Directorios dentro de una estructura modular y los guarda en un arreglo
     *
     * @method arregloModulos
     * @param $ruta string
     * @param $arr  array
     * @param $bool boolean este valor indica que si la function toma un camino recursivo
     *
     * @access   private
     * @since    0.5
     *
     */
    private function arregloModulos($ruta) {

        $arr = self::listarkeyDir($ruta);

        return $arr;
    }

    private function arregloParatabla() {

        $linea = [];
        $result = [];
        $modulosCreados = self::arregloModulos('Aplicacion\Modulos');

        // helpers\debug::imprimir($modulosCreados,true);

        for ($i = 0; $i < count($modulosCreados) - 1; $i++) {
            $linea[] = '1';
            $linea[] = $modulosCreados[ $i ];
            $linea[] = $modulosCreados[ $i + 1 ];
            $result[] = $linea;
            unset($linea);
            $i++;
        }

        // helpers\debug::imprimir($result,true);

        return $result;

    }

    /**
     *
     * @internal esta funcion realiza un mach entre los directorios en la aplicacion y
     * la declaraciones en __initConfig.php de los modulos si hay diferencia devuelve
     * un arreglo con con dos posiciones 0 direcciones y 1 declaraciones
     *
     * @method machModulo
     * @return array
     * @access   private
     * @since    0.5
     *
     */

    private function machModulo() {

        $declaraciones = $GLOBALS['modulos'];
        $direcciones = self::arregloModulos('Aplicacion\Modulos');

        foreach ($declaraciones as $key => $val) {

            foreach ($direcciones as $key_1 => $val_1) {
                if ($val != 'Jadmin') {
                    if ($val == $val_1) {

                        unset($declaraciones[ $key ]);
                        unset($direcciones[ $key_1 ]);
                        unset($direcciones[ $key_1 + 1 ]);
                        break;

                    }
                } else {
                    unset($declaraciones[ $key ]);

                }

            }//end foreach direcciones

        }//end foreach declaraciones

        $arr = ['direcciones'   => $direcciones,
                'declaraciones' => $declaraciones
        ];

        return $arr;

    }//end method machModulo


    private function mensajeModulo() {
        $mach = self::machModulo();
        $mensaje = '';
        if (count($mach['direcciones']) > 0) {
            $mensaje = "<h3>Los siguientes Modulos existen en el sistema pero no estan declarados en la configuracion :</h3> <br>";
            foreach ($mach['direcciones'] as $key => $value) {
                $mensaje .= '<h4>' . $value . '</h4>';
                $mensaje .= "<br>";
            }
            $mensaje .= "<br>";
        }
        if (count($mach['declaraciones']) > 0) {
            $mensaje .= "<h3>Los siguientes Modulos estan declarados en la configuracion pero no existen en el sistema :</h3> <br>";
            foreach ($mach['declaraciones'] as $key => $value) {
                $mensaje .= '<h4>' . $value . '</h4>';
                $mensaje .= "<br>";
            }
        }
        if ($mensaje != '') {
            return $mensaje;
        }

    }


    public function index() {


        $mensaje = self::mensajeModulo();
        // Helpers\debug::imprimir($mensaje,true);
        $arre = self::arregloParatabla();


        $tabla = new Render\jvista($arre, ['titulos' => ['Nombre',
                                                         'Direccion'
        ]
        ], 'Modulos');

        $tabla->accionesFila([
            ['span'  => 'glyphicon glyphicon-edit',
             'title' => 'Modificar menu',
             'href'  => $this->obtUrl('', [$arre])
            ],
            ['span'        => 'glyphicon glyphicon-trash',
             'title'       => 'Eliminar menu',
             'href'        => $this->obtUrl('', ['{clave}']),
             'data-jvista' => 'confirm',
             'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?'
            ]
        ]);


        $tabla->addMensajeNoRegistros('No hay Modulos Registrados', [
            'link'    => $this->obtUrl(''),
            'txtLink' => 'Registrar modulo'
        ]);
        $tabla->acciones(['nuevo ' => ['href' => $this->obtUrl('nuevo')]]);
        Helpers\Mensajes::crear('alerta', $mensaje);


        $this->data(['mensaje' => $mensaje]);
        $this->data(['tablaVista' => $tabla->obtenerVista()]);


    }


    public function nuevo() {
        $formulario = new \Jida\Render\Formulario('nuevoModulo');
        $formulario->action = $this->obtUrl();
        $formulario->boton('principal')->attr('value', "Crear Modulo");

        if ($this->post('btnModulo')) {

            if ($formulario->validar()) {


                $formulario::msj('suceso', "modulo creado con exito");

                $this->crearModulo($this->post('Nombre_modulo'), $this->post('tipo'));

                $this->redireccionar('index');

            } else {

                $formulario::msj('alerta', "No se guardo el registro");

            }
        }
        $this->dv->form = $formulario->armarFormulario();
    }


    private function crearModulo($name = '', $tipo = 0) {


        if ($name != '') {
            $name = Helpers\cadenas::upperCamelCase($name);
            $Path = 'Aplicacion/Modulos/' . $name;
            if ($tipo == 1) {

                $directorios = [
                    $Path . '/Jadmin',
                    $Path . '/Jadmin/Controllers',
                    $Path . '/Modelos',
                    $Path . '/Jadmin/Vistas',
                ];
                $extends = '\Jida\Jadmin\Controllers\JController';

            } elseif ($tipo == 2) {

                $directorios = [
                    '',
                    $Path . '/Controllers',
                    $Path . '/Modelos',
                    $Path . '/Vistas',
                    $Path . '/Nexos',
                    $Path . '/Elementos',
                    $Path . '/Jadmin',
                    $Path . '/Jadmin/Controllers',
                    $Path . '/Jadmin/Vistas',
                ];
                $extends = '\Jida\Jadmin\Controllers\JController';
                $mixto = '\Jida\Core\Controller';

            } else {
                $directorios = [
                    '',
                    $Path . '/Controllers',
                    $Path . '/Modelos',
                    $Path . '/Vistas',
                    $Path . '/Nexos',
                    $Path . '/Elementos'
                ];
                $extends = '\Jida\Core\Controller';
            }


            Helpers\directorios::crear($directorios);

            if ($tipo != 2) {
                $this->crearArchivosEstandar($name, $directorios, $extends);
            } else {
                $this->crearArchivosEstandar($name, $directorios, $extends, $mixto);
            }


        }

    }


    private function crearArchivosEstandar($nombreArchivo, $directorios, $extiende, $mixto = '') {


        $nombreModelo = Helpers\Cadenas::obtenerSingular($nombreArchivo);
        $nombreArchivo .= 'Controller';

        $arch = fopen($directorios[1] . '/' . $nombreArchivo . '.php', 'w+');
        $content = "<?php \n";

        ob_start();
        include_once '\plantillas\controlador.tpl.php';
        $content2 = $content .= ob_get_clean();


        $content = str_replace("{{{nombreArchivo}}}", $nombreArchivo, $content);
        $content = str_replace("{{{extiende}}}", $extiende, $content);

        fwrite($arch, $content);

        fclose($arch);

        $arch = fopen($directorios[2] . '/' . $nombreModelo . '.php', 'w+');

        $content = "<?php \n";

        ob_start();
        include_once '\plantillas\modelo.tpl.php';
        $content .= ob_get_clean();

        $content = str_replace("{{{nombreModelo}}}", $nombreModelo, $content);

        fwrite($arch, $content);

        fclose($arch);

        $arch = fopen($directorios[3] . '/index.php', 'w+');
        fclose($arch);

        if ($mixto != '') {

            $arch = fopen($directorios[7] . '/' . $nombreArchivo . '.php', 'w+');

            $content2 = str_replace("{{{nombreArchivo}}}", $nombreArchivo, $content2);
            $content2 = str_replace("{{{extiende}}}", $mixto, $content2);

            fwrite($arch, $content2);

            fclose($arch);

            $arch = fopen($directorios[8] . '/index.php', 'w+');
            fclose($arch);

        }


    }


    private function listarKeyDir($ruta, $bool = FALSE) {
        $listado = [];
        if (is_dir($ruta)) {
            if ($directorio = opendir($ruta)) {
                while (($file = readdir($directorio)) !== FALSE) {
                    if ($file != "." and $file != '..') {

                        $listado[] = $file;
                        $listado[] = $ruta . '/' . $file;

                    }
                }
            }
        }

        return $listado;
    }

}
