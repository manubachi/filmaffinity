<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Modificar género');
    menu('generos');

    if (!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debe iniciar sesión para modificar géneros.';
        header('Location: index.php');
    }
    try {
        $pdo = conectar();
        $error = [];
        $id = comprobarId();
        $fila = comprobarGenero($pdo, $id);
        comprobarParametros(GEN);
        $valores = array_map('trim', $_POST);
        $flt = [];
        $flt['genero'] = comprobarNomGenero($error);
        comprobarErrores($error);
        modificarGenero($pdo, $flt, $id);
        header('Location: index.php');
    } catch(EmptyParamException|ValidationException $e){
        //No hago nada
    } catch (ParamException $e) {
        header('Location: index.php');
    }
    mostrarFormularioGen($fila, $error, 'Modificar');
    politicaCookies() ;
    pie();
