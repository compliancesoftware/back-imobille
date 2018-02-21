<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();
    
    $data = HttpRequest::getRequestData();

    if($data != null) {
        $usuario = new Usuario();

        $usuario->setNome($data['nome']);
        $usuario->setEmail($data['email']);
        $usuario->setSenha($data['senha']);
        $usuario->setTelefone($data['telefone']);
        $usuario->setPermissao($data['permissao']);
        $usuario->setFoto($data['foto']);
        $usuario->setEndereco($data['endereco']['id']);
        
        $service = new UsuarioService();
        
        echo $service->createUsuario($usuario)->serialize();
    }
    else {
        $message = new ResponseMessage();
        $message->setMessage('Entrada inválida ou em formato inválido. [charset must be UTF-8]');

        echo $message->serialize();
    }
?>