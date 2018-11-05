<?php

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
  if (mb_strlen($fltTitulo) > 255) {
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

  if ($fltDuracion == '') {
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

function comprobarGeneroId(&$error)
{
  $fltGeneroId = filter_input(INPUT_POST, 'genero_id', FILTER_VALIDATE_INT);
  if ($fltGeneroId !== false) {
    // code...
  }
}
