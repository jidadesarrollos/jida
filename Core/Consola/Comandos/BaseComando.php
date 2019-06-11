<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Jida\Core\Consola\MotorDePlantillas;

/**
 * Clase padre de los diferentes comandos de consola de JIDA
 *
 * @author Abner Saavedra <asaavedra@jidadesarrollos.com>
 * @package Framework
 * @category Consola
 *
 */

abstract class BaseComando extends Comando {

    public function configurar (){}
}