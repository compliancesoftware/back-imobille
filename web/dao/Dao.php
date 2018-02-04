<?php
    class Dao {
        
        protected $message;
        protected $conn;

        public function __construct() {
            $this->message = new ResponseMessage();

            try {
                $env = getenv('CLEARDB_DATABASE_URL');
                $envParts = explode('@',$env);
                $envPart1 = $envParts[0];
                $envPart2 = $envParts[1];
                $serverAndUserAndPassword = explode(':',str_replace('//','',$envPart1));
                
                $server = $serverAndUserAndPassword[0];
                $username = $serverAndUserAndPassword[1];
                $password = $serverAndUserAndPassword[2];
                
                $hostAndPortAndDatabase = explode(':',$envPart2);
                if(count($hostAndPortAndDatabase) > 1) {
                    $host = $hostAndPortAndDatabase[0];
                    $portAndDatabase = explode('/',$hostAndPortAndDatabase[1]);
                    $port = $portAndDatabase[0];
                    $database = $portAndDatabase[1];
                }
                else {
                    $hostAndDatabase = explode('/',$envPart2);
                    $port = null;
                    $host = $hostAndDatabase[0];
                    $database = $hostAndDatabase[1];
                }

                $database = str_replace('?reconnect=true','',$database);

                if($port != null) {
                    $dsn = $server.':host='.$host.';port='.$port.';dbname='.$database;
                }
                else {
                    $dsn = $server.':host='.$host.';dbname='.$database;
                }
        
                $this->conn = new PDO($dsn, $username, $password, array(PDO::ATTR_PERSISTENT => true));
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $this->message->setMessage('Conexão bem sucedida.');
                $this->message->setStatus(ResponseMessage::STATUS_OK);
            } catch(PDOException $e) {
                $this->message->setMessage('Erro ao tentar conectar com banco de dados: '.$e->getMessage());
                $this->message->setStatus(ResponseMessage::STATUS_ERROR);
            }
        }

        public function getMessage() {
            return $this->message;
        }

        public function getConnection() {
            return $this->conn;
        }

        protected function retrieve($object) {
            try {
                $class = get_class($object);
                $entity = $object->entityName();
                $connection = $this->conn;
                if($connection != null) {
                    $sql = 'SELECT * FROM '.strtolower($entity);
                    $statement = $connection->query($sql);
                    
                    $resultsArray = array();
            
                    if($statement) {
                        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
                        while($row = $statement->fetch()) {
                            $resultsArray[] = $row;
                        }
                    }

                    $this->message->setStatus(ResponseMessage::STATUS_OK);
                    $this->message->setMessage('Lista de '.$entity);
                    $this->message->setEntity($resultsArray);
                }
                else {
                    $this->message = new ResponseMessage();
                    $this->setMessage('Conexão inválida.');
                }

                return $this->message;
            } catch(Exception $e) {
                $mensagem = $this->message;
                $mensagem->setMessage('Error: '.$e->getMessage());
                $mensagem->setStatus(ResponseMessage::STATUS_ERROR);
                return $mensagem;
            }
        }

        protected function getById($id, $object) {
            try {
                $this->message = new ResponseMessage();

                $class = get_class($object);
                $entity = $object->entityName();

                $connection = $this->conn;
                if($connection != null) {
                    $statement = $connection->prepare('SELECT * FROM '.strtolower($entity).' WHERE id = :id');
                    $statement->bindParam(':id',$id);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_CLASS, $class);
                    $found = $statement->fetch();
                    
                    $this->message->setStatus(ResponseMessage::STATUS_OK);
                    $this->message->setMessage($entity.' obtido com êxito.');
                    $this->message->setEntity($found);
                }
                else {
                    $this->message = new ResponseMessage();
                    $this->setMessage('Conexão inválida.');
                }

                return $this->message;
            } catch(Exception $e) {
                $this->message = new ResponseMessage();
                $this->message->setMessage('Error: '.$e->getMessage());
                return $this->message;
            }
        }

        protected function persist($object) {
            $this->message = new ResponseMessage();

            $class = get_class($object);
            $entity = $object->entityName();
            $objvars = $object->read();
            $objdata = '';
            $valuePoints = '';
            $objectToPersist = array();
            foreach($objvars as $key=>$value) {
                $objdata .= $key.',';
                $objectToPersist[] = $value;
                $valuePoints .= '?,';
            }
            $objdata = substr($objdata, 0, strlen($objdata) - 1);
            $valuePoints = substr($valuePoints, 0, strlen($valuePoints) - 1);

            $statement = null;

            try {
                $connection = $this->conn;
                if($connection != null) {
                    $statement = $connection->prepare('INSERT INTO '.$entity.' ('.$objdata.') VALUES ('.$valuePoints.')');
                    $statement->execute($objectToPersist);

                    $statement = $connection->prepare('SELECT * FROM '.$entity.' ORDER BY id DESC LIMIT 1');
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_CLASS, $class);
                    $lastInserted = $statement->fetch();

                    $this->message->setMessage('Criado com êxito.');
                    $this->message->setStatus(ResponseMessage::STATUS_OK);
                    $this->message->setEntity($lastInserted);
                }
                else {
                    $this->message->setMessage('Conexão inválida.');
                }

                return $this->message;
            } catch(Exception $e) {
                $this->message = new ResponseMessage();
                $this->message->setMessage('Error: '.$e->getMessage());
                if((strpos($e->getMessage(),'Duplicate')) > 0) {
                    $this->message->setMessage('Error: Já existe cadastrado.');
                }
                $this->message->setStatus(ResponseMessage::STATUS_ERROR);
                return $this->message;
            }
        }

        protected function update($object) {
            $this->message = new ResponseMessage();

            $entity = $object->entityName();
            $objvars = $object->read();
            $objdata = '';
            $objectToPersist = array();
            foreach($objvars as $key=>$value) {
                $objdata .= $key.'=?,';
                $objectToPersist[] = $value;
            }
            $objdata = substr($objdata, 0, strlen($objdata) - 1);

            $statement = null;

            try {
                $connection = $this->conn;
                if($connection != null) {
                    $sql = 'UPDATE '.$entity.' SET '.$objdata.' WHERE id = '.$object->getId();
                    $statement = $connection->prepare($sql);
                    $statement->execute($objectToPersist);

                    $this->message->setMessage('Atualizado com êxito.');
                    $this->message->setStatus(ResponseMessage::STATUS_OK);
                    $this->message->setEntity($object);
                }
                else {
                    $this->message->setMessage('Conexão inválida.');
                }

                return $this->message;
            } catch(Exception $e) {
                $this->message = new ResponseMessage();
                $this->message->setMessage('Error: '.$e->getMessage());
                if((strpos($e->getMessage(),'Duplicate')) > 0) {
                    $this->message->setMessage('Error: Já existe informação cadastrada.');
                }
                $this->message->setStatus(ResponseMessage::STATUS_ERROR);
                return $this->message;
            }
        }

        protected function delete($object) {
            $this->message = new ResponseMessage();

            $entity = $object->entityName();
            
            $statement = null;

            try {
                $connection = $this->conn;
                if($connection != null) {
                    $sql = 'DELETE FROM '.$entity.' WHERE id = '.$object->getId();
                    $statement = $connection->prepare($sql);
                    $statement->execute();

                    $this->message->setMessage('Deletado com êxito.');
                    $this->message->setStatus(ResponseMessage::STATUS_OK);
                    $this->message->setEntity($object);
                }
                else {
                    $this->message->setMessage('Conexão inválida.');
                }

                return $this->message;
            } catch(Exception $e) {
                $this->message = new ResponseMessage();
                $this->message->setMessage('Error: '.$e->getMessage());
                $this->message->setStatus(ResponseMessage::STATUS_ERROR);

                $hasStr = strpos($e->getMessage(),'Cannot delete or update a parent row');
                if($hasStr !== false) {
                    $this->message->setMessage('Error: Está em uso e não pode ser excluído.');
                }

                return $this->message;
            }
        }
    }
?>