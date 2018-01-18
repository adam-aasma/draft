<?php

use Walltwisters\data\RepositoryFactory;

require_once 'data/RepositoryFactory.php';
require_once 'library/FormUtilities.php';
require_once 'service/SliderService.php';

$titel = 'add slider';
$keywordContent = '';
require_once 'adminpageheaderlogic.php';

if (isset($_POST['submit'])){
    try {
        $sliderService = new SliderService(RepositoryFactory::getInstance());
        $imagerepo = new Walltwisters\data\ImageRepository();
        $image = Walltwisters\model\Image::create($_FILES['sliderimage']['tmp_name'], $_FILES['sliderimage']['size'], $_FILES['sliderimage']['type'], $_FILES['sliderimage']['name'], 4); //TODO DYNAMICALLY GET CATEGORY_ID NOW SET TO 4
        $imageId = $imagerepo->addImage($image);
        $sliderId = $sliderService->addSlider(  $_POST['name'],
                                                $_POST['text'],
                                                $_POST['productid'],
                                                $imageId,
                                                $user->id
                                             );
        if (headers_sent()){
            die("redirect failed");
        } else {
        exit(header("location: showslides.php?slider=$sliderId"));
        die();
        }
    }     
    catch (Exception $e) {
        $message = $e->getMessage();
    }
    } else {
        $productId = $_GET['id'];
    }
require_once 'adminpageheader.php';
?>
            
            <form action="addslide.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="productid" value='<?= $productId ?>' />
                <fieldset>
                    <legend>slider</legend>
                    <p>
                        <label for="adding-picture1">Select image to upload:</label>
                        <input type="file" name="sliderimage" id="adding-picture1">
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
                <button type="submit" name="submit">add slide</button>
                </fieldset>
                
            </form>
  <?php $script = '/js/addslide.js'  ; ?>        
 <?php require_once 'adminpagefooter.php'; ?>




