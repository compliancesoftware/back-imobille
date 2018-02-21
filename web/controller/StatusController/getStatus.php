<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();

    $teste = new Teste();
    
    $data = json_decode(file_get_contents('php://input'), true);
    $teste->setMensagem($data['mensagem']);

    $message = new ResponseMessage();
    $message->setMessage('Sistema funcionando perfeitamente.');
    $message->setStatus(ResponseMessage::STATUS_OK);
    $message->setEntity($teste);

    echo $message->serialize();
?>