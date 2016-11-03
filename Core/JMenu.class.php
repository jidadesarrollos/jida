<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class JMenu extends Selector{
    
    #Aqui tus propiedades publicas================
    
    private $menu;
    private $opciones;
    private $html;
    
    private $colOrden="orden";
    private $colHijos="hijo";
    private $colPadre="padre";
    private $colId="id_opcion_menu";
    private $colIdMenu="id_menu";
    private $colUrl="url_opcion";
    private $colNombre="nombre_opcion";
    private $colEstatus = "id_estatus";
    private $colMetadata="";
    private $idMenu="";
    private $nombreMenu;
    #Propiedades Heredadas========================
    private $objetoMenu="Menu";
    private $opcionMenu="OpcionMenu";
    private $items=[];

    private $keyComoId=TRUE;
    //  protected $unico;
    private $defaultOpcion=[
    'padre' =>0,
    'hijo'  =>0
    ];
    
    
    function __construct($selectorMenu="UL"){
        parent::__construct($selectorMenu);  
    }
    /**
     * Permite agregar opciones a partir de un arreglo
     * 
     * @method addOpciones
     * @param array $opciones Opciones a crear
     * @example $menu->addOpciones([
     *  [']
     * ]);
     */
    function addOpciones($opciones,$padre=0){
	    if(is_array($opciones)){
            
            $this->opciones=$opciones;
            array_walk($this->opciones,function(&$valor,$key,$default){
                
                $valor = array_merge($default,$valor);
            },$this->defaultOpcion);
            
            foreach ($this->opciones as $key => $value) {
                if($value['padre']==$padre){    
                    $value['idOpcion']=$key;
                    $this->opciones[$key] = $value;
                    
                    $opcion = new JOpcionMenu($value);
                    if($opcion->esPadre()){
                        if(!$opcion->tieneSubmenu()){
                            $submenu=$this->buscarSubmenu($opcion->obtId());
                        } else{
                            
                            $submenu = $opcion->obtSubmenu();
                        }
                        $opcion->agregarSubmenu(
                            $submenu
                        );   
                    }
                    $this->items[]= $opcion;
                    //$this->contenido.=$opcion->renderizar($value);
                }             
            }
            return $this;
        }else{
            throw new Exception("Las opciones deben ser pasadas a partir de un arreglo", 3000);
            
        }
    }
    
    function obtHtml(){
        
        if($this->totalItems()>0){
            for($i=0;$i<=$this->totalItems()-1;++$i){
                
                  $this->contenido.=$this->items[$i]->obtContenido()->render();
                
            } 
        }
        return $this->render();
    }
    /**
     * Busca las opciones de un submenu
     */
    function buscarSubmenu($padre,$cantidad=0){
        $submenu=[];
   
        foreach ($this->opciones as $key => $value) {

            if($value['padre']==$padre) $submenu[$key]=$value;
            if($value['hijo']>0 and $value['padre']==$padre){
                $submenu[$key]['submenu']=$this->buscarSubmenu($key,$cantidad+1);
            }
        }
        
        if(count($submenu>0))
        return $submenu;
    }
    function obtItem($item=false){
        if($item and array_key_exists($item, $this->items)){
            return $this->items[$item];
        }else{
            
        }
        return $this->items;
    }
    function totalItems (){
        return count($this->items);
    }
    
    /**
	 * 
	 */
   	function armarMenu($data){
   		
   	} 
  
}
