<?php
/**
 * Controlador Padre
 * aqui va toda la logica en comun que necesiten
 *  los controladores que extienden de el
 */

namespace App\Controllers;

class App extends \Jida\Core\Controller {

    function __construct () {

        parent::__construct();
        
        $this->layout('principal.tpl.php');

    }

}
