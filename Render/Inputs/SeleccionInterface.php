<?php
namespace Jida\Render\Inputs;

interface SeleccionInterface {

    function __construct($selector = "", array $attr = []);

    function agregarOpciones($opciones);


}