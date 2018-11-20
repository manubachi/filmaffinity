<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Modificar película');
    menu('peliculas');

    if (!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debe iniciar sesión para modificar películas.';
        header('Location: index.php');
    }

    try {
        $pdo = conectar();
        $error = [];
        $id = comprobarId();
        $fila = comprobarPelicula($pdo, $id);
        comprobarParametros(PAR);
        $valores = array_map('trim', $_POST);
        $flt = [];
        $flt['titulo'] = comprobarTitulo($error);
        $flt['anyo'] = comprobarAnyo($error);
        $flt['sinopsis'] = trim(filter_input(INPUT_POST, 'sinopsis'));
        $flt['duracion'] = comprobarDuracion($error);
        $flt['genero_id'] = comprobarGeneroId($pdo, $error);
        comprobarErrores($error);
        modificarPelicula($pdo, $flt, $id);
        $_SESSION['mensaje'] = 'Película modificada correctamente';
        header('Location: index.php');
    } catch(EmptyParamException|ValidationException $e){
        //No hago nada
    } catch (ParamException $e) {
        header('Location: index.php');
    }
    mostrarFormulario($fila, $error, 'Modificar');
    politicaCookies();
    pie();
