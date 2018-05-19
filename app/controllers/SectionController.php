<?php
namespace Walltwisters\controllers;

use Walltwisters\lib\repository\RepositoryFactory;
use Walltwisters\lib\service\SectionService;
use Walltwisters\lib\service\ImageService;

class SectionController extends BaseController {
    private $sectionService;
    private $imageService;
    

    public function __construct($request, $response, $needAuthentication, $app) {
        parent::__construct($request, $response, $app, $needAuthentication);
        $this->sectionService =  new SectionService(RepositoryFactory::getInstance());
        $this->imageService = new ImageService(RepositoryFactory::getInstance());
        $imageCategories = $this->sectionService->getImageCategoriesBy('sectionImageCategories');
        $countryLanguages = $this->sectionService->getCountryLanguages2($this->user->countries);
        $json_imageCategories = [];
        foreach($imageCategories as $category){
            $json_imageCategories[$category->id] = $category->category;
        }

        $this->viewData =
                [
                    'title' => 'adminpanel',
                    'keywordContent' => 'SEO',
                    'menu' => $this->menu,
                    'user' => $this->user,
                    'imageCategories' => $imageCategories,
                    'json_imageCategories' => $json_imageCategories,
                    'countryLanguages' =>  $countryLanguages
                ];
    }
       
    public function index() {
        $response = $this->slimApp->view->render($this->response, 'section/section.php', $this->viewData);
        return $response;
    }
    
    public function update() {
        $parsedBody = $this->request->getParsedBody();
        $sectionId = $this->getSectionId($parsedBody);
        $load = $this->load($parsedBody);
        if($load){
           return; //SEND BACK LOADED SECTION
        }
        if (isset($parsedBody['requestType'])) {
            try {
                switch($parsedBody['requestType']) {
                    case 'image':
                        $categoryId = $parsedBody['image-category-id'];
                        $imageService = new ImageService(RepositoryFactory::getInstance());
                        $datas = $_FILES;
                        $imageId = $imageService->addImage
                                (
                                $this->request->getUploadedFiles(),
                                $categoryId,
                                $sectionId, 
                                'section'
                                );
                        $mime = $datas['images']["type"][0];
                        $size = $datas['images']["size"][0];
                        $name = $datas['images']["name"][0];

                        $response = ['imageName' => $name, 'mime' => $mime, 'size' => $size, 'imageId' => $imageId, 'categoryId' => $categoryId, 'sectionId' => $sectionId];
                        break;
                    case 'deleteimage' :
                        $imageService = new ImageService(RepositoryFactory::getInstance());
                        $imageId = $parsedBody['imageId'];
                        $res = $imageService->deleteImage($imageId);
                        if($res > 0){
                        $response = ['status' => 'ok'];
                        }
                        break;
                    case 'sectioncopy' :
                        $title = $parsedBody['titel'];
                        $languageId = $parsedBody['languageId'];
                        $saleslineHeader = $parsedBody['sline'];
                        $saleslineParagraph = $parsedBody['sline2'];
                        $description = $parsedBody['description'];
                        $sectionId = $this->sectionService->updateSectionCopy($sectionId, $languageId, $title, $saleslineHeader, $saleslineParagraph, $description);
                        $response = ['sectionId' => $sectionId];
                        break;
                    case 'productsformarket' :
                        $marketId = $parsedBody['marketId'];
                        $languageId = $parsedBody['languageId'];
                        $productIds = explode(',', $parsedBody['productIds']);
                        $status = $this->sectionService->updateProductIdsForMarket($sectionId, 
                                                                            $marketId,  
                                                                            $languageId,
                                                                            $productIds);
                        $response = ['sectionId' => $sectionId];
                        break;
                    case 'deleteSection' :
                        $sectionId = $parsedBody['sectionId'];
                        $res = $this->sectionService->deleteSection($sectionId);
                        if($res > 0){
                            $response = ['status' => 'ok'];
                        }
                        break;
                    case 'loadSection' :
                        $sectionId = $parsedBody['sectionId'];
                        $section = $this->sectionService->getCompleteSectionForId($sectionId);

                        $response = $section;
                        break;
                    default:
                        return $this->response
                            ->withStatus(400)
                            ->withHeader('Content-type', 'application/json')
                            ->withJson(['message' => 'No such requestType (' . $parsedBody['requestType'] . ')']);
                }
                return $this->jsonResponse($response);
            } catch (Exception $ex) {
                return $this->exceptionResponse($ex);
            }
        }
    }
    
    
    
    public function getProducts(){
        $params = $this->request->getQueryParams();
        $languageId = $params['languageId'];
        $marketId = $params['marketId'];
        $sectionId = $params['sectionId'];
        return $this->jsonResponse($this->sectionService->getProductsForSection($marketId, $languageId, $sectionId));
    }
    
    private function getSectionId($parsedBody){
        if (isset($parsedBody['sectionId']) && (int)$parsedBody['sectionId'] !==  0) {
        $sectionId = $parsedBody['sectionId'];
        } else {
        $sectionId = $this->sectionService->initializeSection($this->user->id);
        }
        
        return $sectionId;
    }
    
    private function load($parsedBody){
        if (isset($parsedBody['getproductrequest']) && (!isset($parsedBody['requestType']) || $parsedBody['requestType'] !== 'loadSection')) {
            $languageId = $parsedBody['languageid'];
            $marketId = $parsedBody['marketid'];
            $sectionId = $parsedBody['sectionId'];
            return $this->sectionService->getProductsForSection($marketId, $languageId, $sectionId);
        }
        return false;
    }
}
