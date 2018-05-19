<?php

use Walltwisters\lib\repository\RepositoryFactory;
use Walltwisters\lib\service\ProductService;
use Walltwisters\lib\utilities\Security;
use Walltwisters\lib\utilities\FormUtilities;
use Walltwisters\lib\utilities\Images;

$keywordContent = '';
$css = 'css/editProduct.css';
require_once 'adminpageheaderlogic.php';
require_once 'adminpageheader.php';
$productService = new ProductService(RepositoryFactory::getInstance());
$countrylanguages = $productService->getCountryLanguages2($user->countries);
$countryItems = $productService->getCountryItems($user->countries);
$imageCategories = $productService->getImageCategoriesBy('productImageCategories');
$imageCategoryId = $imageCategories[0]->id;


$productId = 0;
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
}
?>
<div id="editProduct" class="row inline-flex vert-start" >
    <!--<input type="submit" id="submit" value="save" /> -->
    <div class="row inline-flex space-around">
        <fieldset id='addPictures' class="border block">
            <legend>product images</legend>
            <div class="pictureBar">
                <form method="POST" action="ajaxtest.php" id="picture"></form>
                <div class="trio left">
                    <input type="file" />
                </div>
                <div class="left radios" >
                    <?= FormUtilities::getAllRadioOptions($imageCategories, 'category', "category" , [$imageCategoryId]);?>
                </div>
                <div class="left center saveImage">
                    <button type="submit" id="submit" value="save" form="picture">Add</button>
                </div>
            </div>
            <div id="addedpictures">
            </div>
        </fieldset>
        <fieldset id='general' class="border">
            <legend>general</legend>
            <p class="wrapper">
                <label>format:</label>
                <select name="format">
                    <?= FormUtilities::getAllOptions($productService->getAllFormats(), 'format') ?>
                </select>
            <p/>
            <p class="wrapper">
                <label>Artist</label>
                <input type="text" name="artist"/>
            </p>
            
        </fieldset>
        <fieldset id='currently' class="border">
            <legend>Currently</legend>
            <h2>pictures</h2>
            <div id="thumbnails">
            </div>
            <h2>Markets</h2>
            <?= Walltwisters\utilities\HtmlUtilities::createSpans(
                    array_map(function($country){
                        return ['id' => $country->id, 'text' => $country->country];
                    },$user->countries), true) ?>
            <h2>languages</h2>
            <p id="languages"></p>
            <h2>items</h2>
            <button id="productPreview">preview</button>
            
        </fieldset>
    </div>
    <div class="row inline-flex space-between">
        <div id="underStore1">
            <ul id="addLanguage" class="solo right">
                    <li class="tabOptions right"><a href="#">Add Language</a></li>
            </ul>
            <fieldset id='productInfo' class="border">
                <legend>product info</legend>
         
            </fieldset>  
        </div>
        <div id="underStore2">
            <ul id="addCountry" class="solo right">
                    <li class="tabOptions right"><a href="#">Add Market</a></li>
            </ul>
            <fieldset id='itemDetails'class="border">
                <legend>Items</legend>
              
            </fieldset>   
        </div>
    </div>
</div>
<script>
    var countryLanguages = <?= json_encode($countrylanguages) ?>;
    var countryItems = <?= json_encode($countryItems) ?>;
    var productId = <?= $productId ?>; 
    var sizesObjArray = <?= $productService->sizestojson() ?>;
    var materialNamesObjArray = <?= $productService->materialstojson() ?>;
   
</script>
<script src="js/general/product.js" type="text/javascript"></script>
<script src="js/page/editproduct.js" type="text/javascript"></script>
<script src="js/page/productshowroom.js" type="text/javascript"></script>
<?php
require_once 'app/lib/utilities/HtmlTemplates/productshowroom.php'; 
require_once 'adminpagefooter.php';
?>