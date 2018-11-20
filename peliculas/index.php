<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Base de datos');
    menu('peliculas');
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
                $pdo->exec('LOCK TABLE peliculas IN SHARE MODE');
                if (!buscarPelicula($pdo, $id)) {
                    $_SESSION['error'] = "La película no existe.";
                    header('Location: index.php');
                } else {
                    $st = $pdo->prepare('DELETE FROM peliculas WHERE id = :id');
                    $st->execute([':id' => $id]);
                    $_SESSION['mensaje'] = "Película borrada correctamente.";
                    $pdo->commit();
                    header('Location: index.php');
                }
            }
            $buscarTitulo = isset($_GET['buscarTitulo']) ?
                trim($_GET['buscarTitulo']): '';

            $st = $pdo->prepare('SELECT p.*, genero
                                   FROM peliculas p
                                   JOIN generos g
                                     ON genero_id = g.id
                                  WHERE position(lower(:titulo) in lower(titulo)) != 0
                                     OR position(lower(:titulo) in lower(genero)) != 0
                               ORDER BY id');
            $st->execute([':titulo' => $buscarTitulo]);
            ?>
        </div>
        <div class="row" id="busqueda">
            <div class="col-md-12">
                <fieldset>
                    <legend>Buscar...</legend>
                    <form action="" method="get" class="form-inline">
                        <div class="form-group">
                            <label for="buscarTitulo">Buscar por título o género:</label>
                            <input id="buscarTitulo" type="text" name="buscarTitulo"
                                    value="<?= $buscarTitulo ?>"
                                    class="form-control">
                        </div>
                        <input type="submit" value="Buscar" class="btn btn-primary">
                    </form>
                </fieldset>
            </div>
        </div>
        <hr>
        <?php
        tablaPeliculas($st) ;
        botonInsertar('insertar.php', 'Insertar una nueva película');
        politicaCookies() ;
        pie();
