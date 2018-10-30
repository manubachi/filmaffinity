<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Bases de datos</title>
    </head>
    <body>
        <?php
        $buscarTitulo = isset($_GET['buscarTitulo'])
                      ? trim($_GET['buscarTitulo'])
                      : '';
        $pdo = new PDO('pgsql:host=localhost;dbname=fa', 'fa', 'fa');
        $st = $pdo->prepare('SELECT p.*, genero
                               FROM peliculas p
                               JOIN generos g
                                 ON genero_id = g.id
                              WHERE position(lower(:titulo) in lower(titulo)) != 0');
        $st->execute([':titulo' => $buscarTitulo]);
        ?>
        <div>
            <fieldset>
                <legend>Buscar...</legend>
                <form action="" method="get">
                    <label for="buscarTitulo">Buscar por título:</label>
                    <input id="buscarTitulo" type="text" name="buscarTitulo"
                           value="<?= $buscarTitulo ?>">
                    <input type="submit" value="Buscar">
                </form>
            </fieldset>
        </div>
        <div style="margin-top: 20px">
            <table border="1" style="margin:auto">
                <thead>
                    <th>Título</th>
                    <th>Año</th>
                    <th>Sinopsis</th>
                    <th>Duración</th>
                    <th>Género</th>
                </thead>
                <tbody>
                    <?php foreach ($st as $fila): ?>
                        <tr>
                            <td><?= $fila['titulo'] ?></td>
                            <td><?= $fila['anyo'] ?></td>
                            <td><?= $fila['sinopsis'] ?></td>
                            <td><?= $fila['duracion'] ?></td>
                            <td><?= $fila['genero'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
