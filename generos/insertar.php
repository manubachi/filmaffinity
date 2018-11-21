<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Insertar nuevo género');
    menu('generos');

    if (!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debe iniciar sesión para insertar géneros.';
        header('Location: index.php');
    }

    $valores = GEN;
        // Filtrado de la entrada
    try {
        $error = [];
        $pdo = conectar();
        comprobarParametros(GEN);
        $valores = array_map('trim', $_POST);
        $flt = [];
        $flt['genero'] = comprobarNomGenero($pdo, $error);
        comprobarErrores($error);
        insertarGenero($pdo, $flt);
        header('Location: index.php');
    } catch(EmptyParamException|ValidationException $e){
        //No hago nada
    } catch (ParamException $e) {
        header('Location: index.php');
    }
    mostrarFormularioGen($valores, $error, 'Insertar') ;
    politicaCookies() ;
    pie();
