<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();
    
    $service = new UsuarioService();

    $data = json_decode(file_get_contents('php://input'), true);

    $login = $data['login'];

    echo $service->getUsuarioByLogin($login)->serialize();
?>