
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
- `length`: Valida que la cadena sea del tamaño especificado en la opcion 
- `length:5`, si se le pasan dos opciones la primea indica el minimo y la segunda el maximo `length:3,5`
- `fecha`:Valida un una fecha y convierte en un objeto DateTime acepta una opcion  que que es el formato de la fecha valida ejemplo `fecha:Y-m-d`
- `despues_de`: valida que una fecha sea despues de la fecha indicada en la opcion, ejemplo: `despues_de:2018-10-01`
- `antes_de`: valida que una fecha sea antes de la fecha indicada en la opcion, ejemplo: `antes_de:2018-10-01`
- `required`: puede utilizarse para indicar que iten no es requerido `required:false`
- `numerico`: Valida que el item sea numerico acepta una opcion la cual puede ser `int` o `float`para indicas que tipo debe ser 
- `max`: se puede usar junto a `numerico`, valida que el numero no sea mayo a la opcion pasada ejemplo `max:5`
- `min`: se puede usar junto a `numerico`, valida que el numero no sea menor a la opcion pasada ejemplo `max:5`
- `archivo`: Valida un archivo un arra de archivo recibido de $_FILES y lo convierte en un objeto de la clase *`Jida\Validador\Type\Archivo `* este acepta una opcion con la que se puede indicar si se reciben multiples archivos en el item `archivo:multiple` en este caso se convertirá en un array de objetos *`Jida\Validador\Type\Archivo `*
- `mime_type`: puede usarse junto a `archivo` validar el tipo de archivo recibido se puede indicar los tipos de archivos en las opciones 
- `mail`: Valida un mail acepta una opcion con la que se puede indicar si se reciben multiples mail separados por coma `mail:multiple` en este caso convertirá en un array con los mails recibidos 
- `password`: Convierte la cadena en un objeto *`Jida\Validador\Type\Password`* que trasforma la cadena en un hash de *`password_hash`* acepta dos opciones el primero seria el salt y el segundo el costo 
- `url`: valida una url si se le  pasa `activo` en la primera opcion valida que el url este activo 
- `ip`: Valida una ip, se le puede pasar una opcion indicando `ipv4` o `ipv6` para indicar que tipo de ip es 
- `match`: Valida que texto coincida con la exprecion regular pasada en la primera opcion 
- 