# `\App\Config\Configuracion`

Define las configuraciones generales de la aplicación por medio de propiedades.
## Sinopsis 
```php
class Configuracion extends Config {

    const NOMBRE_APP = 'Deteik';
    const ENTORNO_APP = 'dev';
    var $modulos =[
    'Auth',
    'Mensajes'
    ];
}
```

## Propiedades

- idiomas
- modulos
- tema
- logo
- mensajes


# Definir una aplicación multilenguaje

Para definir una aplicación multilenguaje es necesario definir la constante 
`MULTIIDIOMA` en true, luego definir la propiedad `$modulos` con los idiomas
a utilizar. Ejemplo:
```php
const MULTIIDIOMA = true;
var $idiomas =  [
    'es' => 'Español',
    'en' => 'Ingles'
];

```

De esta manera la aplicación quedara disponible para manejar idiomas en ingles
y en español y buscar los textos a partir de los mismos, por medio del objeto
`\Jida\Manager\Traduccion`. El idioma que este en uso queda disponibilizado
en  `Estructura::$idioma`.