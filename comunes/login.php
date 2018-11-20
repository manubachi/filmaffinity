<?php session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Iniciar sesión');
    menu('home');
    ?>
    <div class="container">
        <?php
        compruebaMensajes('mensaje', 'success');
        compruebaMensajes('error', 'danger');
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
                $_SESSION['error'] = 'El usuario o la contraseña son incorrectos.';
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
                <div class="form-group <?= hasError('login', $error) ?>">
                    <label for="login">Usuario:</label>
                    <input id="login" class="form-control" type="text" name="login" value="">
                    <?php mensajeError('login', $error) ?>
                </div>
                <div class="form-group <?= hasError('password', $error) ?>">
                    <label for="password">Contraseña:</label>
                    <input id="password" class="form-control" type="password" name="password" value="">
                    <?php mensajeError('password', $error)  ?>
                </div>
                <button type="submit" class="btn btn-default">Iniciar sesión</button>
            </form>
        </div>
    <?php pie();
