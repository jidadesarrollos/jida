# Layout

Renderiza el layout de la aplicación.

## METODOS

<h3>incluirCSS</h3>

Permite incluir archivos css desde el controlador

```php
$this->layout()->incluirCSS(['identificador'=> 'archivo/css.css']);
$this->layout()->incluirCSS(['archivo/css.css']);
$this->layout()->incluirCSS('archivo/css.css');
```
#### Parametros:
   - $archivos (_mixed_) : puede ser un string o un mapa asociativo,
   el key de los arreglos asociativos es tratado como un identificador unico
   del archivo con el fin de evitar la doble inclusion de archivos. Debe tenerse
   cuidado pues si no se pasa y se llama varias veces al mismo metodo podria suceder 
   que se sobreescriban los archivos al encontrarse todos en la posición 0.
   

<h3>incluirJS</h3>

Permite incluir archivos css desde el controlador

```php
$this->layout()->incluirJS(['identificador'=> 'archivo/js.js']);
$this->layout()->incluirJS(['archivo/js.js']);
$this->layout()->incluirJS('archivo/js.js');
```
#### Parametros:
   - $archivos (_mixed_) : puede ser un string o un mapa asociativo,
   el key de los arreglos asociativos es tratado como un identificador unico
   del archivo con el fin de evitar la doble inclusion de archivos. Debe tenerse
   cuidado pues si no se pasa y se llama varias veces al mismo metodo podria suceder 
   que se sobreescriban los archivos al encontrarse todos en la posición 0.
   
<h3>incluirJSAjax</h3>

   Funciona igual que el metodo `incluirJS` pero a diferencia de este, solo incluye
   en la vista los archivos pasados en el metodo sin importar la configuración realizada
   para el tema general de la aplicación. 