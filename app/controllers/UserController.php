<?php

namespace Walltwisters\controllers;

use Walltwisters\lib\repository\UserRepository;

class UserController extends BaseController {

    public function __construct($request, $response, $needAuthentication, $app) {
        parent::__construct($request, $response, $app, $needAuthentication);
    }
    
    public function getLogin() {
        $error = '';
        $this->slimApp->logger->addinfo('its working');
        $response = $this->slimApp->view->render($this->response, '/user/login.php', ['error' => $error]);
        return $response;
    }
    
    public function doLogin() {
        $params = $this->request->getParsedBody();
        try {
            $userName = $params["username"];
            $password = $params["password"];
            $repo = new UserRepository();
            $user = $repo->LoginUser($userName,$password);
            if ($user) {
                session_set_cookie_params(600);
                session_start();
                $_SESSION['user'] = serialize($user);
                $response = $this->slimApp->view->render($this->response, 'base/adminpanel.php', [
                                                                                                    'title' => 'adminpanel',
                                                                                                    'keywordContent' => 'SEO',
                                                                                                    'menu' => $this->menu,
                                                                                                    'user' => $user
                                                                                                  ]);
                return $response;
              
            } else {
                session_start();
                session_unset();
                session_destroy();
                $response = $this->slimApp->view->render($this->response, '/user/login.php', ['error' => 'wrong username or password']);
                return $response;
               
            }    
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $response = $this->slimApp->view->render($this->response, '/user/login.php', ['error' => $error]);
            return $response;
        }
                
    }
    
    public function doLogout() {
        session_start();
        session_unset();
        session_destroy();
        $response = $this->slimApp->view->render($this->response, '/user/login.php');
        return $response;
        //return $this->redirect('/user/login');
    }
}
