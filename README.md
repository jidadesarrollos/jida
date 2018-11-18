# Plantilla básica para Webs con JiDAFramwork #

Este repositorio contiene el esqueleto básico para empezar con una web.

#### Instalación ####

Para instalar una web básica con Jida Framework se deben seguir los siguientes pasos:

1. Clonar el repositorio del esqueleto básico de una web para Jida Framework. Para ello debemos ingresar desde la consola, ubicándonos en el directorio donde queremos clonar el repositorio, y aplicar el siguiente comando:

    ```
    $ git clone https://github.com/jidadesarrollos/app.git [nombre-proyecto]
    ```

    Nota: [nombre-proyecto] es el nombre del directorio donde se va a clonar el repositorio. Se debe colocar el nombre del proyecto o sitio web que se va a desarrollar.

2. Una vez clonado el repositorio del esqueleto básico de una web, procedemos a clonar el repositorio del framework. Para ello debemos ingresar en el directorio que acabamos de crear, y desde la consola debemos ejecutar el siguiente comando:

    ```
    $ git clone https://github.com/jidadesarrollos/jida.git Framework
	```

    Nota: se debe clonar en el directorio “Framework” ya que según la estructura del sitio web, es en ese directorio donde van a estar alojados los archivos del core del framework.

3. Luego de haber clonado el repositorio del framework, ingresamos en el directorio “Framework”, y desde consola vamos a instalar las dependencias de composer y bower, para ello vamos a ejecutar los siguientes comandos:

	```
	$ bower install
	```

    ```
    $ composer install
    ```
	
4. En MySQL crear una base de datos con el nombre del proyecto y restaurar el backup que se encuentra en el directorio **[directorio-proyecto]/BD/backup.sql**.

#### Creación de VirtualHost ####

Procedemos a registrar una URL para este sitio web mediante la creación de un VirtualHost. Esto con el fin de que el Framework pueda leer el sitio web como directorio raíz. Para ello debemos seguir una serie de pasos sencillos:

1. Ingresamos en **[U:]/Windows/System32/drivers/etc/** y con cualquier editor de texto abrimos el archivo **hosts**.
2. Comentamos la línea **127.0.0.1 localhost**, para ello sólo debemos agregarle el símbolo “#” al principio de la línea. Debería quedar de esta forma **# 127.0.0.1 localhost**.
3. Luego agregamos la siguiente línea **127.0.0.1 dev.[nombre-proyecto].local**, donde [nombre-proyecto] será el nombre de nuestro proyecto sin los “[]”. Ejemplo: dev.jidadesarrollos.local
4. Guardamos los cambios y cerramos el archivo hosts.
5. Luego debemos configurar nuestro servidor apache con el _VirtualHost_ creado. Para ello debemos ubicarnos en el directorio **[U:]/xampp/apache/conf/extra/** y abrimos con cualquier editor de texto el archivo **httpd-vhosts.conf**.
6. En ese archivo nos aseguraremos de que todas las líneas están comentadas con un “#” al inicio de la línea. Luego agregamos la siguiente línea **NameVirtualHost *:80**.
7. Luego agregamos la configuración básica para agregar un _VirtualHost_:

```
<VirtualHost *:80>
    DocumentRoot "[U:]/xampp/htdocs/[directorio-proyecto]"
    ServerName dev.[nombre-proyecto].local
</VirtualHost> 
```

8. Una vez hechas estas modificaciones, guardamos los cambios y reiniciamos el servidor apache.
9. Luego en el navegador ingresamos la url http://dev.[nombre-proyecto].local/.

Notas:

* “[U:]” define la unidad, y se debe colocar sin los “[]”. Ejemplo: C:
* “[directorio-proyecto]” se refiere al directorio donde se alojan los archivos del sitio web. Se debe colocar sin los “[]”. Ejemplo: jidadesarrollos.
* “[nombre-proyecto]” se refiere al nombre del sitio web que se está desarrollando. Se debe colocar sin los “[]”. Ejemplo: jidadesarrollos.
