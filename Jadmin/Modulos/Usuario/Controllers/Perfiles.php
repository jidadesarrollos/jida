<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/02/19
 * Time: 02:09 PM
 */

namespace Jida\Jadmin\Modulos\Usuario\Controllers;

use Jida\Jadmin\Controllers\JControl;
use Jida\Modulos\Usuarios\Modelos\Perfil;
use Jida\Render\JVista;

class Perfiles extends JControl {

    public function index(){

        $listaPerfiles = new Perfil();
        $listaPerfiles = $listaPerfiles->consulta()->obt();
        $parametros = ['titulos' => ['Usuario', 'Nombre', 'Apellido', 'Correo', 'Perfiles']];
        $vista = new JVista($listaPerfiles, $parametros);

        $vista->accionesFila([
            ['span'  => 'fa fa-edit',
             'title' => "Editar Perfil",
             'href'  => '/jadmin/usuario/perfiles/gestion/{clave}'],
            ['span'        => 'fa fa-trash',
             'title'       => 'Eliminar Perfil',
             'href'        => '/jadmin/usuario/perfiles/eliminar/{clave}',
             'data-jvista' => 'confirm',
             'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el cliente seleccionado?']
        ]);

        $render = $vista->render();

        $this->data([
            'vista' => $render
        ]);

    }
}