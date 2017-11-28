<?php
/**
 * Helper para prueba de plataforma
 * @revision
 */
function respuestaAjax($respuesta, $tipo = 2) {
    if ($tipo == 2) {
        echo $respuesta;
    } else {
        print(json_encode($respuesta));
    }
    exit;
}

function redireccionar($url) {
    header('location:' . $url . '');
    exit;
}

function TestPlataforma() {

    /**
     * Validacion de Plataforma
     *
     */
    if (defined('TEST_PLATFORM') && TEST_PLATFORM === TRUE) {

        echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:#69c1ff">Test Plataforma de Capa App [ ' . $_SERVER['SERVER_ADDR'] . ' ]</h3>';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:green;">Servidor Windows!</h3>';
        } else {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:green;">Servidor Linux!</h3>';
        }

        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:green;">La versi&oacute;n de PHP es la ' . PHP_VERSION . '!</h3>';

        } else {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:red;">La versi&oacute;n de PHP es Inferior a la 5.3.0, Se Recomienda Actualizarla!</h3>';
        }

        if (!function_exists('apache_get_modules')) {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:red;">Modulo Rewrite de Apache Desactivado!</h3>';
        } else {
            if (in_array('mod_rewrite', apache_get_modules())) {
                echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:green;">Modulo Rewrite de Apache Activado!</h3>';
            }
        }

        if (in_array('curl', get_loaded_extensions())) {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:green;">Libreria cURL Activa!</h3>';
        } else {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:red;">Libreria cURL Desactivada!</h3>';
        }

        if (function_exists('apache_get_modules')) {
            echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:red;">Sin Conexion a Base de Datos!</h3>';
        } else {
            if (in_array('mod_rewrite', apache_get_modules())) {
                echo '<h3 style="color:white;display:block;padding:0.8em;margin:1em 0;clear:both;background:green;">Validar Conexion a Base de Datos!</h3>';
            }
        }

        exit(0);
    }

}

function debug() {
}
