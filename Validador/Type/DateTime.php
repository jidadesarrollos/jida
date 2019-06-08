<?php


namespace Jida\Validador\Type;


class DateTime extends \DateTime {

    protected $format;

    public function __construct ($time, $timezone, $format) {
        
        parent::__construct($time, $timezone);
        $this->format = $format;

    }

    public function __toString () {
        
        return $this->format($this->format);

    }

}
