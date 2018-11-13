<?php

require 'Usuario.php';

class Subclase extends Usuario
{
    use Saludador;

    public static function quienSoy()
    {
      return 'Subclase';
    }
}

/*
self::CONSTANTE
self:$numero -> variable estatica
static::metodo()
 */
