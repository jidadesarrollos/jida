<?php

namespace Jida\Core\Consola\Comandos;

use Jida\Core\Consola\Comando;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function validarArchivoBD(InputInterface $input) {

        $archivo = $this->path . '/BD/app.sql';

        if ($input->getArgument('archivo')) $archivo = $input->getArgument('archivo');

        if (!file_exists($archivo)) throw new \Exception('"El Archivo $archivo no existe', 100);

        return file_get_contents($archivo);

    }

    public function crearConfigBD(InputInterface $input) {

        $config = [
            'puerto'   => ($input->getOption('puerto')) ? $input->getOption('puerto') : '3306',
            'usuario'  => ($input->getOption('usuario')) ? $input->getOption('usuario') : 'root',
            'clave'    => ($input->getOption('clave')) ? $input->getOption('clave') : '',
            'bd'       => ($input->getOption('bd')) ? $input->getOption('bd') : '',
            'servidor' => ($input->getOption('servidor')) ? $input->getOption('servidor') : 'localhost'
        ];

        return $config;

    }

    public function restaurar(array $config, $sql) {

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

    public function ejecutar(InputInterface $input, OutputInterface $output) {

        try {

            $config = $this->crearConfigBD($input, $output);
            //var_dump($config);
            $sql = $this->validarArchivoBD($input, $output);

            $output->writeln("Restaurando base de datos espere...");

            if ($this->restaurar($config, $sql) === NULL) {
                $output->writeln("Base de datos restaurada...");
            }
            else {
                $output->writeln("Ocurrio un error $this->restaurar($config, $sql)...");
            }

        }
        catch (\Exception $ex) {
            $output->writeln($ex->getMessage());
        }

    }

}
