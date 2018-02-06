<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();

    $teste = new Teste();
    
    if(isset($_POST['teste']['mensagem'])) {
        $teste->setMensagem($_POST['teste']['mensagem']);
    }

    $message = new ResponseMessage();
    $message->setMessage('Sistema funcionando perfeitamente.');
    $message->setStatus(ResponseMessage::STATUS_OK);
    $message->setEntity($teste);

    echo $message->serialize();
?>