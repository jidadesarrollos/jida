
# Validacion 

Con la clase Jida\Validador\Validador se puede procesar datos validado y sanitizando segun las reglas proporcionadas 

## Metodos: 

### Validador::crear(array $value, array $opciones)

Este es un metodo estatico que procesa los datos y reglas pasados en sus parametros retornando un objeto Validador 

- `array $value`: Valores a Validar y sanitizar
- `array $opciones`: lista de reglas para cada valor 

Ejemplo:
```php
<?php
$valid = Validador::crear(array_merge($_POST, $_FILES), [
    'file' => 'archivo|mime_type:image/jpeg,image/jpg,image/png',
     'email' => 'mail|string:lower'
]);
if ($valid->valido()) {

    $name = 'file.' . $valid['file']->getExtension();
    $valid['file']->copy($name);
    echo "datos recividos de $valud[email]";
}
```

como se puede ver se pueden aplicar varias reglas para cada item las reglas deben estar separadas por el caracter  `| ` y cada regla admite opciones las cuales se pasan colocando `:`  despues de la regla y separando los las opciones por `,` las reglas validas son :

- `string` : Esta regla valida una cadena de texto acepta las opciones `lower, ouper, md5, trim, htmlentities, htmlencode o urlencode` que aplicadas modifican el texto ejemplo `string:trim,lower`

- `alpha`:  Valida una cadena alfabetica 
- `alpha_num`:  Valida una cadena alfanumerica 
- `fecha`:Valida un una fecha y convierte en un objeto DateTime acepta una opcion  que que es el formato de la fecha valida ejemplo `fecha:Y-m-d`
- `despues_de`: valida que una fecha sea despues de la fecha indicada en la opcion, ejemplo: `despues_de:2018-10-01`
- `antes_de`: valida que una fecha sea antes de la fecha indicada en la opcion, ejemplo: `antes_de:2018-10-01`
