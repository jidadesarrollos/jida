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

        $this->layout()->incluirCSS([
//            'Framework/Jadmin/Layout/jadmin/htdocs/plugins/datatables/dataTables.bootstrap4.min.css',
//            '{tema}htdocs/plugins/datatables/dataTables.bootstrap4.min.css',
            'julio1',
            'julio2'
        ]);

        $this->layout()->incluirJS([
            'Framework/Jadmin/Layout/jadmin/htdocs/plugins/datatables/jquery.dataTables.min.js',
            'Framework/Jadmin/Layout/jadmin/htdocs/plugins/datatables/dataTables.bootstrap4.min.js',
            '{tema}htdocs/plugins/datatables/dataTables.bootstrap4.min.js',
        ]);

    }

}
