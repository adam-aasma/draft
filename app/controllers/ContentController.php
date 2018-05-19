<?php
namespace Walltwisters\controllers;

use Walltwisters\lib\repository\RepositoryFactory;
use Walltwisters\lib\service\SectionService;
use Walltwisters\lib\service\ProductService;

class ContentController extends BaseController {
    
    protected $viewData;
    
    public function __construct($request, $response, $needsAuthentication, $app) {
        parent::__construct($request, $response, $app, $needsAuthentication);
        
        $this->viewData = [
                            'title' => 'adminpanel',
                            'keywordContent' => 'SEO',
                            'menu' => $this->menu,
                             'user' => $this->user,
                           ];
    }
    
    public function index() {
        $this->slimApp->logger->addinfo('its working');
        $response = $this->slimApp->view->render($this->response, '/content/lists.php', $this->viewData);
        return $response;
    }
    
    public function product() {
        $this->slimApp->logger->addinfo('its working');
        $productService = new ProductService(RepositoryFactory::getInstance());
        $response= $productService->getAllProducts();
        return $this->response
                        ->withHeader('Content-type', 'application/json')
                        ->withJson($response);
    }
    
    
}
