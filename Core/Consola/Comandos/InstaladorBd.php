<?php

namespace Jida\Core\Consola\Comandos;

require(__DIR__ . "/../../../BD/Restaurar.php");

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Comando cargar un backup en la base de datos
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Consola
 *
 */
class InstaladorBd extends Comando {

    protected static $defaultName = 'instalar:bd';

    public function configurar() {

        $this->addArgument(
            'archivo',
            InputArgument::OPTIONAL,
            'Archivo que se cargara.'
        );
        $this->addOption(
            'servidor',
            's',
            InputOption::VALUE_OPTIONAL,
            "Servidor de la base de datos");
        $this->addOption(
            'puerto',
            'p',
            InputOption::VALUE_OPTIONAL,
            " puerto "
        );
        $this->addOption(
            'usuario',
            'u',
            InputOption::VALUE_OPTIONAL,
            "Usuario de la base de datos"
        );
        $this->addOption(
            'clave',
            'c',
            InputOption::VALUE_OPTIONAL,
            "Contraseña del usuario"
        );
        $this->addOption(
            'bd',
            'b',
            InputOption:: VALUE_OPTIONAL,
            "Nombre de la base de datos"
        );

    }

    public function validarArchivoBD(InputInterface $input, OutputInterface $output) {

        if ($input->getArgument('archivo')) {

            $archivo = $input->getArgument('archivo');
        }
        else {

            $archivo = $this->path . '/BD/app.sql';
        }

        $sql = file_get_contents($archivo);

        if (!file_exists($archivo)) {

            return $output->writeln("El Archivo $archivo no existe");

        }

    }

    public function ejecutar(InputInterface $input, OutputInterface $output) {

        $this->validarArchivoBD($input, $output);

        $config = [
            'puerto'   => '3306',
            'usuario'  => 'root',
            'clave'    => '',
            'bd'       => '',
            'servidor' => 'localhost'
        ];
        $params = false;

        if ($input->getOption('servidor')) {
            $config['servidor'] = $input->getOption('servidor');
            $params = true;
        }

        if ($input->getOption('puerto')) {

            $config['puerto'] = $input->getOption('puerto');
            $params = true;

        }

        if ($input->getOption('usuario')) {

            $config['usuario'] = $input->getOption('usuario');
            $params = true;
        }

        if ($input->getOption('clave')) {

            $config['clave'] = $input->getOption('clave');
            $params = true;

        }

        if ($input->getOption('bd')) {

            $config['bd'] = $input->getOption('bd');
            $params = true;

        }

    }

}
