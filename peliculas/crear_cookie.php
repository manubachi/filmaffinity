<?php
session_start() ;

setcookie('acepta','1', time()+ 3600 * 24 * 365);
header('Location: index.php');
