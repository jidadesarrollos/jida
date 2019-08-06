# Cambios 0.7.2

## Nuevo
   
   - Agregado objeto `Jida\Manager\Textos` para manejo de textos
   generales y multiidiomas.
   - Los textos y traducciones pueden manejarse por modulo.
   
## Cambios
   - Handler para manejo de idiomas.
   - Deprecado componente traducciones 
   - propiedad layout del `tema.json` ahora recibe un objeto con los valores 
   "default" y "error".
   - Mejorado el manejo de excepciones. El layout de excepciones
   debe ser definido ahora en el tema.json adentro de la entrada layout.
   - Las excepciones son buscadas en la carpeta del tema instalado, adentro de una carpeta "errors"
   
