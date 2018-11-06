<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Insertar una nueva película</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <?php
        require './auxiliar.php';
        const PAR = [
            'titulo' => '',
            'anyo' => '',
            'sinopsis' => '',
            'duracion' => '',
            'genero_id' => '',
        ];
        extract(PAR);
            // Filtrado de la entrada
        try {
          $error = [];
          comprobarParametros(PAR);
          extract(array_map('trim', $_POST), EXTR_IF_EXISTS);
          $flt = [];
          $flt['titulo'] = comprobarTitulo($error);
          $flt['anyo'] = comprobarAnyo($error);
          $flt['sinopsis'] = trim(filter_input(INPUT_POST, 'sinopsis'));
          $flt['duracion'] = comprobarDuracion($error);
          $pdo = conectar();
          $flt['genero_id'] = comprobarGeneroId($pdo, $error);
          comprobarErrores($error);
          insertarPelicula($pdo, $flt);
          header('Location: index.php');
        } catch(EmptyParamException $e){
            //No hago nada
        } catch (ParamException $e) {
            header('Location: index.php');
        } catch(ValidationExeception $e) {
            // foreach ($error as $err) {
            //   echo "<h4>$err</h4>";
            // }
        }
        ?>
        <br>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Insertar una nueva película...</h3>
                </div>
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="form-group <?= hasError('titulo', $error) ?>">
                            <label for="titulo" class="control-label">Título</label>
                            <input id="titulo" type="text" name="titulo"
                                   class="form-control" value="<?= $titulo ?>" >
                            <?php mensajeError('titulo', $error) ?>
                        </div>
                        <div class="form-group <?= hasError('anyo', $error) ?>">
                            <label for="anyo" class="control-label">Año</label>
                            <input id="anyo" type="text" name="anyo"
                                   class="form-control" value="<?= $anyo ?>">
                            <?php mensajeError('anyo', $error) ?>
                        </div>
                        <div class="form-group">
                            <label for="sinopsis" class="control-label">Sinopsis</label>
                            <textarea id="sinopsis"
                                      name="sinopsis"
                                      rows="8"
                                      cols="80"
                                      class="form-control"><?= $sinopsis ?></textarea>
                        </div>
                        <div class="form-group <?= hasError('duracion', $error) ?>">
                            <label for="duracion" class="control-label">Duración</label>
                            <input id="duracion" type="text" name="duracion"
                                   class="form-control"
                                   value="<?= $duracion ?>">
                            <?php mensajeError('duracion', $error) ?>
                        </div>
                        <div class="form-group <?= hasError('genero', $error) ?>">
                            <label for="genero_id" class="control-label">Género</label>
                            <select class="form-control" name="genero_id">
                                <option selected value="">--Seleccione un género de la lista--</option>
                                <?php
                                    $pdo = conectar();
                                    $st = $pdo->query('SELECT id, genero FROM generos');
                                    $res = '';
                                    foreach ($st as $fila):
                                        ?>
                                        <option
                                            value="<?= $fila['id'] ?>">
                                            <?= $fila['genero'] ?>
                                        </option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                            <?php mensajeError('genero_id', $error) ?>
                        </div>
                        <input type="submit" value="Insertar"
                               class="btn btn-success">
                        <a href="index.php" class="btn btn-info">Volver</a>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
