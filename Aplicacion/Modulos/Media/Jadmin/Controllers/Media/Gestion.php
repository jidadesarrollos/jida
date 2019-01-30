<?php

namespace App\Modulos\Media\Jadmin\Controllers\Media;

use Jida\Render\Formulario;

Trait Gestion {

    function _gestion($idFk, $id) {

        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyecto = new Proyecto($idFk);

        $form = new Formulario('Media/Media', $id);

        $form->action = $this->obtUrl('', [$id]);

        if ($this->post('btnFormularioMedia')) {
            if ($form->validar()) {
                if ($this->modelo->salvar($this->post())) {
                    $condicion = empty($id) ? 'almacenada' : 'editada';
                    Mensajes::almacenar(Mensajes::suceso("Fotografia {$condicion} correctamente"));
                    $this->redireccionar("/jadmin/media/index/{$idFk}");
                }
                else Mensajes::crear('error','Error al guardar la informacion');
            }
            else Mensajes::crear('error','Informacion no valida');
        }

        $this->data([
            'form' => $form->render(),
            'idFk' => $idFk
        ]);

    }

}