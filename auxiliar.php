<?php

class ValidationExeception extends Exception
{
}

class ParamException extends Exception
{
}

class EmptyParamException extends Exception
{
}

function conectar()
{
    return new PDO('pgsql:host=localhost;dbname=fa', 'fa', 'fa');
}

function buscarPelicula($pdo, $id)
{
    $st = $pdo->prepare('SELECT * FROM peliculas WHERE id = :id');
    $st->execute([':id' => $id]);
    return $st->fetch();
}

function comprobarTitulo(&$error)
{
    $fltTitulo = trim(filter_input(INPUT_POST, 'titulo'));
    if ($fltTitulo == '') {
        $error[] = 'El título es obligatorio';
    } elseif (mb_strlen($fltTitulo) > 255) {
        $error[] =  'El título es demasiado largo.';
    }
    return $fltTitulo;
}

function comprobarAnyo(&$error)
{
    $fltAnyo = filter_input(INPUT_POST, 'anyo', FILTER_VALIDATE_INT, [
      'options' => [
        'min_range' => 0,
        'max_range' => 9999,
      ],
    ]);
    if ($fltAnyo === false) {
          $error[] = 'El año no es correcto.';
    }
    return $fltAnyo;
}

function comprobarDuracion(&$error)
{
    $fltDuracion = trim(filter_input(INPUT_POST, 'duracion'));

    if ($fltDuracion === '') {
      $fltDuracion = filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT, [
        'options' => [
          'min_range' => 0,
          'max_range' => 32767,
        ],
      ]);
      if ($fltDuracion === false) {
          $error[] = 'La duración no es correcta.';
      }
    } else {
        $fltDuracion = null;
    }
    return $fltDuracion;
}

function comprobarGeneroId($pdo, &$error)
{
  $fltGeneroId = filter_input(INPUT_POST, 'genero_id', FILTER_VALIDATE_INT);
  if ($fltGeneroId !== false) {
      //Buscar en la base de datos si existe este género.
      $st = $pdo->prepare('SELECT * FROM generos WHERE id = :id');
      $st->execute([':id' => $fltGeneroId]);
      if (!$st->fetch()) {
          $error[] = 'No existe ese género.';
      }
  } else {
      $error[] = 'El género no es correcto.';
  }
  return $fltGeneroId;
}

function insertarPelicula($pdo, $fila)
{
    $st = $pdo->prepare('INSERT INTO peliculas (titulo, anyo, sinopsis, duracion, genero_id)
                         VALUES (:titulo, :anyo, :sinopsis, :duracion, :genero_id)');
    $st->execute($fila);
}

function comprobarParametros($par)
{
  if (empty($_POST)) {
    throw new EmptyParamException;

  }
  if (!empty(array_diff_key($par, $_POST)) ||
      !empty(array_diff_key($_POST, $par))) {
        throw new ParamException;


  }
}

function comprobarErrores($error)
{
  if (!empty($error)) {
      throw new ValidationExeception;

  }
}
