<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();
    
    $service = new EnderecoService();

    $endereco = new Endereco();

    $endereco->setEndereco($_POST['endereco']['endereco']);
    $endereco->setBairro($_POST['endereco']['bairro']);
    $endereco->setCidade($_POST['endereco']['cidade']);
    $endereco->setEstado($_POST['endereco']['estado']);
    $endereco->setComplemento($_POST['endereco']['complemento']);
    $endereco->setNumero($_POST['endereco']['numero']);
    $endereco->setCep($_POST['endereco']['cep']);
    $endereco->setReferencias($_POST['endereco']['referencias']);
    $endereco->setLatitude($_POST['endereco']['latitude']);
    $endereco->setLongitude($_POST['endereco']['longitude']);

    echo $service->createEndereco($endereco)->serialize();
?>