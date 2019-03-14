<?php

namespace Jida\Validador\Type;

class Clave {

    protected $pass;

    /**
     * Algoritmo que se usara
     * @var int 
     */
    private $Algorim;

    /**
     * opciones del algoritmo
     * @var array 
     */
    private $Options;

    public function __construct(string $password, $algo = PASSWORD_BCRYPT, array $options = []) {
        
        $this->pass = $password;
        $this->Algorim = $algo;
        $this->Options = $options;
    }

    public function __toString() {
        
        return $this->hash();
        
    }

    public function compare($hash) {
        
        return password_verify($this->pass, $hash);
        
    }

    public function hash() {
        
        if (!isset($this->Options['cost'])) {
            
            $this->Options['cost'] = $this->generateCost();
            
        }
        return password_hash($this->pass, $this->Algorim, $this->Options);
        
    }

    /**
     * establece las opciones para el hash
     * @param array $options
     */
    public function SetOptions(array $options) {
        
        $this->Options = $options;
        
    }

    /**
     * genera el costo de tiempo que usara pasword hash
     * @return int
     */
    private function generateCost(): int {
        
        // return 10;
        $timeTarget = 0.05;
        $coste      = 8;
        do {
            
            $coste++;
            $inicio = microtime(true);
            password_hash("test", $this->Algorim, ["cost" => $coste]);
            $fin    = microtime(true);
            
        } while (($fin - $inicio) < $timeTarget);

        return $coste;
        
    }

}
