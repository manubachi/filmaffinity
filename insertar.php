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
        <br>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Insertar una nueva película...</h3>
                </div>
                <div class="panel-body">
                    <form action="#" method="post">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input id="titulo" type="text" name="titulo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="anyo">Año</label>
                            <input id="anyo" type="text" name="anyo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="sinopsis">Sinopsis</label>
                            <textarea id="sinopsis" name="sinopsis" rows="8" cols="80" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duración</label>
                            <input id="duracion" type="text" name="duracion" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="genero_id">Género</label>
                            <input id="genero_id" type="text" name="genero_id" class="form-control">
                        </div>
                        <input type="submit" value="Insertar" class="btn btn-success">
                        <a href="index.php" class="btn btn-info">Volver</a>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
