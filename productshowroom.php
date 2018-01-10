<?php
use Walltwisters\data\RepositoryFactory;

require_once 'service/ProductService.php';
require_once 'data/RepositoryFactory.php';
require_once 'adminpageheader.php';

$productService = new ProductService(RepositoryFactory::getInstance());
$productid = $_GET['productid'];
$product = $productservice->getCompleteProductBy($productid);
$imagesHtml = '';
$description = '';
$name = '';
if (!empty($product)) {
    $name = $product->name;
    foreach ($product->imageids as $imageId) {
        $imagesHtml .= "<img class='slider' src='getimage.php?id=$imageId'>\n";
    }
    if (count($product->descriptions)) {
        $description = $product->descriptions[0];
    }
}

?>
<div class="desktop">
            <div class="slideshow showroom"><?=$imagesHtml?>
                <div class="arrowbutton">
                <button onclick="plusDivs(-1)">&#10094;</button>
                <button onclick="plusDivs(1)">&#10095;</button>
                </div>
            </div>
            <div class="product-info">
                <h1><?= $name ?></h1>
                <div class="priceButton">
                    <h2>35€</h2>
                    <button>confirm</button>
                    <button >edit</button>
                </div>
            <p>
                <?=$description?>
            </p>
            </div>
            
</div>
<?php
$script = 'js/showroom.js';
require_once 'adminpagefooter.php';
?>