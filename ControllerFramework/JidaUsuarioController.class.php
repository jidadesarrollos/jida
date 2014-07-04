<?PHP 


class JidaUsuarioController extends Controller{
    
    
    
    function cierreSesion(){
        Session::destroy();
    }
}

?>