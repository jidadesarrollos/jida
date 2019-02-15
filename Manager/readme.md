Manejo de Rutas 
===

**Codigo De Error** : 1000;
El objeto principal para el manejo de Rutas
es el **Manager**.

**Manager**: Se encarga de inicializar los valores de ejecución del framework, registra tiempo de incio, carga configuraciones 
e instancia el validador y el Objeto Arranque


Rutas\Inicio
-----

Verifica la estructura de la url pasada. Si se solicita un idioma e instancia al objeto Arranque.  
parametros enviados.


Rutas\Arranque
----
Disponibiliza la información de los componentes de la url de la petición.

Ejecuta el procesaodor para identificar la petición. Instancia al controlador y ejecuta el metodo 
pedido. Posteriormente realiza el llamado al objeto pagina para realizar la renderización.

Rutas\Procesador
---
Procesa todos los componentes de la petición realizada por url. Instancia el controlador y e 