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
            const OPCIONES = ['Título','Año','Duración','Género'];

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
            $buscar = isset($_GET['buscar']) ?
                trim($_GET['buscar']): '';
            $buscador = isset($_GET['buscador']) ?
                trim($_GET['buscador']): '';
            $st = encontrarPelicula($pdo,$buscador,$buscar);
            ?>
        </div>
        <div class="row" id="busqueda">
            <div class="col-md-12">
                <fieldset>
                    <legend>Buscar...</legend>
                    <form action="" method="get" class="form-inline">
                        <div class="form-group">
                            <label for="buscador">Buscar por :</label>
                            <select  name='buscador'><?php
                                foreach (OPCIONES as $op):
                                  ?>
                                    <option value="<?= $op ?>"  <?= selected($buscador,$op)?>>
                                        <?= $op ?>
                                    </option>
                                  <?php
                                endforeach;
                                ?>
                             </select>
                            <input id="buscar" type="text" name="buscar"
                            value="<?= $buscar ?>" class="form-control">
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
