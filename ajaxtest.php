<?php
require __DIR__ . '/vendor/autoload.php';

use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\ImageService;
use Walltwisters\service\ProductService;

session_start();


if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 401 Not Authorized");
    echo json_encode(['message' => 'there is no user']);
    die();
}
$productService = new ProductService(RepositoryFactory::getInstance());
$user = unserialize($_SESSION['user']);
$existingProductId = null;
if (isset($_REQUEST['productId']) && $_REQUEST['productId'] != 0) {
    $existingProductId = $_REQUEST['productId'];
} else {
    $existingProductId = $productService->initializeProduct($user->id, $_REQUEST['formatId']);
}
if (isset($_POST['requestType'])) {
    try {
        header('Content-Type: ' . 'application/json');
        switch($_POST['requestType']) {
            case 'image':
                $id = $_POST['image-category-id'];
                $imageService = new ImageService(RepositoryFactory::getInstance());
                $imageId = $imageService->addProductImage($_FILES, $id, $existingProductId);

                $mime = $_FILES['images']["type"][0];
                $size = $_FILES['images']["size"][0];
                $name = $_FILES['images']["name"][0];

                $response = ['imageName' => $name, 'mime' => $mime, 'size' => $size, 'imageId' => $imageId, 'categoryId' => $id, 'productId' => $existingProductId];
                break;
            case 'deleteimage':
                $imageService = new ImageService(RepositoryFactory::getInstance());
                $imageService->deleteImage($_REQUEST['imageId']);
                $response = ['status' => 'ok'];
                break;
            case 'artist':
                $artist = $_POST['artistDesigner'];
                $artistDesignerId = (empty($_REQUEST['artistId'])) ? null : $_REQUEST['artistId'];
                $artistId = $productService->addArtistDesigner($artist, $artistDesignerId);
                $response = ['artistId' => $artistId, 'productId' => $existingProductId];
                break;
            case 'product':
                $imageIds = $_POST['imageId'];
                $formatId = $_POST['formatId'];
                $artistId = $_POST['artistId'];
                $productId = $productService->addProduct($imageIds, $formatId, $artistId, $user->id);
                $response = ['productId' => $productId];
                break;
            case 'productinfo':                $productId = ($_POST['productId']) ? $_POST['productId'] : $productId;
                $languageId = $_POST['languageId'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $tags = $_POST['tags'];
                $productService->addDescriptionToProduct($languageId, $existingProductId, $name, $description);
                $response = ['productId' => $existingProductId];
                break;
            case 'productitems':
                if( $_POST['item']) {
                    foreach($_POST['item'] as $countryId => $materials) {
                        $productService->saveProductItemsForProductAndCountry($existingProductId, $countryId, $materials);
                    }
                } else {
                    $countryId = $_POST['marketId'];
                    $productService->saveProductItemsForProductAndCountry($existingProductId, $countryId, $materials);
                }
                $response = ['productId' => $existingProductId];
                break;
            default:
                header("HTTP/1.0 400 Invalid data");
                echo json_encode(['message' => 'No such requestType (' . $_POST['requestType'] . ')']);
                die();
                break;
        }

        echo json_encode($response);
    } catch (Exception $ex) {
        header("HTTP/1.0 500 Internal error");
        echo json_encode(['message' => $ex->getMessage()]);
    }
} else if ($existingProductId > 0) {
    // Get product
    try {
        $completeProduct = $productService->getProductById($existingProductId);
        if (!empty($completeProduct)) {
            header('Content-Type: ' . 'application/json');
            $res = json_encode($completeProduct);
                echo json_encode($completeProduct);
        }
    } catch (Exception $ex) {
        header("HTTP/1.0 500 Internal error");
        echo json_encode(['message' => $ex->getMessage()]);
    }
}
