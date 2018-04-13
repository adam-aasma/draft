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
if (isset($_GET['languageid']) && isset($_GET['marketid'])) {
    $json = true;
    $response = $sectionService->getAvailableProductsforSection($_GET['marketid'], $_GET['languageid'], $json);
}
if (isset($_REQUEST['sectionId']) && $_REQUEST['sectionId'] != 0) {
    $sectionId = $_REQUEST['sectionId'];
} else {
    $sectionId = $sectionService->initializeSection($user->id);
}
if (isset($_POST['requestType'])) {
    try {
        header('Content-Type: ' . 'application/json');
        switch($_POST['requestType']) {
            case 'image':
                $id = $_POST['image-category-id'];
                $imageService = new ImageService(RepositoryFactory::getInstance());
                $imageId = $imageService->addProductImage($_FILES, $id, $sectionId, 'section');

                $mime = $_FILES['images']["type"][0];
                $size = $_FILES['images']["size"][0];
                $name = $_FILES['images']["name"][0];

                $response = ['imageName' => $name, 'mime' => $mime, 'size' => $size, 'imageId' => $imageId, 'categoryId' => $id, 'sectionId' => $sectionId];
                break;
            case 'deleteimage' :
                $imageService = new ImageService(RepositoryFactory::getInstance());
                $imageService->deleteImage($_REQUEST['imageId']);
                $response = ['status' => 'ok'];
                break;
        }
    } catch (Exception $ex) {
        header("HTTP/1.0 500 Internal error");
        echo json_encode(['message' => $ex->getMessage()]);
    }
    
    
}
echo json_encode($response);

?>