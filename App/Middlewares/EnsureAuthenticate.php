<?php

    namespace App\Middlewares;

    class EnsureAuthenticate {
        public function ensureUserIsLoggedIn(){
            $userLoggedIn = true;
            if(!($_SESSION['loggedIn'] == 'true' and $_SESSION['token'] != null)){
                $userLoggedIn = false;
            }

            if($userLoggedIn){
                header('Authorization: Bearer ' . $_SESSION['token']);
                //var_dump(headers_list());
            }

            return $userLoggedIn;
        }
    }
