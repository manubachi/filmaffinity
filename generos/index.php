<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Base de datos');
    menu('generos');
    ?>
    <div class="container">
        <br>
        <?php
        compruebaMensajes('mensaje', 'success');
        compruebaMensajes('error', 'danger');
        ?>
        <div class="row">
            <?php
            $pdo = conectar();
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $pdo->beginTransaction();
                $pdo->exec('LOCK TABLE generos IN SHARE MODE');
                if (!buscarGenero($pdo, $id)) {
                    $_SESSION['error'] = "El género no existe";
                    header('Location: index,php');
                } elseif (compruebaUsoGenero($pdo, $id)) {
                    $_SESSION['error'] = "El género está en uso y no se puede borrar";
                    header('Location: index,php');
                } else {
                    $st = $pdo->prepare('DELETE FROM generos WHERE id = :id');
                    $st->execute([':id' => $id]);
                    $_SESSION['mensaje'] = "Género borrado correctamente";
                    $pdo->commit();
                    header('Location: index,php');
                }
            }
            $buscarGenero = isset($_GET['buscarGenero'])
                ? trim($_GET['buscarGenero']) : '';

            $st = $pdo->prepare('SELECT count(*) AS numero
                                   FROM generos
                                  WHERE position(lower(:genero) in lower(genero)) != 0');
            $st->execute([':genero' => $buscarGenero]);

            $fila = $st->fetch();
            $nfilas = $fila['numero'];
            $npags = ceil($nfilas / FPP);
            $pag = isset($_GET['pag']) &&
                   ctype_digit($_GET['pag']) &&
                   $_GET['pag'] >= 1 &&
                   $_GET['pag'] <= $npags ? (int) $_GET['pag'] : 1;

            $ord = isset($_GET['ord'])
                    ? trim($_GET['ord']) : 'id';

            $st = $pdo->prepare('SELECT *
                                   FROM generos
                                  WHERE position(lower(:genero) in lower(genero)) != 0
                               ORDER BY id
                                  LIMIT :limit
                                 OFFSET :offset');
            $st->execute([
                            ':genero' => $buscarGenero,
                            ':limit' => FPP,
                            ':offset' => ($pag - 1) * FPP,
                        ]);
            ?>
        </div>
        <div class="row" id="busqueda">
            <div class="col-md-12">
                <fieldset>
                    <legend>Buscar...</legend>
                    <form action="" method="get" class="form-inline">
                        <div class="form-group">
                            <label for="buscarGenero">Buscar por género:</label>
                            <input id="buscarGenero" type="text" name="buscarGenero"
                                      value="<?= h($buscarGenero) ?>"
                                      class="form-control">
                        </div>
                            <input type="submit" value="Buscar" class="btn btn-primary">
                    </form>
                </fieldset>
            </div>
        </div>
        <hr>
        <?php
        tablaGeneros($st, $npags, $pag, $buscarGenero) ;
        botonInsertar('insertar.php', 'Insertar un nuevo género');
        politicaCookies() ;
        pie();
