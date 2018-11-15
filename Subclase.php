<?php

require 'Usuario.php';

class Subclase extends Usuario
{
    use Saludador;

    public $nombre;

    public function __construct($id,$nombre)
    {
      parent::__construct($id); // SE hace referencia al metodo de la clase padre
      $this->nombre = $nombre;
    }

    public static function quienSoy()
    {
      return 'Subclase de '. parent::quienSoy();
    }
}

/*
self::CONSTANTE
self:$numero -> variable estatica
static::metodo()
 */
