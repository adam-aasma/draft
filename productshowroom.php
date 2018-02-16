<?php
use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\ProductService;


$keywordContent = '';
require_once 'adminpageheaderlogic.php';

$productService = new ProductService(RepositoryFactory::getInstance());
$productid = $_GET['id'];
$product = $productService->getShowRoomProductBy($productid);
$imagesHtml = '';
$description = '';
$name = '';

if (!empty($product)) {
    foreach ($product->imageids as $imageId) {
        $imagesHtml .= "<img class='slider' src='getimage.php?id=$imageId'>\n";
    }
    $descriptions = [];
    $names = [];
    $markets = [];
    $languages = [];
    foreach($product->productInfo as $productInfo){
        $names[] = $productInfo->productName;
        $descriptions[] = $productInfo->description;
        $markets[] = $productInfo->country;
        $languages[] = $productInfo->language;
        
    }
}


require_once 'adminpageheader.php';
?>
<div class="desktop">
            <div class="slideshow showroom"><?=$imagesHtml?>
                <div class="arrowbutton">
                <button onclick="plusDivs(-1)">&#10094;</button>
                <button onclick="plusDivs(1)">&#10095;</button>
                </div>
            </div>
            <div class="product-info">
                <h1><?= $names[0] ?></h1>
                <div class="priceButton">
                    <h2>35€</h2>
                    <button>confirm</button>
                    <button >edit</button>
                </div>
            <p>
                <?=$descriptions[0]?>
            </p>
            </div>
            
</div>
<?php
$script = 'js/showroom.js';
require_once 'adminpagefooter.php';
?>