<?php
const PAR = [
    'titulo' => '',
    'anyo' => '',
    'sinopsis' => '',
    'duracion' => '',
    'genero_id' => '',
];

const GEN = [
    'genero' => '',
];

class ValidationException extends Exception
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

function buscarUsuario($pdo, $id)
{
    $st = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
    $st->execute([':id' => $id]);
    return $st->fetch();
}

function comprobarId()
  {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === null || $id === false) {
      throw new ParamException();
    }
    return $id;
}

function comprobarPelicula($pdo, $id)
{
  $fila = buscarPelicula($pdo, $id);
  if ($fila === false) {
      throw new ParamException();
  }
  return $fila;
}

function comprobarTitulo(&$error)
{
    $fltTitulo = trim(filter_input(INPUT_POST, 'titulo'));
    if ($fltTitulo == '') {
        $error['titulo'] = 'El título es obligatorio';
    } elseif (mb_strlen($fltTitulo) > 255) {
        $error['titulo'] =  'El título es demasiado largo.';
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
          $error['anyo'] = 'El año no es correcto.';
    }
    return $fltAnyo;
}

function comprobarDuracion(&$error)
{
    $fltDuracion = trim(filter_input(INPUT_POST, 'duracion'));

    if ($fltDuracion !== '') {
      $fltDuracion = filter_input(INPUT_POST, 'duracion', FILTER_VALIDATE_INT, [
        'options' => [
          'min_range' => 0,
          'max_range' => 32767,
        ],
      ]);
      if ($fltDuracion === false) {
          $error['duracion'] = 'La duración no es correcta.';
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
          $error['genero_id'] = 'No existe ese género.';
      }
  } else {
      $error['genero_id'] = 'El género no es correcto.';
  }
  return $fltGeneroId;
}

