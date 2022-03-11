<?php
    namespace App\Services;

    use App\Models\User;
    use App\Repositories\UserRepository;

    class UserService
    {
        protected $userRepository;
        protected $user;

        public function __construct()
        {
            $this->user = new User();
            $this->userRepository = new UserRepository($this->user);
        }

        public function setUserData($data, $id = null)
        {
            if($data->name != null){
                $this->user->setName($data->name);
            }

            if($data->drink != null){
                $this->user->setDrinks($data->drink);
            }

            if($id != null){
                $this->user->setId($id);
            }

            if($data->email != null){
                $this->user->setEmail($data->email);
            }

            if($data->password != null){
                $this->user->setPassword($data->password);
            }

        }

        public function get($id)
        {
            $this->user->setId($id);
            return $this->userRepository->select();
        }

        public function getUserRankPerDay()
        {
            return $this->userRepository->getUserRankPerDay();
        }

        public function getUserHistory($id)
        {
            $this->user->setId($id);
            return $this->userRepository->getUserDrinkHistory();
        }

        public function getAll()
        {
            return $this->userRepository->selectAll();
        }

        public function createUser($data)
        {
            try{
                $this->setUserData($data);
                return $this->userRepository->insert();
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        public function login($data)
        {
            try{
                $this->setUserData($data);
                return $this->userRepository->login();
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        public function update($data, $id)
        {
            try{
                $this->setUserData($data, $id);
                return $this->userRepository->update();
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        public function updateUserDrinks($data, $id)
        {
            try{
                $this->setUserData($data, $id);
                return $this->userRepository->updateUserDrinks();
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        public function delete($id)
        {
            try{
                $this->user->setId($id);
                return $this->userRepository->delete();
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
    }