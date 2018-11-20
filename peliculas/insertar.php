<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Insertar película');
    menu('peliculas');

    if (!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debe iniciar sesión para insertar películas.';
        header('Location: index.php');
    }

    $valores = PAR;
        // Filtrado de la entrada
    try {
        $error = [];
        $pdo = conectar();
        comprobarParametros(PAR);
        $valores = array_map('trim', $_POST);
        $flt = [];
        $flt['titulo'] = comprobarTitulo($error);
        $flt['anyo'] = comprobarAnyo($error);
        $flt['sinopsis'] = trim(filter_input(INPUT_POST, 'sinopsis'));
        $flt['duracion'] = comprobarDuracion($error);
        $flt['genero_id'] = comprobarGeneroId($pdo, $error);
        comprobarErrores($error);
        insertarPelicula($pdo, $flt);
        $_SESSION['mensaje'] = 'Película insertada correctamente';
        header('Location: index.php');
    } catch(EmptyParamException|ValidationException $e){
        //No hago nada
    } catch (ParamException $e) {
        header('Location: index.php');
    }
    mostrarFormulario($valores, $error, 'Insertar') ;
    politicaCookies() ;
    pie();
