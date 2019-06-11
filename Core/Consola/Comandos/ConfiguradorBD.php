<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Comando cargar un backup en la base de datos
 *
 * @author Abner Saavedra <asaavedra@jidadesarrollos.com>
 * @package Framework
 * @category Consola
 *
 */
class ConfiguradorBD extends Comando{


    protected static $defaultName = 'configurar:bd';

    public function configurar () {

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

    public function validarArchivoBD(InputInterface $input, OutputInterface $output){

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

    public function ejecutar (InputInterface $input, OutputInterface $output) {


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

    public function CrearConfigBD( $params = false, $config, OutputInterface $output, $sql){

        $BD = "$this->path/Aplicacion/Config/BD.php";
        $file_exists = file_exists($BD);

        if (!$params && $file_exists) {

            $bd = new \App\Config\BD();
            $config = $bd->default;

        }
        elseif (!$file_exists) {

            $this->crearConfiguracion($config);
            $output->writeln("Archivo de configuracion creado ...");

        }

        $output->writeln("Restaurando base de datos espere...");

        try {

            $resultado = Restaurar($config, $sql);

        }
        catch (\Exception $ex) {

            return $output->writeln($ex->getMessage());

        }

        if ($resultado == NULL) {

            $output->writeln("Base de datos restaurada...");

        }
        else {

            $output->writeln("Ocurrio un error $resultado...");

        }

    }

    protected function crearConfiguracion ($config) {

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