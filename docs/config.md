#Crear Aplicación Jida.


Crear una aplicación Jida se realiza de forma simple con los siguientes pasos.


1. Crear el proyecto, esto se puede realizar de dos maneras:
    1. create-project 
        - ```composer create-project --prefer-dist jida/app nombre-app```
    2. Clonando el repositiorio `https://github.com/jidadesarrollos/app.git`
        1. `git clone https://github.com/jidadesarrollos/app.git`
        2. `composer install` 
        
Siguiendo los pasos anteriores ya se tendrá una aplicación jida funcional y lista para
agregar nuevos _features_. 

La estructura de la aplicación Jida es la siguiente:
- Aplicacion [namespace `App\`] (Carpeta con todos los archivos para desarrollar la aplicación).
    - Config [namespace `App\Config\`]  Archivos de configuración de la aplicación.
        - [`\App\Config\BD`]()
    - Controllers [namespace `App\Controllers\`]
    - Modelos [namespace `App\Modelos\`]
    - Modulos[namespace `App\Modulos\`]
    - vistas
-htdocs: Carpeta sugerida para los recursos estaticos generales, como imagenes, javascript y css
que no correspondan a un tema. 