<?php session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Confirmar borrado');
    menu('peliculas');

    if (!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debe iniciar sesión para poder borrar películas';
        header('Location: index.php');
    } elseif ($_SESSION['usuario'] != 'admin') {
        $_SESSION['error'] = 'Debe ser administrador para poder borrar películas';
        header('Location: index.php');
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        header('Location: index.php');
    }

    $pdo = conectar();
    if (!buscarPelicula($pdo, $id)) {
        header('Location: index.php');
    }
    preguntarBorrado($id);
    politicaCookies() ;
    pie();
