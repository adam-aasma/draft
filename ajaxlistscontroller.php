<?php

require __DIR__ . '/vendor/autoload.php';

use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\ImageService;
use Walltwisters\service\SectionService;
use Walltwisters\service\ProductService;

session_start();
if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 401 Not Authorized");
    echo json_encode(['message' => 'there is no user']);
    die();
}
$user = unserialize($_SESSION['user']);
$productService = new ProductService(RepositoryFactory::getInstance());
$sectionService = new SectionService(RepositoryFactory::getInstance());
if (isset($_GET['requestType'])) {
    try {
        header('Content-Type: ' . 'application/json');
        switch($_GET['requestType']) {
            case 'getAll':
                $response = $productService->getAllProducts();
                break;
            case 'getAllSections' :
                $response = $sectionService->getAllSections();
                break;
            case 'getAllSliders' :
                $response = [];
                break;
        }
    } catch (Exception $ex) {
        header("HTTP/1.0 500 Internal error");
        echo json_encode(['message' => $ex->getMessage()]);
    }
    
    
}
echo json_encode($response);

?>
