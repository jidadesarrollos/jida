<?php

namespace Jida\Modulos\Usuarios\Modelos;

use Jida\Core\Modelo;

class Perfil extends Modelo {

    public $id_perfil;
    public $perfil;
    public $identificador;
    public $id_idioma;
    public $texto_original;

    protected $tablaBD = "s_perfiles";
    protected $pk = "id_perfil";

}