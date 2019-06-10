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
- **render**: renderiza el HTML de las etiquetas meta Open Graph

Parámetros
--
- **$data**: arreglo con la configuración personalizada de las etiquetas meta.

Implementación
---
- Desde el controlador, se debe pasar mediante el layout, el arreglo con la configuración personalizada para las etiquetas open graph. 
```php
$og = [
    'og:title' => 'Aplicación Jida',
    'og:type'  => 'website',
    'og:url'   => 'http://jidadesarrollos.com',
    'og:image' => 'http://jidadesarrollos.com/htdocs/img/logo.png'
];

$this->layout()->openGraph($og);
```
