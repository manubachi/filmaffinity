<?php
namespace espacio5;

trait Saludador
{
    public $mensajeSaludo = "Hola\n";

    public function saluda()
    {
      echo $this->$mensajeSaludo;
    }
}
