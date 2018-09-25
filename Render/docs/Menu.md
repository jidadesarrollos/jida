# Menu

Permite renderizar menus personalizados. Los menus deben ir en el directorio "Menus" de la aplicacion en formato json.

Sinopsis
---
```php
class \Jida\Render\Menu extends Selector{
 ....
}
```
Instancia
---
```php

$form = new \Jida\Render\Menu($ruta);
```

Parametros
---
- **$ruta**: (string) Ruta del menu en estructura de directorio. La ruta debe hacer uso de "/" para la separación de Paths. Si se desea implementar un menu del framework se debe usar como ruta "jida/".


Metodos
--
- render: Renderiza el HTML del Menu