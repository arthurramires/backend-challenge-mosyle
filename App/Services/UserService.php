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

        public function get($id = null) 
        {
            if ($id) {
                $this->user->setId($id);
                return $this->userRepository->select();
            } else {
                return $this->userRepository->selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            return $this->userRepository->insert($data);
        }

        public function update() 
        {
            
        }

        public function delete() 
        {
            
        }
    }