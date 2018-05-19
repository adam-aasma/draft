<?php
namespace Walltwisters\controllers;

use Walltwisters\lib\repository\ImageRepository;

class ImageController extends BaseController {
    public function __construct($request, $response, $app, $needAuthentication = true) {
        parent::__construct($request, $response, $app, $needAuthentication);
    }
    
    public function get() {
        $params = $this->request->getQueryParams();
        $id = $params['id'];
        $repo = new ImageRepository();
        $image = $repo->getImage($id);

        return $this->response
                ->withHeader('Content-Type', $image['mime'])
                ->getBody()->write($image['data']);
    }
}
