Funcionamiento de arranque del Jida
====

El manager inicializa los valores por defectos
del framework. 
Realiza la instancia del Validador para verificar que
toda la estructura inicial necesaria se encuentre armada.

Instancia al *Lector*, que es quien se encarga
de procesar la url solicitada desde el navegador
e interpretar que es lo que significa y que componentes
requiere.

Lector
--
Valida que la estructura y datos solicitados sean validos,
procesa la informacion para identificar el controlador encargado
y la vista a renderizar y posteriormente r|ealiza la ejecucion.
