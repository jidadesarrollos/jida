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

como se puede ver se pueden aplicar varias reglas para cada item las reglas deben estar separadas por el caracter  `| ` y cada regla admite parametros los cuales se pasan colocando `:`  despues de la regla y separando los parametros por `,` las reglas validas son :

- 