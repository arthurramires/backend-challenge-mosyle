<?php
    namespace App\Repositories;

    use App\Database\Database;
    use App\Models\User;

    class UserRepository {
        protected $connection;
        protected $user;

        public function __construct(User $user)
        {
            $this->user = $user;
            $databaseObject = new Database();
            $this->connection = $databaseObject->dbConnection();
        }

        public function select() {
            $id  = $this->user->getId();
            $sql = 'SELECT * FROM user WHERE id = :id';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("User not found!");
            }
        }

        public function selectAll() {

            $sql = 'SELECT * FROM user ';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Users not found!");
            }
        }

        public function insert($data)
        {
            $sql = 'INSERT INTO user (email, password, name) VALUES (:em, :pa, :na)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':em', $data['email']);
            $stmt->bindValue(':pa', $data['password']);
            $stmt->bindValue(':na', $data['name']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'User inserted!';
            } else {
                throw new \Exception("Error on insert user!");
            }
        }
    }
