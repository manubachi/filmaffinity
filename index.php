<?php session_start();
        require 'comunes/auxiliar.php';
        cabecera('FilmAffinity');
        menu('home');
        ?>
        <div class="container">
            <h1 align="center" class="bg-primary">FilmAffinity</h1>
            <p class="text-primary">Página de pruebas con base de datos de peliculas</p>
            <?php
            pie();
