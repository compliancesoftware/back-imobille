<?php
    class UsuarioService{
        private $usuarioDao = null;

        public function __construct() {
            $this->usuarioDao = new UsuarioDao();
        }

        public function getUsuarioByLogin($login) {
            $response = $this->usuarioDao->getUsuarioByLogin($login);
            $usuario = $response->getEntity();

            if($usuario != null) {
                $usuarioOutput = new Usuario();
                $usuarioOutput->setId($usuario->getId());
                $usuarioOutput->setNome($usuario->getNome());
                $usuarioOutput->setEmail($usuario->getEmail());
                $usuarioOutput->setAtivo($usuario->getAtivo());
                $usuarioOutput->setFoto($usuario->getFoto());

                $response->setEntity($usuarioOutput);
            }

            return $response;
        }



        public function listUsuarios() {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();

            if($autenticado != null) {
                if(LoginSession::canListAllUsers($autenticado)) {
                    return $this->usuarioDao->listUsuarios();
                }
                else {
                    $message = new ResponseMessage();
                    $message->setMessage('Usuário autenticado');
                    $message->setStatus(ResponseMessage::STATUS_OK);
                    $message->setEntity($autenticado);
    
                    return $message;
                }
            }
            else {
                return $response;
            }
        }

        public function listClientes() {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();

            if($autenticado != null) {
                if(LoginSession::canListAllUsers($autenticado)) {
                    return $this->usuarioDao->listClientes();
                }
                else {
                    $message = new ResponseMessage();
                    $message->setMessage('Usuário autenticado');
                    $message->setStatus(ResponseMessage::STATUS_OK);
                    $message->setEntity($autenticado);
    
                    return $message;
                }
            }
            else {
                return $response;
            }
        }

        public function updateUsuario($usuario) {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();

            if($autenticado != null) {
                if(LoginSession::canUpdateUsers($autenticado) || ($usuario->getId() == $autenticado->getId())) {
                    return $this->usuarioDao->updateUsuario($usuario);
                }
                else {
                    $message = new ResponseMessage();
                    $message->setMessage('Sem permissões suficientes.');
    
                    return $message;
                }
            }
            else {
                return $response;
            }
        }

        public function createUsuario($usuario) {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();

            if($autenticado != null) {
                if(LoginSession::canUpdateUsers($autenticado)) {
                    $usuario->setAtivo(true);

                    $dateTime = new DateTime();
                    $now = $dateTime->format('Y-m-d H:i:s');

                    $usuario->setCriadoEm($now);
                    $usuario->setUltimoAcesso($now);

                    return $this->usuarioDao->createUsuario($usuario);
                }
                else {
                    $message = new ResponseMessage();
                    $message->setMessage('Sem permissões suficientes.');
    
                    return $message;
                }
            }
            else {
                return $response;
            }
        }
    }
    
?>