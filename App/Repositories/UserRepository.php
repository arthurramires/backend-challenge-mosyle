<?php
    namespace App\Repositories;

    use App\Database\Database;
    use App\Models\User;
    use App\Utils\ArrayUtils;
    use App\Utils\AuthToken;

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
            $sql = 'SELECT
                        user.id AS userid,
                        name,
                        email,
                        SUM(user_drink.drinks) AS drinkCounter
                    FROM user 
                    INNER JOIN user_drink ON user.id = user_drink.user_id
                    WHERE user.id = :id
                    GROUP BY user.id';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("User not found!");
            }
        }

        public function getUserDrinkHistory() {
            $id  = $this->user->getId();
            $sql = 'SELECT
                        user.id AS userid,
                        name,
                        email,
                        SUM(user_drink.drinks) AS drinkCounter,
                        DATE_FORMAT(user_drink.created_at, "%Y-%m-%d") AS date
                    FROM user 
                    INNER JOIN user_drink ON user.id = user_drink.user_id
                    WHERE user.id = :id
                    GROUP BY DATE_FORMAT(user_drink.created_at, "%Y-%m-%d")';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $userHistory = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return ArrayUtils::groupUserHistoryPerDay($userHistory);
            } else {
                throw new \Exception("User not found!");
            }
        }

        public function getUserRankPerDay() {
            $sql = 'SELECT
                        user.id AS userid,
                        name,
                        email,
                        SUM(user_drink.drinks) AS drinkCounter,
                        DATE_FORMAT(user_drink.created_at, "%Y-%m-%d") AS date
                    FROM user
                    INNER JOIN user_drink ON user.id = user_drink.user_id
                    GROUP BY DATE_FORMAT(user_drink.created_at, "%Y-%m-%d"), user.id';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $userHistory = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return ArrayUtils::groupUserHistoryPerDay($userHistory);
            }
        }

        public function getUserByEmail() {
            $email  = $this->user->getEmail();
            $sql = 'SELECT * FROM user WHERE email = :email';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                if($user){
                    return $user;
                }
            } else {
                return false;
            }
        }

        public function getCounterUserDrinks() {
            $email  = $this->user->getEmail();
            $sql = 'SELECT SUM(user_drink.drinks) as drinks FROM user INNER JOIN user_drink ON user.id = user_drink.user_id WHERE email = :email GROUP BY user.id';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $drinks = $stmt->fetch(\PDO::FETCH_ASSOC);
                return intval($drinks['drinks']);
            }
        }

        public function login() {
            $email  = $this->user->getEmail();
            $sql = 'SELECT * FROM user WHERE email = :email';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                if(!$user){
                    throw new \Exception("User not found with this email!");
                }

                $userPassword = crypt($this->user->getPassword(), '$2a$07$usesomesillystringforsalt$');

                if(!hash_equals($user['password'], $userPassword)){
                    throw new \Exception("Wrong Password!");
                }

                $this->user->setId($user['id']);
                $this->user->setEmail($user['email']);
                $this->user->setToken(AuthToken::generateToken($this->user->getId()));

                $this->setTokenSession();
            }

            return [
                'userid' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'drinkCounter' => $this->getCounterUserDrinks()
            ];
        }

        private function setTokenSession(){

            $_SESSION['loggedIn'] = "true";
            $_SESSION['username'] = $this->user->getName();
            $_SESSION['email'] = $this->user->getEmail();
            $_SESSION['token'] = $this->user->getToken();
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

        public function insert()
        {
            $existingUser = $this->getUserByEmail();
            if($existingUser){
                throw new \Exception("User already exists!");
            }

            $sql = 'INSERT INTO user (email, password, name) VALUES (:email, :password, :name)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $this->user->getEmail());
            $stmt->bindValue(':password',  crypt($this->user->getPassword(), '$2a$07$usesomesillystringforsalt$'));
            $stmt->bindValue(':name',  $this->user->getName());
            $stmt->execute();
            $this->user->setId($this->connection->lastInsertId());
            if ($stmt->rowCount() > 0) {
                $this->insertDrink();
                return 'User inserted!';
            } else {
                throw new \Exception("Error on insert user!");
            }
        }

        public function update()
        {
            $existingUser = $this->select();

            if(!$existingUser){
                throw new \Exception("User dont exists!");
            }

            $sql = 'UPDATE user SET email = :email, name = :name, password = :password WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $this->user->getEmail());
            $stmt->bindValue(':password',  crypt($this->user->getPassword(), '$2a$07$usesomesillystringforsalt$'));
            $stmt->bindValue(':name',  $this->user->getName());
            $stmt->bindValue(':id',  $this->user->getId());
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'User updated!';
            } else {
                throw new \Exception("Error on update user!");
            }
        }

        public function updateUserDrinks()
        {
            $existingUser = $this->select();

            if(!$existingUser){
                throw new \Exception("User dont exists!");
            }

            $sql = 'INSERT INTO user_drink (drinks, user_id) VALUES (:drinks, :user_id)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':drinks', $this->user->getDrinks());
            $stmt->bindValue(':user_id',  $this->user->getId());
            $stmt->execute();

            if (!$stmt->rowCount() > 0){
                throw new \Exception("Error on insert drinks!");
            }

            if ($stmt->rowCount() > 0) {
                 $existingUser = $this->select();
            }

            return $existingUser;
        }

        public function insertDrink()
        {
            $sql = 'INSERT INTO user_drink (drinks, user_id) VALUES (:drinks, :user_id)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':drinks', 0);
            $stmt->bindValue(':user_id',  $this->user->getId());
            $stmt->execute();

            if (!$stmt->rowCount() > 0){
                throw new \Exception("Error on insert drinks!");
            }
        }

        public function delete()
        {
            $existingUser = $this->select();

            if(!$existingUser){
                throw new \Exception("User dont exists!");
            }

            $sql = 'DELETE FROM user WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id',  $this->user->getId());
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'User deleted!';
            } else {
                throw new \Exception("Error on delete user!");
            }
        }
    }
