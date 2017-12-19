<?php

require_once 'checkauth.php';
require_once 'views/admintemplate.php';
require_once 'library/security.php';
require_once 'data/productRepository.php';
require_once 'data/ImageRepository.php';
require_once 'library/FormUtilities.php';

$productrep = new productRepository();
if (isset($_POST['submit'])){
    try {
    $imagerepo = new ImageRepository();
    $imagedata = FormUtilities::getImagedata($_FILES);
    $titel = $_POST['name'];
    $salesmessage = $_POST['text'];
    $productid = $_POST['product'];
    $languageid = 1;
    $countryid = 1;
    $user = unserialize($_SESSION['user']);
    $userid = $user->id;
    $image = new Image($imagedata[0]['filepath'], $imagedata[0]['size'], $imagedata[0]['mime'], $titel, 'slider');
    $imageId = $imagerepo->addImage($image);
    $slider = new Slider($imageId, $productid, $languageid, $countryid, $salesmessage, $titel, $userid);
    $sliderId = $productrep->addSlider($slider);
    header("location: showslides.php?slider=$sliderId");
    }
    catch (Exception $e) {
        $message = $e->getMessage();
    }
}
$productlist = $productrep->getProductList();
$productOptions ='';
foreach ($productlist as $product){
    $val = $product['id'];
    $name = $product['name'];
    $productOptions .= '<option value="' . $val . '">' . $name . '</option>';
}
$homepage = new adminTemplate();
$content = $homepage -> content = '
            <div class="fieldset-wrapper2">
            <form action="addslide.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>slider</legend>
                    <p>
                        <label for="adding-picture1">Select image to upload:</label>
                        <input type="file" name="productimage" id="adding-picture1">
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Slider info</legend>
                    <p>
                        <label for="adding-name">titel:</label>
                        <input type="text" name="name" value="" id="adding-name">
                    </p>
                    <p>
                        <label for="adding-description">Text:</label>
                        <textarea type="text" name="text" value="" id="adding-description">
                        </textarea>
                    </p>
                    
                </fieldset>
                <fieldset>
                <legend>submiting</legend>
                <label for="assign-product">assign to product</label>
                <select id="assign-product" name="product">
                ' . $productOptions . '
                </select>
                <button type="submit" name="submit">add slide</button>
                </fieldset>
                
            </form>';



$homepage -> Display();


?>


