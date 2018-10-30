<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Confirmar Borrado</title>
  </head>
  <body>
    <?php
    require 'auxiliar.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }else {
      //Si no se le manda ningún id, se debe generar una respuesta http.
        header('Location:index.php');
    }

    // Comprobamos que existe la pelicula
    $pdo = conectar();
    if (!buscarPelicula($pdo, $id)) {
        header('Location:index.php');
    }

    ?>
    <h3>¿Seguro que deseas borrar la pelicula?</h3>
    <form action="index.php" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="submit" value="Sí">
        <a href="index.php">No</a>
    </form>
  </body>
</html>
