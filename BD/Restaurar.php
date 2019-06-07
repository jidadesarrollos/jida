<?php

/**
 * Restaura una base de datos
 * @param array $config configuracion  de conexion 
 * @param string $sql sql a ejecutar
 * @return string|NULL retorna NULL si no hay error 
 */
function Restaurar (array $config, $sql) {

    echo
    $dsn = "mysql:host=$config[servidor];port=$config[puerto];";

    $pdo = new \PDO($dsn, $config['usuario'], $config['clave']);

    $baseDatos = $config['bd'];

    if (!$pdo->exec("DROP DATABASE IF EXISTS $baseDatos;")) {

        if ($pdo->errorInfo()[0] != '00000') {

            return $pdo->errorInfo()[2];
            
        }
        
    }

    if (!$pdo->exec("CREATE DATABASE  $baseDatos;")) {

        if ($pdo->errorInfo()[0] != '00000') {

            return $pdo->errorInfo()[2];
            
        }
        
    }

    if (!$pdo->exec("USE $baseDatos;")) {

        if ($pdo->errorInfo()[0] != '00000') {

            return $pdo->errorInfo()[2];
            
        }
        
    }

    if (!$pdo->exec($sql)) {

        if ($pdo->errorInfo()[0] != '00000') {

            return $pdo->errorInfo()[2];
            
        }
        
    }

    return NULL;

}
