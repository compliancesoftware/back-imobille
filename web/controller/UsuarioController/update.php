<?php
    require('../../classloader.php');
    header('Content-type: application/json; charset=UTF-8');

    ClassLoader::load();
    
    $service = new UsuarioService();

    $data = HttpRequest::getRequestData();

    if($data != null) {
        $usuario = new Usuario();

        $usuario->setId($data['id']);
        $usuario->setNome($data['nome']);
        $usuario->setEmail($data['email']);
        $usuario->setSenha($data['senha']);
        $usuario->setTelefone($data['telefone']);
        $usuario->setPermissao($data['permissao']);
        $usuario->setAtivo($data['ativo']);
        $usuario->setFoto($data['foto']);
        $usuario->setCriadoEm($data['criadoEm']);
        $usuario->setUltimoAcesso($data['ultimoAcesso']);
        $usuario->setEndereco($data['endereco']['id']);
        
        echo $service->updateUsuario($usuario)->serialize();
    }
    else {
        $message = new ResponseMessage();
        $message->setMessage('Entrada inválida ou em formato inválido. [charset must be UTF-8]');

        echo $message->serialize();
    }
?>