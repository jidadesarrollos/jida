<?php

define('DS', DIRECTORY_SEPARATOR);
define("PS", PATH_SEPARATOR);
define('ROOT', getcwd() . DS);

/**
 * Entornos
 */
define('dev', 'dev');
define('prod', 'prod');
set_time_limit(180);