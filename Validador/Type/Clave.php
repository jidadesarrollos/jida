<?php

namespace Jida\Validador\Type;

class Clave {

    const Md5 = "md5";
    const PasswordHash = "password_hash";
    protected $pass;

    /**
     * Algoritmo que se usara
     *
     * @var int
     */
    private $Algorim;

    /**
     * opciones del algoritmo
     *
     * @var array
     */
    private $Options;

    protected $hash_clave = '';

    public function __construct($password, $algo = PASSWORD_BCRYPT, $options = []) {

        $this->pass = $password;
        $this->Algorim = $algo;
        $this->Options = $options;
        $this->hash_clave = \App\Config\Configuracion::HASH_CLAVE;
    }

    public function __toString() {

        return $this->hash();

    }

    public function compare($hash) {

        switch ($this->hash_clave) {
            case self::Md5:
                return md5($this->pass) === $hash;

            case self::PasswordHash:
                return password_verify($this->pass, $hash);

        }

    }

    public function hash() {

        switch ($this->hash_clave) {
            case self::Md5:
                return md5($this->pass);

            case self::PasswordHash:
                if (!isset($this->Options['cost'])) {

                    $this->Options['cost'] = $this->generateCost();

                }
                return password_hash($this->pass, $this->Algorim, $this->Options);

        }

    }

    /**
     * establece las opciones para el hash
     *
     * @param array $options
     */
    public function SetOptions(array $options) {

        $this->Options = $options;

    }

    /**
     * genera el costo de tiempo que usara pasword hash
     *
     * @return int
     */
    private function generateCost() {

        // return 10;
        $timeTarget = 0.05;
        $coste = 8;
        do {

            $coste++;
            $inicio = microtime(true);
            password_hash("test", $this->Algorim, ["cost" => $coste]);
            $fin = microtime(true);

        } while (($fin - $inicio) < $timeTarget);

        return $coste;

    }

}
