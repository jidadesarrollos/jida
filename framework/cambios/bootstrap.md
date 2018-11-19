Ajustes Branch Bootstrap.
==
1.	Remover todo el código del archivo index.php y dejar solo la 
inclusión de Framework/inicio.php  y la instancia del jidaController.

2.	Remover Archivos InitConfig.php y AppConfig.php existentes en Aplicación\Bootstrap. 
 Si hay alguna constante necesaria para la aplicación en desarrollo, crear un archivo 
“index.php” en Aplicación y mover la lógica para allá.  El objeto App\Config\Configuracion Ahora es llamado directamente 
desde el framework.

3.	El objeto App\Config\Configuracion debe tener un método publico llamado “inicio” que realice
 la declaración de las variables globales _CSS y _JS (Esto es transicional, en un futuro no será necesario). 
    Ejemplo: 
    
    
       public function inicio() {
      
              $GLOBALS['_CSS'] = \App\Config\Cliente\CSS::archivos();
              $GLOBALS['_JS'] = \App\Config\Cliente\JS::archivos();
              $GLOBALS['configJVista'] = [
                  'nroFilas' => 5,
              ];
      
          }
    
    
    
    