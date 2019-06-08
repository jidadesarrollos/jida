
# Comando de consola de Jida Framework


## Consola:

### Crear un módulo desde la línea de comandos: 

Con el siguiente comando se puede crear un módulo con toda su estructura de directorios y archivos básicos en el directorio /Aplicacion/Modulos
```sh
$ php jida/bin/jida crear:modulo <nombre>
```

 -  `<nombre>` : nombre del módulo que será creado 

### Crear un controlador 

Este comando crea un controlador con un método index y su correspondiente vista index
```sh
$ php jida/bin/jida crear:controlador <nombre> --[opcion] [--]
```
  

 - `<nombre>` : nombre del controlador que será creado  

 -  `-m, --modulo[=modulo]` : establece el módulo al que pertenece el controlador (opcional)

 - ` -j, --jadmin `: si está presente creará el controlador dentro del directorio /Aplicacion/Jadmin, si la opción módulo está establecida crear el controlador dentro del directorio Jadmin del módulo

## Instalar base de datos 

Con este comando se puede instalar la base de datos del proyecto o del archivo indicado si no se pasa ninguna opción el se tomaran los parametros de conexion del la clase App\Config\BD y si hay presentes opciones de conexion y la clase App\Config\BD sera creada con la configuracion obtenida de las opciones 
```sh
$ php jida/bin/jida instalar:bd <archivo> --[opcion] [--]
```
 -  `<nombre>` : nombre del archivo sql que sera ejecutado (opcional)
 -  `-s, --servidor`: host del mysql (opcional)
 -  `-p, --puerto`: puerto de mysql (opcional)
 -  `-u, --usuario`: nombre de usuario (opcional)
 -  `-c, --clave`: clave de usuario (opcional)
 -  `-bd, --bd`: nombre de la base de datos (opcional)