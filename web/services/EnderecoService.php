<?php
    class EnderecoService{
        private $enderecoDao = null;

        public function __construct() {
            $this->enderecoDao = new EnderecoDao();
        }

        public function getEnderecoById($id) {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();

            if($autenticado != null) {
                if(LoginSession::canListAddress($autenticado)) {
                    return $this->enderecoDao->getEnderecoById($id);
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

        public function listEnderecos() {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();

            if($autenticado != null) {
                if(LoginSession::canListAddress($autenticado)) {
                    return $this->enderecoDao->listEnderecos();
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

        public function createEndereco($endereco) {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();
            
            if($autenticado != null) {
                if(LoginSession::canUpdateAddress($autenticado)){
                    return $this->enderecoDao->createEndereco($endereco);
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

        public function updateEndereco($endereco) {
            $response = LoginSession::authenticate();
            $autenticado = $response->getEntity();
            
            if($autenticado != null) {
                if(LoginSession::canUpdateAddress($autenticado)){
                    return $this->enderecoDao->updateEndereco($endereco);
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