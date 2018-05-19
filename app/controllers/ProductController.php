<?php
namespace Walltwisters\controllers;

use Walltwisters\lib\repository\RepositoryFactory;
use Walltwisters\lib\service\ProductService;
use Walltwisters\lib\service\ImageService;

class ProductController extends BaseController {
    protected $productService;
    protected $countrylanguages;
    protected $countryItems;
    protected $imageCategories;
    protected $viewData;
    
    public function __construct($request, $response, $needAuthentication, $app) {
        parent::__construct($request, $response, $app, $needAuthentication);
        
        $this->productService = new ProductService(RepositoryFactory::getInstance());
        $this->countrylanguages = $this->productService->getCountryLanguages2($this->user->countries);
        $this->countryItems = $this->productService->getCountryItems($this->user->countries);
        $this->imageCategories = $this->productService->getImageCategoriesBy('productImageCategories');
        
        $this->viewData =
                [
                    'title' => 'adminpanel',
                    'keywordContent' => 'SEO',
                    'menu' => $this->menu,
                    'user' => $this->user,
                    'imageCategories' => $this->imageCategories,
                    'imageCategoryId' => $this->imageCategories[0]->id,
                    'productService' => $this->productService,
                    'countries' => $this->user->countries,
                    'countrylanguages' => $this->countrylanguages,
                    'countryItems' => $this->countryItems,
                    'productId' => 0
                ];

    }
    
    public function index() {
        $this->slimApp->logger->addinfo('its working');
        $response = $this->slimApp->view->render($this->response, '/product/edit.php', $this->viewData);
                
        return $response;
      
    }
    
    public function editProduct() {
        $params = $this->request->getQueryParams();
        $id = $params['id'];
        $this->viewData['productId'] = (int)$id;
        return $this->slimApp->view->render($this->response, '/product/edit.php', $this->viewData);
    }
    
    public function load() {
        $params = $this->request->getQueryParams();
        $completeProduct = $this->productService->getProductById($params['productId']);
        return $this->jsonResponse($completeProduct);
    }
    
    public function update() {
        $productService = $this->productService;
        $existingProductId = null;
        $parsedBody = $this->request->getParsedBody();
        if (isset($parsedBody['productId']) && $parsedBody['productId'] != 0) {
            $existingProductId = $parsedBody['productId'];
        } else {
            $existingProductId = $productService->initializeProduct($this->user->id, $parsedBody['formatId']);
        }
        if (isset($parsedBody['requestType'])) {
            try {
                switch($parsedBody['requestType']) {
                    case 'image':
                        $id = $parsedBody['image-category-id'];
                        $imageService = new ImageService(RepositoryFactory::getInstance());
                        $imageId = $imageService->addImage($this->request->getUploadedFiles(), $id, $existingProductId);

                        $mime = $_FILES['images']["type"][0];
                        $size = $_FILES['images']["size"][0];
                        $name = $_FILES['images']["name"][0];

                        $response = ['imageName' => $name, 'mime' => $mime, 'size' => $size, 'imageId' => $imageId, 'categoryId' => $id, 'productId' => $existingProductId];
                        break;
                    case 'deleteimage':
                        $imageService = new ImageService(RepositoryFactory::getInstance());
                        $ok = $imageService->deleteImage($parsedBody['imageId']);
                        $response = $ok ? ['imageId' => $parsedBody['imageId']] : 'somethings wrong';
                        break;
                    case 'artist':
                        $artist = $parsedBody['artistDesigner'];
                        $artistDesignerId = (empty($parsedBody['artistId'])) ? null : $parsedBody['artistId'];
                        $artistId = $productService->addArtistDesigner($artist, $artistDesignerId);
                        $response = ['artistId' => $artistId, 'productId' => $existingProductId];
                        break;
                    case 'product':
                        $imageIds = $parsedBody['imageId'];
                        $formatId = $parsedBody['formatId'];
                        $artistId = $parsedBody['artistId'];
                        $productId = $productService->addProduct($imageIds, $formatId, $artistId, $user->id);
                        $response = ['productId' => $productId];
                        break;
                    case 'productinfo':                
                        $languageId = $parsedBody['languageId'];
                        $name = $parsedBody['name'];
                        $description = $parsedBody['description'];
                        $tags = $parsedBody['tags'];    // TODO
                        $productService->addDescriptionToProduct($languageId, $existingProductId, $name, $description);
                        $response = ['productId' => $existingProductId];
                        break;
                    case 'productitems':
                        if ($parsedBody['item']) {
                            foreach($parsedBody['item'] as $countryId => $materials) {
                                $productService->saveProductItemsForProductAndCountry($existingProductId, $countryId, $materials);
                            }
                        } else {
                            $countryId = $parsedBody['marketId'];
                            $productService->saveProductItemsForProductAndCountry($existingProductId, $countryId, $materials);
                        }
                        $response = ['productId' => $existingProductId];
                        break;
                    default:
                        return $this->response
                            ->withStatus(400)
                            ->withHeader('Content-type', 'application/json')
                            ->withJson(['message' => 'No such requestType (' . $parsedBody['requestType'] . ')']);
                }

                return $this->response
                        ->withHeader('Content-type', 'application/json')
                        ->withJson($response);

            } catch (Exception $ex) {
                return $this->response
                    ->withStatus(500)
                    ->withHeader('Content-type', 'application/json')
                    ->withJson(['message' => $ex->getMessage()]);
            }
        }
    }
}
