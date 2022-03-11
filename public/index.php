<?php
    header('Content-Type: application/json');

    require_once '../vendor/autoload.php';
    session_start();
    if ($_GET['url']) {
        $url = explode('/', $_GET['url']);
        $route = $url[1];
        if ($url[0] === 'api') {
            array_shift($url);
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            $routeMiddleware = new \App\Middlewares\EnsureAuthenticate();
            if($route === 'users'){
                switch($method){
                    case 'post':
                        if($url[2] != null and $url[2] == 'drink'){
                            $couldAccess = $routeMiddleware->ensureUserIsLoggedIn();
                            if($couldAccess){
                                (new \App\Controllers\UserController())->updateUserDrinks();
                            } else {
                                http_response_code(401);
                                echo json_encode(array('status' => 'error', 'data' => 'You dont have permission to access this endpoint'), JSON_UNESCAPED_UNICODE);
                            }
                        } else {
                            (new \App\Controllers\UserController())->post();
                        }

                        break;
                    case 'put':
                        $couldAccess = $routeMiddleware->ensureUserIsLoggedIn();
                        if($couldAccess){
                            (new \App\Controllers\UserController())->update();
                        } else {
                            http_response_code(401);
                            echo json_encode(array('status' => 'error', 'data' => 'You dont have permission to access this endpoint'), JSON_UNESCAPED_UNICODE);
                        }
                        break;
                    case 'get':
                        $couldAccess = $routeMiddleware->ensureUserIsLoggedIn();
                        if($couldAccess){
                            (new \App\Controllers\UserController())->getUsers();
                        } else {
                            http_response_code(401);
                            echo json_encode(array('status' => 'error', 'data' => 'You dont have permission to access this endpoint'), JSON_UNESCAPED_UNICODE);
                        }
                        break;
                    case 'delete':
                        $couldAccess = $routeMiddleware->ensureUserIsLoggedIn();
                        if($couldAccess){
                            (new \App\Controllers\UserController())->delete();
                        } else {
                            http_response_code(401);
                            echo json_encode(array('status' => 'error', 'data' => 'You dont have permission to access this endpoint'), JSON_UNESCAPED_UNICODE);
                        }
                        break;
                }
            } else {
                (new \App\Controllers\UserController())->login();
            }
        }
    }
    