<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();
    
    $data = HttpRequest::getRequestData();

    if($data != null) {
        $endereco = new Endereco();

        $endereco->setId($data['id']);
        $endereco->setEndereco($data['endereco']);
        $endereco->setBairro($data['bairro']);
        $endereco->setCidade($data['cidade']);
        $endereco->setEstado($data['estado']);
        $endereco->setComplemento($data['complemento']);
        $endereco->setNumero($data['numero']);
        $endereco->setCep($data['cep']);
        $endereco->setReferencias($data['referencias']);
        $endereco->setLatitude($data['latitude']);
        $endereco->setLongitude($data['longitude']);

        $service = new EnderecoService();
        
        echo $service->updateEndereco($endereco)->serialize();
    }
    else {
        $message = new ResponseMessage();
        $message->setMessage('Entrada inválida ou em formato inválido. [charset must be UTF-8]');

        echo $message->serialize();
    }
?>