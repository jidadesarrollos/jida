<?php

namespace App\Modulos\Proyectos\Jadmin\Controllers\proyectos;
use App\Modulos\Proyectos\Modelos\Proyecto as Modelo;
use Jida\Render\Formulario;

Trait gestion {

    function gestion($id = "") {

        $modelo = new Modelo($id);
        $form = new Formulario('Proyectos/Proyectos', $id);

        $form->action = $this->obtUrl('', [$id]);

        if ($this->post('btnFormularioProyectos')) {

            if ($form->validar()) {

                $modelo->slug = Cadenas::guionCase($modelo->nombre);

                if ($modelo->salvar($this->post())) {

                    $condicion = empty($id) ? 'creado' : 'modificado';

                    Mensajes::crear("suceso", "Proyecto {$condicion} correctamente");
                    $this->redireccionar("/jadmin/proyectos");

                }
                else Mensajes::almacenar(Mensajes::error('Error al guardar la informacion'));
            }
            else Mensajes::almacenar(Mensajes::error('Informacion no valida'));
        }

        $this->data(['vista' => $form->render()]);

    }

}