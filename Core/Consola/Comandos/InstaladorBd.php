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

        //Validamos el archivo recibido para restaurar la BD y sino lo hay asignamos uno por defecto.
        if ($input->getArgument('archivo')) {

            $archivo = $input->getArgument('archivo');
        }
        else {

            $archivo = $this->path . '/BD/app.sql';
        }

        /*Sino existe el archivo de restauración de BD escribimos un mensaje, en caso contrario (si existe) creamos uno
          con el contenido del recibido y lo retornamos*/
        if (!file_exists($archivo)) {

            $output->writeln("El Archivo $archivo no existe");

        }
        else {

            return $sql = file_get_contents($archivo);

        }

    }

    public function crearConfigBD(InputInterface $input, OutputInterface $output) {

        //Validamos el archivo recibido para restaurar la BD y sino lo hay asignamos uno por defecto.

        $validarArchivoBD = $this->validarArchivoBD($input, $output);

        //Procedemos a crear la configuración de BD recibida y sino la hay asignamos una por defecto.
        $config = [
            'puerto'   => ($input->getOption('puerto'))? $input->getOption('puerto'): '3306',
            'usuario'  => ($input->getOption('usuario'))? $input->getOption('usuario'): 'root',
            'clave'    => ($input->getOption('clave'))? $input->getOption('clave'): '',
            'bd'       => ($input->getOption('bd'))? $input->getOption('bd'): '',
            'servidor' => ($input->getOption('servidor'))? $input->getOption('servidor'): 'localhost'
        ];

        $configBD = [
            'config' => $config,
            'sql'    => $validarArchivoBD,
        ];
        return $configBD;

    }

    public function ejecutar(InputInterface $input, OutputInterface $output) {

        //Procedemos a crear la configuración de BD recibida y sino la hay asignamos una por defecto.
        $configBD = $this->crearConfigBD($input, $output);
        $config = $configBD['config'];
        $sql = $configBD['sql'];

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


}
