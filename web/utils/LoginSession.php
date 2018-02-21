<?php
    class LoginSession{
        public static function canUpdateAddress($usuario) {
            return ($usuario->getPermissao() == 'Administrador' || 
                    $usuario->getPermissao() == 'Gerente');
        }

        public static function canListAddress($usuario) {
            return ($usuario->getPermissao() == 'Administrador' || 
                    $usuario->getPermissao() == 'Gerente' || 
                    $usuario->getPermissao() == 'Marketing' || 
                    $usuario->getPermissao() == 'Corretor');
        }

        public static function canUpdateUsers($usuario) {
            return ($usuario->getPermissao() == 'Administrador');
        }

        public static function canListAllUsers($usuario) {
            return ($usuario->getPermissao() == 'Administrador');
        }

        public static function canListAllClients($usuario) {
            return ($usuario->getPermissao() == 'Administrador' || 
                    $usuario->getPermissao() == 'Gerente' || 
                    $usuario->getPermissao() == 'Marketing');
        }

        public static function isClient($usuario) {
            return ($usuario->getPermissao() == 'Cliente');
        }

        public static function hasValidEntry() {
            if(isset($_SERVER['HTTP_LOGIN']) && isset($_SERVER['HTTP_SENHA'])) {
                return true;
            }
            else {
                return false;
            }
        }

        public static function authenticate() {
            $response = new ResponseMessage();

            if(LoginSession::hasValidEntry()) {
                $login = $_SERVER['HTTP_LOGIN'];
                $senha = base64_decode($_SERVER['HTTP_SENHA']);
                $usuarioDao = new UsuarioDao();
                $response = $usuarioDao->authenticate($login, $senha);
                $autenticado = $response->getEntity();

                if($autenticado == null) {
                    $response->setMessage("Falha de autenticação.");
                    $response->setStatus(ResponseMessage::STATUS_ERROR);
                }
                else {
                    $dateTime = new DateTime();
                    $now = $dateTime->format('Y-m-d H:i:s');
                    $autenticado->setUltimoAcesso($now);
                    $autenticado->setEndereco($autenticado->getEndereco()->getId());
                    $response = $usuarioDao->updateUsuario($autenticado);
                }
            }
            else {
                $response->setMessage("Falha de autenticação.");
            }
            
            return $response;
        }
    }
?>