<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Medios\Debug;

class Jadmin extends JControl {

    public function index() {

        Debug::imprimir(1111, true);
        exit("si");

    }

}
