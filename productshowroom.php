<?php
require_once 'checkauth.php';
require_once 'views/admintemplate.php';
$productrep = new ProductRepository;
$productid = $_GET['productid'];
$product = $productrep->getCompleteProduct($productid);
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
$homepage = new adminTemplate();
$titel = $homepage -> title = 'wally';
$content = $homepage -> content = '<div class="wrapper font">
            <div class="slideshow showroom">' . $imagesHtml . '
                <div class="arrowbutton">
                <button onclick="plusDivs(-1)">&#10094;</button>
                <button onclick="plusDivs(1)">&#10095;</button>
                </div>
            </div>
            <div class="product-info">
                <h1>' . $name . '</h1>
                <div class="priceButton">
                    <h2>35€</h2>
                    <button>confirm</button>
                    <button >edit</button>
                </div>
            <p>
                ' . $description . '
            </p>
            </div>
            
        </div>';

$homepage -> Display();

?>