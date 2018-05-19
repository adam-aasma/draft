<?php

namespace Walltwisters\controllers;

class AdminController extends BaseController {
    
    public function __construct($request, $response, $needsAuthentication) {
        parent::__construct($request, $response, $needsAuthentication);
    }
    
    public function panel() {
        return $this->renderTemplate('../views/base/adminpanel.php',['error' => '']);
    }
}
