<?php

use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\ProductService;
use Walltwisters\utilities\Security;
use Walltwisters\utilities\FormUtilities;
use Walltwisters\utilities\Images;

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
        <fieldset id='productImages' class="border block">
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
   
</script>
<script src="js/general/product.js" type="text/javascript"></script>
<script src="js/page/editproduct.js" type="text/javascript"></script>
<template>
    <div id="showroom">
        <link href="css/productShowRoom.css" type="text/css" rel="stylesheet">
        <div class="showroom">
            <div class="arrowbutton">
                <button onclick="plusDivs(-1)">&#10094;</button>
                <button onclick="plusDivs(1)">&#10095;</button>
            </div>
        </div>
        <div class="product-info">
            <h1><!-- the product name --></h1>
            <div class="priceButton">
                <h2><!--product price --></h2>
                <button>confirm</button>
                <button id="editButton" onclick="goBackFromShowRoom()">edit</button>
            </div>
        <p id="productdescription">
                <!-- the product description -->
        </p>
        </div>
    </div>
</template>

<?php
require_once 'adminpagefooter.php';