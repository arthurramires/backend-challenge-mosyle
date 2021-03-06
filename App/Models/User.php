<?php
    namespace App\Models;

    class User
    {
        protected $id;
        protected $name;
        protected $email;
        protected $password;
        protected $drinks;
        protected $token;

        public function getToken(){
            return $this->token;
        }

        public function setToken(string $token){
            $this->token = $token;
        }

        public function getId(){
            return $this->id;
        }

        public function setId(int $id){
            $this->id = $id;
        }

        public function getName(){
            return $this->name;
        }

        public function setName(string $name){
            $this->name = $name;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setEmail(string $email){
            $this->email = $email;
        }

        public function getPassword(){
            return $this->password;
        }

        public function setPassword(string $password){
            $this->password = $password;
        }

        public function getDrinks(){
            return $this->drinks;
        }

        public function setDrinks(int $drinks){
            $this->drinks = $drinks;
        }
    }