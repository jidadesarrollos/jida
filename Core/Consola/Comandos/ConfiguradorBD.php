<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Comando cargar un backup en la base de datos
 *
 * @author Abner Saavedra <asaavedra@jidadesarrollos.com>
 * @package Framework
 * @category Consola
 *
 */
class ConfiguradorBD extends Comando {

    protected static $defaultName = 'configurar:bd';

    public function configurar() {

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

    public function CrearConfigBD(InputInterface $input) {

        $config = [
            'puerto'   => ($input->getOption('puerto')) ? $input->getOption('puerto') : '3306',
            'usuario'  => ($input->getOption('usuario')) ? $input->getOption('usuario') : 'root',
            'clave'    => ($input->getOption('clave')) ? $input->getOption('clave') : '',
            'bd'       => ($input->getOption('bd')) ? $input->getOption('bd') : '',
            'servidor' => ($input->getOption('servidor')) ? $input->getOption('servidor') : 'localhost'
        ];

        return $config;
    }

    public function ejecutar(InputInterface $input, OutputInterface $output) {

        $config = $this->CrearConfigBD($input);
        $BD = "$this->path/Aplicacion/Config/BD.php";
        $file_exists = file_exists($BD);

        if ($file_exists) {

            $bd = new \App\Config\BD();
            $config = $bd->default;

        }
        elseif (!$file_exists) {

            $this->crearConfiguracion($config);
            $output->writeln("Archivo de configuracion creado ...");

        }

    }

    protected function crearConfiguracion($config) {

        $path = $this->directorioDeProyecto . DS . self::PathApp . DS . "Config";
        $configtpl = new MotorDePlantillas();
        $configtpl->asignar('servidor', $config['servidor']);
        $configtpl->asignar('puerto', $config['puerto']);
        $configtpl->asignar('usuario', $config['usuario']);
        $configtpl->asignar('clave', $config['clave']);
        $configtpl->asignar('bd', $config['bd']);
        $configtpl->asignar('manejador', 'MySQL');
        file_put_contents("$path/BD.php", $configtpl->obt("clase-BD.jida"));

    }
}