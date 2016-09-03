<?php



//require_once 'GeneradorObjeto.class.php';
namespace Jida;
class GeneradorModelo extends GeneradorObjeto{

    private $tabla;

    public function __construct(){
        parent::__construct();
        $this->ubicacion=DIR_APP."Modelos/";
    }
    function obtenerTablas(){
        return $this->bd->obtTablasBD();
    }


	function ubicacion($ubicacion=""){
		if(empty($ubicacion)){
			return $this->ubicacion;
		}
		$this->ubicacion = $ubicacion;
		return $this;

	}
    function generar($tablaBD,$prefijos){

        $this->tabla=$tablaBD;
        $this->nombreObjeto($tablaBD,$prefijos);
        $this->extends="DataModel";
        $this->generarDoc();
        $this->propiedades = $this->definirPropiedadesBD($tablaBD);
        $this->crearClase();
		return true;
    }

    private function generarDoc(){
        $tags=[
            'package'   => 'Aplicacion',
            'category'  =>  'Modelo',
        ];
        $titulo = "Clase Modelo para ".$this->tabla;
        $this->docBlock=$this->generarDocObjeto($titulo,null,$tags);
    }

    private function obtenerMetodos($tablaBD){
        return $this->bd->obtColumnasTabla($tablaBD);
    }
    private function definirPropiedadesBD($tablaBD){
        $metodos = $this->obtenerMetodos($tablaBD);

        $pk="";
        $propiedades=[

        ];
        for($i=0;$i<count($metodos);++$i){

            if(strtolower($metodos[$i]['column_key'])=='pri') $pk=$metodos[$i];
            $propiedades[] =[
                'propiedad'     =>      $metodos[$i]['column_name'],
                'ambito'        =>      'public',
                'doc'           =>      $this->generarDocPropiedad($metodos[$i]['data_type'], $metodos[$i]['column_name']),
            ];
        }
        if(empty($pk)) throw new Exception("La tabla $tablaBD no tiene definida una clave primaria", 200);
        $propiedades[]=['propiedad'=>'pk','ambito'=>'protected','valor'=>$pk['column_name']];
        $propiedades[]=[
                    'propiedad'         =>      'tablaBD',
                    'ambito'            =>      'protected',
                    'valor'             =>      $tablaBD
                ];
        return $propiedades;
    }

    private function  generarDocPropiedad($type,$prop,$comment=""){
        $doc  ="\t/**\n";
        $doc.="\t* @var ".$type." $prop ".$comment."\n\t*/";
        return $doc;

    }



}

