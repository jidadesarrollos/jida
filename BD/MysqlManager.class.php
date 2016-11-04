<?php 
/**
 * Clase Controladora del RDBMS Mysql
 * 
 * Esta clase permite manipular y consultar estructuras generales de la base de datos.
 * @author Julio Rodriguez
 * @version 0.1
 * @package Framework
 * @subpackage BD
 * @class MysqlManager
 */

class MysqlManager extends Mysql{
    
    protected $servidor="127.0.0.1";
    protected $bd = "jidadesa_jida_framework";
    protected $esquemaSistema;
    
    function __construct(){
        parent::__construct();
        $this->esquemaSistema=$this->bd;
    }
    /**
     * Obtiene las tablas de la base de datos
     * @method obtenerTablas
     * @return Array Arreglo de tablas obtenidas
     */
    function obtenerTablas(){
      $q = "select table_name,table_type, table_collation, create_time 
                from information_schema.tables  where table_schema='".$this->esquemaSistema."'
                and table_type!='VIEW' 
            ;";
            //debug::string($q);
        $data = $this->obtenerDataCompleta($q);
      return $data;   
    }
    
    function obtColumnas($tables,$bd=""){
        $q= "select table_schema,table_name,column_name,data_type,column_type,column_key from 
            information_schema.tables where table_schema='".$this->bd."'
            and tables in (".explode(",",$tables)."";
        return $this->obtenerDataCompleta($q);  
        
    }
 
    
}