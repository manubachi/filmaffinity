<?php session_start() ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar sesión</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <?php
        require '../comunes/auxiliar.php';
        menu('home');
        ?>
        <div class="container">
        <?php
        if (isset($_SESSION['mensaje'])): ?>
          <div class="row">
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['mensaje'] ?>
                </div>
            </div>
            <?php unset($_SESSION['sesion']);
        endif;
         const PAR_LOGIN = ['login' => '', 'password' => ''];
         $valores = PAR_LOGIN;
         try {
            $error = [];
            $pdo = conectar();
            comprobarParametros(PAR_LOGIN);
            $valores = array_map('trim', $_POST);
            $flt = [];
            $flt['login'] = comprobarLogin($error);
            $flt['password'] = comprobarPassword($error);
            $usuario = comprobarUsuario($flt, $pdo, $error);
            comprobarErrores($error);
            if ($usuario === false) {
               $_SESSION['mensaje'] = 'El usuario o la contraseña son incorrectos.';
               header('Location: login.php');
             }else {
               $_SESSION['usuario'] = $usuario['login'];
               header('Location: ../index.php');
           }
        } catch (EmptyParamException|ValidationException $e) {
            // No hago nada
        } catch (ParamException $e) {
            header('Location: ../index.php');
        }
        ?>
            <div class="row">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="login">Usuario:</label>
                        <input class="form-control <?= hasError('login', $error) ?>" type="text" name="login" value="">
                        <?php mensajeError('login', $error) ?>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input class="form-control <?= hasError('password', $error) ?>" type="password" name="password" value="">
                        <?php mensajeError('password', $error)  ?>
                    </div>
                    <button type="submit" class="btn btn-default">Iniciar sesión</button>
                </form>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
