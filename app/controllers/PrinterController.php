<?php

namespace Walltwisters\controllers;

use Walltwisters\lib\repository\RepositoryFactory;
use Walltwisters\lib\service\PrinterService;
use Walltwisters\lib\utilities\FormUtilities;


class PrinterController extends BaseController {
    
    protected $templateVars;
    protected $printerService;
    
     public function __construct($request, $response, $needAuthentication, $app){
        parent::__construct($request, $response, $app, $needAuthentication);
        
        $this->templateVars = [
            'title' => 'adminpanel',
            'keywordContent' => 'SEO',
            'menu' => $this->menu,
            'user' => $this->user,
            'countries' => $this->user->countries,
            'countryOptions' => (FormUtilities::getAllOptions($this->user->countries, 'country'))
        ];
        $this->printerService = new PrinterService(RepositoryFactory::getInstance());
     }
    
    public function add(){
        $this->slimApp->logger->addinfo('its working');
        $response = $this->slimApp->view->render($this->response, '/printer/edit.php', $this->templateVars);
        return $response;
    }
    public function update(){
        $parsedBody = $this->request->getParsedBody();
        $printerId = $this->getPrinterId($parsedBody);
        if(isset($parsedBody['requestType']))
            try {
                switch($parsedBody['requestType']){
                    case 'printerGeneral' :
                        $response = $this->updatePrinter($parsedBody, $printerId);
                        break;
                    case 'item' :
                        $response = $this->updateItem($parsedBody, $printerId);
                        break;
                    case 'price' :
                        //TODO
                        break;
                    default :
                        return $this->defaultResponse($parsedBody);
                }
                
                return $this->succesfullyUpdatedResponse($response);
                
            } catch (Exception $ex) {
                return $this->exceptionResponse($ex);
                 
            }
            
        
    }
    
    private function updatePrinter($parsedBody, $printerId){
        $printerService = $this->printerService;
        $obj = $printerService->updatePrinter
                (
                    $parsedBody['countryId'],
                    $printerId,
                    $parsedBody['companyName'],
                    $parsedBody['email'],
                    $parsedBody['telephoneNumber'],
                    $parsedBody['contactPerson'],
                    $this->user->id
                );
        $ok = $obj ? 'good' : 'wrong';
        return $response = ['printerId' => $printerId, 'updated' => $ok];
    }
    
    private function updateItem($parsedBody, $printerId) {
        $printerService = $this->printerService;
        $id =  $printerService->updateItem
        (
            $printerId,
            $parsedBody['size'],
            $parsedBody['technique'],
            $parsedBody['material'],
            $parsedBody['itemId']   
        );
                    
      return $response = ['printerId' => $printerId, 'itemId' => $id];
    }
    
    private function getPrinterId($parsedBody) {
        $printerService = $this->printerService;
        if (isset($parsedBody['printerId']) && $parsedBody['printerId'] != 0) {
            $printerId = $parsedBody['printerId'];
        } else {
            $printerId = $printerService->initializePrinter($parsedBody['countryId'], $this->user->id);
        }
        
        return $printerId;
    }
    
    private function defaultResponse($parsedBody) {
        return $this->response
                            ->withStatus(400)
                            ->withHeader('Content-type', 'application/json')
                            ->withJson(['message' => 'No such requestType (' . $parsedBody['requestType'] . ')']);
    }
    private function succesfullyUpdatedResponse($response) {
        return $this->response
                        ->withHeader('Content-type', 'application/json')
                        ->withJson($response);
    }
    private function exceptionResponse($ex) {
        return $this->response
                    ->withStatus(500)
                    ->withHeader('Content-type', 'application/json')
                    ->withJson(['message' => $ex->getMessage()]);
    }
}
