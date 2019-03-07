<?php

namespace Jida\Validador\Type;

class Archivo extends \SplFileInfo {

    protected $multiple;
    protected $File;

    public function __construct($file) {

        $this->File = $file;

        parent::__construct($this->File['name']);
    }

    public function __toString() {
        
        return $this->getContent();
        
    }

    /**
     * copia el archivo recibido al destino 
     * @param string $dest directorio donde se copiara
     */
    public function copy($dest) {
        
        copy($this->File['tmp_name'], $dest);
        
    }

    /**
     * retorna el contenido del archivo recibido
     * @return string
     */
    public function getContent() {
        
        return file_get_contents($this->File['tmp_name']);
        
    }

    /**
     * retorna un objeto SplFileObject con el archivo recibido
     * @param string $open_mode
     * @param string $use_include_path
     * @param string $context
     * @return SplFileObject
     */
    public function openFile($open_mode = "r", $use_include_path = false, $context = null) {
        
        return new \SplFileObject($this->File['tmp_name'], $open_mode, $use_include_path, $context);
        
    }

    public function getType(): string {
        
        return $this->File['type'];
        
    }

    public function isDir() {
        
        return isset($this->File['tmp_name']) && is_dir($this->File['tmp_name']);
        
    }

    public function isFile() {
        
        return isset($this->File['tmp_name']) && is_file($this->File['tmp_name']);
        
    }

    public function isReadable() {
        
        return isset($this->File['tmp_name']) && is_readable($this->File['tmp_name']);
        
    }

    public function isExecutable() {
        
        return isset($this->File['tmp_name']) && is_executable($this->File['tmp_name']);
        
    }

    public function getSize() {
        
        return isset($this->File['tmp_name']) && filesize($this->File['tmp_name']);
        
    }

}
