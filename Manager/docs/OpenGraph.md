# OpenGraph

Permite crear etiquetas open graph personalizadas.

Sinopsis
---
```php
class \Jida\Manager\Vista\OpenGraph {
 ....
}
```

Metodo
--
- **imprimir**: Imprime el HTML de las etiquetas meta Open Graph

Parámetros
--
- **$data**: arreglo con la configuración personalizada de las etiquetas meta.

Implementación
---
- Se debe agregar en el layout principal el método _imprimirOG()_
```php
    echo $this->imprimirOG();
```
- Desde el controlador, se debe pasar mediante el objeto data, el arreglo con la configuración personalizada para las etiquetas open graph. 
```php
$og = [
    'og:title' => 'Aplicación Jida',
    'og:type'  => 'website',
    'og:url'   => 'http://jidadesarrollos.com',
    'og:image' => 'http://jidadesarrollos.com/htdocs/img/logo.png'
];

$this->data([
    'og' => $og
]);
```
