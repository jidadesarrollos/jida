<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jida\Console\Command;

use Jida\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of test
 *
 * @author Enyerber Franco
 */
class Test extends Command {

    protected static $defaultName = 'jida:test';

    protected function ejecutar(InputInterface $input, OutputInterface $output) {
        $output->write('Hola mundo ');
    }

    protected function configurar() {
        
    }

}
