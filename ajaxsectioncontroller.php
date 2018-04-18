<?php
require __DIR__ . '/vendor/autoload.php';

use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\ImageService;
use Walltwisters\service\SectionService;

session_start();
if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 401 Not Authorized");
    echo json_encode(['message' => 'there is no user']);
    die();
}

$sectionService = new SectionService(RepositoryFactory::getInstance());
$user = unserialize($_SESSION['user']);
$sectionId = null;

/*
 * initalizing or setting section Id
 */
if (isset($_REQUEST['sectionId']) && (int)$_REQUEST['sectionId'] !==  0) {
    $sectionId = $_REQUEST['sectionId'];
} else {
    $sectionId = $sectionService->initializeSection($user->id);
}

/*
 * getting all products or products for an existing section
 */

if (isset($_GET['getproductrequest'])) {
    $languageId = $_GET['languageid'];
    $marketId = $_GET['marketid'];
    $sectionId = $_GET['sectionId'];
    $response = $sectionService->getProductsForSection($marketId, $languageId, $sectionId);
}

/*
 * all save to DB cases
 */
if (isset($_POST['requestType'])) {
    try {
        header('Content-Type: ' . 'application/json');
        switch($_POST['requestType']) {
            case 'image':
                $id = $_POST['image-category-id'];
                $imageService = new ImageService(RepositoryFactory::getInstance());
                $datas = $_FILES;
                $imageId = $imageService->addImage($datas, $id, $sectionId, 'section');

                $mime = $datas['images']["type"][0];
                $size = $datas['images']["size"][0];
                $name = $datas['images']["name"][0];

                $response = ['imageName' => $name, 'mime' => $mime, 'size' => $size, 'imageId' => $imageId, 'categoryId' => $id, 'sectionId' => $sectionId];
                break;
            case 'deleteimage' :
                $imageService = new ImageService(RepositoryFactory::getInstance());
                $imageId = $_REQUEST['imageId'];
                $imageService->deleteImage($imageId);
                $response = ['status' => 'ok'];
                break;
            case 'sectioncopy' :
                $title = $_REQUEST['titel'];
                $languageId = $_REQUEST['languageId'];
                $saleslineHeader = $_REQUEST['sline'];
                $saleslineParagraph = $_REQUEST['sline2'];
                $description = $_REQUEST['description'];
                $sectionId = $sectionService->updateSectionCopy($sectionId, $languageId, $title, $saleslineHeader, $saleslineParagraph, $description);
                $response = ['sectionId' => $sectionId];
                break;
            case 'productsformarket' :
                $marketId = $_REQUEST['marketId'];
                $languageId = $_REQUEST['languageId'];
                $productIds = explode(',', $_REQUEST['productIds']);
                $status = $sectionService->updateProductIdsForMarket($sectionId, 
                                                                    $marketId,  
                                                                    $languageId,
                                                                    $productIds);
                $response = ['sectionId' => $sectionId];
        }
    } catch (Exception $ex) {
        header("HTTP/1.0 500 Internal error");
        echo json_encode(['message' => $ex->getMessage()]);
    }
    
    
}
echo json_encode($response);

?>