<?php
trait Saludador
{
    public $mensajeSaludo = "Hola\n";

    public function saluda()
    {
      echo $this->$mensajeSaludo;
    }
}

class Usuario
{
    use Saludador;

    const ADMIN = 'admin';


    public $id;
    public $login;
    public $password;
    public static $cantidad = 0;

    public function __construct($id)
    {
        require 'comunes/auxiliar.php';
        $pdo = conectar();
        $usuario = buscarUsuario($pdo, $id);
        $this->id = $usuario['id']; // this se refiere a la instancia actual
        $this->login = $usuario['login'];
        $this->password = $usuario['password'];
        self::$cantidad++;
    }

    public function __destruct()
    {
        echo "Se destruye";
        self::$cantidad--;
    }

    public function desloguear()
    {
        $nombre = $this->login;
        echo "Ya está deslogueado el usuario $nombre";
    }

    public static function nombreAdmin()
    {
      return self::ADMIN; // self se refiere a la clase actual.
    }

    public static function quienSoy()
    {
      return 'Usuario';
    }

    public static function prueba()
    {
      return static::quienSoy();//Con static se tiene en cuenta la clase desde donde se llama la función
    }
}