function insertarPelicula($pdo, $fila)
{
    $st = $pdo->prepare('INSERT INTO peliculas (titulo, anyo, sinopsis, duracion, genero_id)
                         VALUES (:titulo, :anyo, :sinopsis, :duracion, :genero_id)');
    $st->execute($fila);
}

function modificarPelicula($pdo, $fila, $id)
{
    $st = $pdo->prepare('UPDATE peliculas
                            SET titulo = :titulo
                              , anyo = :anyo
                              , sinopsis = :sinopsis
                              , duracion = :duracion
                              , genero_id = :genero_id
                          WHERE id = :id');
    $st->execute($fila + ['id' => $id]);
}

function comprobarParametros($par)
{
  if (empty($_POST)) {
    throw new EmptyParamException();
  }
  if (!empty(array_diff_key($par, $_POST)) ||
      !empty(array_diff_key($_POST, $par))) {
        throw new ParamException();
  }
}

function comprobarErrores($error)
{
  if (!empty($error)) {
      throw new ValidationException();
  }
}

function hasError($key, $error)
{
  return array_key_exists($key, $error) ? 'has-error' : '';
}

function mensajeError($key, $error)
{
  if (isset($error[$key])) :?>
    <span id="helpBlock" class="help-block"><?=$error[$key]?></span>
  <?php endif;
}

function mostrarFormulario($valores, $error, $accion)
{
    extract($valores);

    ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $accion ?> película...</h3>
            </div>
            <div class="panel-body">
                <form action="" method="post">
                    <div class="form-group <?= hasError('titulo', $error) ?>">
                        <label for="titulo" class="control-label">Título</label>
                        <input id="titulo" type="text" name="titulo"
                               class="form-control" value="<?= h($titulo) ?>" >
                        <?php mensajeError('titulo', $error) ?>
                    </div>
                    <div class="form-group <?= hasError('anyo', $error) ?>">
                        <label for="anyo" class="control-label">Año</label>
                        <input id="anyo" type="text" name="anyo"
                               class="form-control" value="<?= h($anyo) ?>">
                        <?php mensajeError('anyo', $error) ?>
                    </div>
                    <div class="form-group">
                        <label for="sinopsis" class="control-label">Sinopsis</label>
                        <textarea id="sinopsis"
                                  name="sinopsis"
                                  rows="8"
                                  cols="80"
                                  class="form-control"><?= h($sinopsis) ?></textarea>
                    </div>
                    <div class="form-group <?= hasError('duracion', $error) ?>">
                        <label for="duracion" class="control-label">Duración</label>
                        <input id="duracion" type="text" name="duracion"
                               class="form-control"
                               value="<?= h($duracion) ?>">
                        <?php mensajeError('duracion', $error) ?>
                    </div>
                    <div class="form-group <?= hasError('genero_id', $error) ?>">
                        <label for="genero_id" class="control-label">Género</label>
                        <select class="form-control" name="genero_id">
                            <?php
                                $pdo = conectar();
                                $st = $pdo->query('SELECT id, genero FROM generos');
                                $res = '';
                                foreach ($st as $fila):
                                    ?>
                                    <option <?= selected($fila['id'], $genero_id) ?>
                                        value="<?= h($fila['id']) ?>">
                                        <?= h($fila['genero']) ?>
                                    </option>
                                    <?php
                                endforeach;
                            ?>
                        </select>
                        <?php mensajeError('genero_id', $error) ?>
                    </div>
                    <input type="submit" value="<?= $accion ?>"
                           class="btn btn-success">
                    <a href="index.php" class="btn btn-info">Volver</a>
                </form>
            </div>
        </div>
  <?php
}

function selected($a,$b)
{
  return $a == $b ? 'selected' : '';
}

function h($cadena)
{
    return htmlspecialchars($cadena, ENT_QUOTES);
}

function buscarGenero($pdo, $id)
{
    $st = $pdo->prepare('SELECT * FROM generos WHERE id = :id');
    $st->execute([':id' => $id]);
    return $st->fetch();
}

function comprobarNomGenero(&$error)
{
    $fltGenero = trim(filter_input(INPUT_POST, 'genero'));
    if ($fltGenero == '') {
        $error['genero'] = 'El nombre del género es obligatorio';
    } elseif (mb_strlen($fltGenero) > 255) {
        $error['genero'] =  'El nombre del género es demasiado largo.';
    }
    $st = $pdo->prepare('SELECT * FROM generos WHERE lower(genero) = lower(:genero)');
    $st->execute([':genero' => $fltGenero]);
    if ($st->fetch()) {
      $error['genero'] = 'Ya existe dicho género';
    }
    return $fltGenero;
}


function mostrarFormularioGen($valores, $error, $accion)
{
    extract($valores);

    ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $accion ?> género...</h3>
            </div>
            <div class="panel-body">
                <form action="" method="post">
                    <div class="form-group <?= hasError('genero', $error) ?>">
                        <label for="genero" class="control-label">Género</label>
                        <input id="genero" type="text" name="genero"
                               class="form-control" value="<?= h($genero) ?>" >
                        <?php mensajeError('genero', $error) ?>
                    </div>
                    <input type="submit" value="<?= $accion ?>"
                           class="btn btn-success">
                    <a href="index.php" class="btn btn-info">Volver</a>
                </form>
            </div>
        </div>
  <?php
}

function insertarGenero($pdo, $fila)
{
    $st = $pdo->prepare('INSERT INTO generos (genero)
                         VALUES (:genero)');
    $st->execute($fila);
}

function comprobarGenero($pdo, $id)
{
  $fila = buscarGenero($pdo, $id);
  if ($fila === false) {
      throw new ParamException();
  }
  return $fila;
}

function modificarGenero($pdo, $fila, $id)
{
    $st = $pdo->prepare('UPDATE generos
                            SET genero = :genero
                          WHERE id = :id');
    $st->execute($fila + ['id' => $id]);
}

function compruebaUsoGenero($pdo, $id)
{
    $st = $pdo->prepare('SELECT * from peliculas WHERE genero_id = :id;');
    $st->execute([':id' => $id]);
    return $st->fetch();
}

function politicaCookies()
{
    if(!isset($_COOKIE['acepta'])): ?>
      <nav class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
        <div class="container">
          <p class="navbar-text"> Tienes que aceptar las políticas de cookies</p>
          <p class="navbar-text navbar-right">
              <a href="crear_cookie.php" class="btn btn-success">Aceptar cookies</a>
          </p>
        </div>
      </nav>
  <?php endif ;
}

function pie()
{
    ?>
    <br>
    <br>
    <footer class="footer navbar-inverse">
        <div class="container">
            <p class="navbar-text"> FilmAffinity </p>
            <p class="navbar-text"> Manuel Alejandro Benítez García </p>
        </div>
    </footer>
    <?php
}

function menu($accion)
{
    ?>
    <nav class="navbar navbar-default navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">FilmAffinity</a>
            </div>
            <div class="collapse navbar-collapse navbar-center">
              <ul class="nav navbar-nav">
                <li role="separator" class="divider"></li>
                <li class="<?= $accion == 'home' ? 'active' : '' ?>"><a href="../index.php">Home</a></li>
                <li class="<?= $accion == 'peliculas' ? 'active' : '' ?>"><a href="../peliculas/index.php">Peliculas</a></li>
                <li class="<?= $accion == 'generos' ? 'active' : '' ?>"><a href="../generos/index.php">Géneros</a></li>
              </ul>
              <div class="navbar-text navbar-right">
                <?php if (isset($_SESSION['usuario'])) :  ?>
                  <span class="label label-info glyphicon glyphicon-user"> <?= $_SESSION['usuario']?></span>
                  <a href="../comunes/logout.php" class="btn btn-success">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout
                  </a>
                <?php else:  ?>
                  <a href="../comunes/login.php" class="btn btn-success">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Login
                  </a>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </nav>
    <?php
}

function comprobarLogin(&$error)
{
    $login = trim(filter_input(INPUT_POST, 'login'));
    if ($login === '') {
        $error['login'] = 'El nombre de usuario no puede estar vacío.';
    } else{

      return $login;
    }
}

function comprobarPassword(&$error)
{
    $password = trim(filter_input(INPUT_POST, 'password'));
    if ($password === '') {
        $error['password'] = 'La contraseña no puede estar vacía.';
    }else{

      return $password;
    }
}

function comprobarUsuario($valores, $pdo, &$error)
{
    extract($valores);
    $st = $pdo->prepare('SELECT *
                           FROM usuarios
                          WHERE login = :login');
    $st->execute(['login' => $login]);
    $fila = $st->fetch();
    if ($fila !== false) {
        if (password_verify($password, $fila['password'])) {
            return $fila;
        }
    }
    $error['sesion'] = 'El usuario o la contraseña son incorrectos.';
    return false;
}
