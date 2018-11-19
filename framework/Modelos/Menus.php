<?php

/**
 * Clase Modelo para s_menus
 *
 *
 * @package Aplicacion
 * @category Modelo
 */

namespace Jida\Modelos;

use Jida\BD as BD;

class Menus extends BD\DataModel {


    /**
     * @var int id_menu
     */
    public $id_menu;
    /**
     * @var varchar menu
     */
    public $menu;
    /**
     * @var varchar identificador
     */
    public $identificador;
    /**
     * @var varchar meta_data
     */
    public $meta_data;
    protected $pk = 'id_menu';
    protected $tablaBD = 's_menus';

    function obtMenus() {
        $this->consulta(['id_menu',
                         'menu'
        ]);

        return $this->obt('id_menu');
    }

}//fin clase