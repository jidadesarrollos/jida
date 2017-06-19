<?PHP 
 /**
 * Interfaz de conexión de base de datos 
 */

interface ManejadorBD{
	
	
	private function establecerConexion();
		
	 
	function cerrarConexion();
		
	
	function obtenerDataCompleta();
		
	
	function obtenerTotalCampos();
		
	
	function obtenerArray();
		
	
	function obtenerArrayAsociativo();
		
	
	function insert();
		
	
	function update();
		
	
	function delete();
		
	
	function select();
}
	
 