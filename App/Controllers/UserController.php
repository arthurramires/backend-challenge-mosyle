<?php
    namespace App\Controllers;

    use App\Services\UserService;

    class UserController{
        protected $userService;

        public function __construct()
        {
            $this->userService = new UserService();
        }

        public function getUsers()
        {
            try {
                $request = explode('/', $_GET['url']);
                if($request[2] === 'drinks'){
                    $users = $this->userService->getUserRankPerDay();
                } else if($request[2] === null){
                    $users = $this->userService->getAll();
                } else if($request[2] != null and $request[3] == 'drinks'){
                    $users = $this->userService->getUserHistory($request[2]);
                } else {
                    $users = $this->userService->get($request[2]);
                }
                http_response_code(200);
                echo json_encode(array('status' => 'sucess', 'data' => $users));
                exit;
            } catch (\Exception $e) {
                http_response_code($e->getCode());
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }

        }

        public function post()
        {
            try {
                $body = @file_get_contents('php://input');
                $createdUser =  $this->userService->createUser(json_decode($body));
                echo json_encode(array('status' => 'sucess', 'data' => $createdUser));
                exit;
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        public function login()
        {
            try {
                $body = @file_get_contents('php://input');
                $createdUser =  $this->userService->login(json_decode($body));
                echo json_encode(array('status' => 'sucess', 'data' => $createdUser));
                exit;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        public function update()
        {
            try {
                $request = explode('/', $_GET['url']);
                $userId = $request[2];
                $body = @file_get_contents('php://input');
                $updatedUser =  $this->userService->update(json_decode($body), $userId);
                echo json_encode(array('status' => 'sucess', 'data' => $updatedUser));
                exit;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        public function updateUserDrinks()
        {
            try {
                $request = explode('/', $_GET['url']);
                $userId = $request[2];
                $body = @file_get_contents('php://input');
                $updatedUser =  $this->userService->updateUserDrinks(json_decode($body), $userId);
                echo json_encode(array('status' => 'sucess', 'data' => $updatedUser));
                exit;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        public function delete()
        {
            try {
                $request = explode('/', $_GET['url']);
                $userId = $request[2];
                $this->userService->delete($userId);
                echo json_encode(array('status' => 'sucess', 'data' => 'User deleted!'));
                exit;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
