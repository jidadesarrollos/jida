<?PHP

namespace Jida\Jadmin\Controllers;

class Acl extends JController {

    function __construct () {

        $this->url = '/jadmin/acl/';
        parent::__construct();
        $this->layout = "jadmin.tpl.php";
    }

    function index () {

    }

    function objetos () {

    }
}
