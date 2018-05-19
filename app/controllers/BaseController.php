<?php
namespace Walltwisters\controllers;

use Walltwisters\lib\exceptions\NotAuthenticatedException;

class BaseController {
    protected $slimApp;
    protected $request;
    protected $response;
    protected $menu;
    protected $settings;
    protected $user;
    
    public function __construct($request, $response, $app, $needAuthentication = true) {
        session_start();
        if ($needAuthentication && !isset($_SESSION['user'])) {
            throw new NotAuthenticatedException('You are not authenticated');
        }
        $this->user = unserialize($_SESSION['user']);
        $this->slimApp = $app;
        $this->request = $request;
        $this->response = $response;
        $this->settings = $GLOBALS['config']['settings'];
        $this->menu = array("dashboard" => "#",
            "content" => "/content",
            "printer" => "/printer/add",
            "view customer" => "#",
            "view orders" => "#",
            "view payments" => "#",
            "users" => "/adduser.php",
            "logout" => "/user/logout");        
    }
    
    protected function jsonResponse($response){
        return $this->response
                ->withHeader('Content-type', 'application/json')
                ->withJson($response);
    }
    
    protected function exceptionResponse($ex){
        return $this->response
                    ->withStatus(500)
                    ->withHeader('Content-type', 'application/json')
                    ->withJson(['message' => $ex->getMessage()]);
    }
    
    protected function renderTemplate($path, $data = []) {
        ob_start();
        $data['user'] = $this->user;
        $data['menu'] = $this->menu;
        include_once $path;
        $contents = ob_get_contents();
        ob_end_clean();
        return $this->response->getBody()->write($contents);
    }
    
    protected function redirect($url) {
        return $this->response->withHeader('Location', $url);
    }
}
