<?php


 namespace Jida\Jadmin\Controllers;
 use Jida\Render as Render;
 use \Jida\helpers AS Helpers;
class ModulosController extends JController{


   var $manejoParams=true;

  function __construct()
  {
    parent::__construct();
    $this->layout="jadmin.tpl.php";
    $this->url="/jadmin/Modulos/";
  }


  /**
   *
   * @internal esta funcion recorre el directorio de modulos de forma recursiva encontrando
   * todos loa Directorios dentro de una estructura modular y los guarda en un arreglo
   *
   * @method arregloModulos
   * @param $ruta string
   * @param $arr array
   * @param $bool boolean este valor indica que si la function toma un camino recursivo
   * @access private
   * @since 0.5
   *
   */
  private function arregloModulos($ruta,$bool=false,&$arr=[]){

        $dir = self::listarkeyDir($ruta);

       foreach ($dir as $key => $val){

          if ($bool) {

              
              $dir = self::arregloModulos($ruta.'/'.$key,false,$arr);
              $arr[] = $key;
          }elseif ($key=='Modulos') {

              $dir = self::arregloModulos($ruta.'/Modulos',TRUE,$arr);
              continue;

          }

        }

       return $arr;
  }

  private function arregloParatabla(){

    $linea = [];
    $result= [];
    $modulosCreados = self::arregloModulos('Aplicacion\Modulos',true);
    // helpers\debug::imprimir($modulosCreados,true);

    for ($i=0; $i <count($modulosCreados)-1 ; $i++) { 
      $linea[]='1';
      $linea[]=$modulosCreados[$i];
      $linea[]=$modulosCreados[$i+1];
      $result[]=$linea;
      unset($linea);
      $i++;
    }

    return $result;
    
  }

  /**
   *
   * @internal esta funcion realiza un mach entre los directorios en la aplicacion y
   * la declaraciones en initConfig.php de los modulos si hay diferencia devuelve
   * un arreglo con con dos posiciones 0 direcciones y 1 declaraciones
   *
   * @method machModulo
   * @return array
   * @access private
   * @since 0.5
   *
   */

  private function machModulo(){

      $declaraciones = $GLOBALS['modulos'];
      $direcciones = self::arregloModulos('Aplicacion\Modulos',true);

      foreach ($declaraciones as $key => $val) {

        $mach = false;
        foreach ($direcciones as $key_1 => $val_1) {
           
            if ($val == $val_1 || $val=='Jadmin' ) {

              $mach=true;
              unset($declaraciones[$key]);
              unset($direcciones[$key_1]);
              unset($direcciones[$key_1+1]);

              break;
            }

        }//end foreach direcciones

      }//end foreach declaraciones

    $arr=[ 'direcciones'=> $direcciones,
            'declaraciones'=>$declaraciones
      ];

    return $arr;

  }//end method machModulo

  private function mensajeModulo()
  {
        $mach = self::machModulo();
        $mensaje = '';
        if (count($mach['direcciones'])>0) {
          $mensaje = "Los siguientes Modulos existen en el sistema pero no estan declarados en la configuracion : <br>";
          foreach ($mach['direcciones'] as $key => $value) {
              $mensaje .= $value;
              $mensaje.= "<br>";
          }
          $mensaje.="<br>";
        }
        if (count($mach['declaraciones'])>0) {
          $mensaje .= "Los siguientes Modulos estan declarados en el sistema pero no existen en el sistema : <br>";
          foreach ($mach['declaraciones'] as $key => $value) {
              $mensaje .= $value;
              $mensaje.= "<br>";
          }
        }
        if ($mensaje!='') {
              return $mensaje;
        }

  }


  public function index()
  {


    $mensaje = self::mensajeModulo();

    $arre=self::arregloParatabla();

    

    $tabla = new Render\jvista($arre,['titulos'=>['nombre','direccion']],'Modulos');


    $tabla->addMensajeNoRegistros('No hay Modulos Registradas', [
                                                            'link'  =>$this->obtUrl(''),
                                                            'txtLink' =>'Registrar modulo'
                                                            ]);
    $tabla->acciones(['nuevo ' => ['href'=>$this->obtUrl('nuevo')]]);
    Helpers\Mensajes::crear('alerta',$mensaje);



    $this->data(['tablaVista'=>$tabla->obtenerVista()]);


  }


  public function nuevo()
  {
    $formulario = new \Jida\Render\Formulario('nuevoModulo');
    $formulario->action=$this->obtUrl();
    $formulario->boton('principal')->attr('value',"Crear Modulo");

        if ($this->post('btnModulo')) {

          if ($formulario->validar()) {


            $formulario::msj('suceso',"Encomienda creada con exito");
                        
            $this->crearModulo($this->post('Nombre_modulo'));
            // helpers\debug::imprimir($this->post(),true);
            $this->redireccionar('index');

          }else{

            $formulario::msj('alerta',"No se guardo el registro");

          }
        }
    $this->dv->form = $formulario->armarFormulario();
  }



  private function crearModulo($name='') {  
   

    if ($name!='' ) {

          $Path = 'Aplicacion/Modulos/'.$name;

          $directorios = [
                      $Path.'/Controllers',
                      $Path.'/Modulos',
                      $Path.'/Vistas',
                      $Path.'/Nexos',
                      $Path.'/Elementos'
          ];

          Helpers\directorios::crear($directorios);

    }

  }


  private function listarKeyDir($ruta,$bool=false){
    $listado=[];
    if(is_dir($ruta)){
      if($directorio = opendir($ruta)){
        while (($file = readdir($directorio)) !== false) {
          if($file!="." and $file!='..'){

            $listado[$file]=$file;
                        $listado[$ruta.'/'.$file]=0;

          }
        }
      }
    }
    return $listado;
  }

}
