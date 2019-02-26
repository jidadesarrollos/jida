<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

class Jadmin extends JControl {

    public function index() {

        $this->layout()->incluirCSS([
            'bt-dataTables' => '{tema}/htdocs/plugins/datatables/dataTables.bootstrap4.min.css',
        ]);

        $this->layout()->incluirJS([
            'jq-dataTables' => '{tema}/htdocs/plugins/datatables/jquery.dataTables.min.js',
            'bt-dataTables' => '{tema}/htdocs/plugins/datatables/dataTables.bootstrap4.min.js',
        ]);

    }

}
