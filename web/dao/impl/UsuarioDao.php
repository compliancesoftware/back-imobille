<?php
    class UsuarioDao extends Dao {
        
        public function __construct() {
            parent::__construct();
        }

        private function restructUsuario($response) {
            if($response != null && $response->getEntity() != null) {
                $entity = $response->getEntity();
                if(is_array($entity)) {
                    foreach($entity as &$usuario) {
                        $id = $usuario->getEndereco();
                        $endereco = new Endereco();
                        $endereco = parent::getById($id,$endereco);

                        $endereco = $endereco->getEntity();

                        $usuario->setEndereco($endereco);
                        $usuario->setSenha('<secret>');
                        if($usuario->getAtivo() == '1') {
                            $usuario->setAtivo(true);
                        }
                        else {
                            $usuario->setAtivo(false);
                        }
                    }
                }
                else {
                    $id = $entity->getEndereco();
                    $endereco = new Endereco();
                    $endereco = parent::getById($id,$endereco);

                    $endereco = $endereco->getEntity();
                    
                    $entity->setEndereco($endereco);
                    $entity->setSenha('<secret>');
                    if($entity->getAtivo() == '1') {
                        $entity->setAtivo(true);
                    }
                    else {
                        $entity->setAtivo(false);
                    }
                }
            }
        }

        public function getUsuarioById($id) {
            $usuario = new Usuario();
            $response = parent::getById($id, $usuario);

            $this->restructUsuario($response);

            return $response;
        }

        private function getUsuarioByNome($nome) {
            $usuario = new Usuario();

            $filterVars = array();
            $filterVars[] = 'nome';

            $filterValues = array();
            $filterValues[] = $nome;

            $response = parent::retrieveWithConditions($usuario, $filterVars, $filterValues);

            $this->restructUsuario($response);

            if(is_array($response->getEntity())) {
                if(count($response->getEntity()) > 0) {
                    $response->setEntity($response->getEntity()[0]);
                }
                else {
                    $response->setEntity(null);
                }
            }

            return $response;
        }

        private function getUsuarioByEmail($email) {
            $usuario = new Usuario();

            $filterVars = array();
            $filterVars[] = 'email';

            $filterValues = array();
            $filterValues[] = $email;

            $response = parent::retrieveWithConditions($usuario, $filterVars, $filterValues);

            $this->restructUsuario($response);

            if(is_array($response->getEntity())) {
                if(count($response->getEntity()) > 0) {
                    $response->setEntity($response->getEntity()[0]);
                }
                else {
                    $response->setEntity(null);
                }
            }

            return $response;
        }

        public function getUsuarioByLogin($login) {
            $found = $this->getUsuarioByNome($login);
            if($found->getEntity() == null) {
                $found = $this->getUsuarioByEmail($login);
            }
            return $found;
        }

        public function listUsuarios() {
            $usuario = new Usuario();

            $filterVars = array();
            $filterVars[] = 'permissao';

            $filterValues = array();
            $filterValues[] = 'Administrador';
            $filterValues[] = 'Gerente';
            $filterValues[] = 'Marketing';
            $filterValues[] = 'Corretor';

            $response = parent::retrieveWithConditions($usuario, $filterVars, $filterValues);

            $this->restructUsuario($response);

            return $response;
        }

        public function listClientes() {
            $usuario = new Usuario();

            $filterVars = array();
            $filterVars[] = 'permissao';

            $filterValues = array();
            $filterValues[] = 'Cliente';

            $response = parent::retrieveWithConditions($usuario, $filterVars, $filterValues);

            $this->restructUsuario($response);

            return $response;
        }

        public function createUsuario($usuario) {
            $response = parent::persist($usuario);

            $this->restructUsuario($response);

            return $response;
        }

        public function updateUsuario($usuario) {
            $novaSenha = $usuario->getSenha();

            if($novaSenha == '<secret>') {
                $senha = parent::getById($usuario->getId(), $usuario)->getEntity()->getSenha();
                $usuario->setSenha($senha);
            }
            
            $response = parent::update($usuario);

            $this->restructUsuario($response);

            return $response;
        }

        public function authenticate($login, $senha) {
            $response = new ResponseMessage();

            try {
                $object = new Usuario();
                $class = get_class($object);
                $entity = $object->entityName();
                $connection = $this->conn;
                if($connection != null) {
                    $sql = 'SELECT * FROM '.$entity.' WHERE (nome = :nome or email = :email) and senha = :senha';
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(':nome',$login);
                    $statement->bindParam(':email',$login);
                    $statement->bindParam(':senha',$senha);
                    $statement->execute();
                    
                    $resultsArray = array();
            
                    if($statement) {
                        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
                        while($row = $statement->fetch()) {
                            $resultsArray[] = $row;
                        }
                    }

                    if(count($resultsArray) > 0) {
                        $response->setStatus(ResponseMessage::STATUS_OK);
                        $response->setMessage('Usuário autenticado');
                        $response->setEntity($resultsArray[0]);
                    }
                    else {
                        $response->setStatus(ResponseMessage::STATUS_ERROR);
                        $response->setMessage('Falha de Autenticação');
                        $response->setEntity(null);
                    }
                }
                else {
                    $response = new ResponseMessage();
                    $this->setMessage('Conexão inválida.');
                }
            } catch(Exception $e) {
                $response->setMessage('Error: '.$e->getMessage());
                $response->setStatus(ResponseMessage::STATUS_ERROR);
            }

            $this->restructUsuario($response);

            return $response;
        }
    }
?>