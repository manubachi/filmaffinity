<?php  session_start() ;
    require '../comunes/auxiliar.php';
    cabecera('Confirmar borrado');
    menu('generos');

    if (!isset($_SESSION['usuario'])) {
        $_SESSION['error'] = 'Debe iniciar sesión para poder borrar géneros';
        header('Location: index.php');
    } elseif ($_SESSION['usuario'] != 'admin') {
        $_SESSION['error'] = 'Debe ser administrador para poder borrar géneros';
        header('Location: index.php');
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        header('Location: index.php');
    }

    $pdo = conectar();
    if (!buscarGenero($pdo, $id)) {
        header('Location: index.php');
    }
    preguntarBorrado($id);
    politicaCookies() ;
    pie();
