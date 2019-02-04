<?php

namespace App\Modulos\Proyectos\Jadmin\Controllers\Media;

use App\Modulos\Proyectos\Modelos\Media;
use Jida\Medios\Sesion;
use Jida\Render\Formulario;

Trait Gestion {

    private function gestionForm(Formulario $form, $idMedia) {

        if (!$form->validar()) {
            //la clase formulario genera la variable de sesion __msjForm automaticamente
            //debe limpiarse para que no aparezca.
            Sesion::destruir('__msjForm');
            $this->respuestaJson(['estatus' => false]);
        }
        $media = new Media($idMedia);

        if (!$media->salvar($this->post())) {
            $this->respuestaJson(['estatus' => false]);
        }

        $this->respuestaJson(['estatus' => true]);

    }

    function _gestion($idMedia) {

        $form = new Formulario('proyectos/Media', $idMedia);
        $form->action = $this->obtUrl('', ['id' => $idMedia]);

        $form->boton('principal')->attr('type', 'button');

        if ($this->solicitudAjax() and $this->post('btnMedia')) {
            $this->gestionForm($form, $idMedia);
        }

        if (empty($idMedia)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->data([
            'id'   => $idMedia,
            'form' => $form->render()
        ]);

    }

}