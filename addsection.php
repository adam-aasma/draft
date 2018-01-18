<?php
use \Walltwisters\data\RepositoryFactory;

$titel = 'addsection';
$keywordContent = '';
require_once 'service/ImageService.php';
require_once 'service/SectionService.php';
require_once 'adminpageheaderlogic.php';
require_once 'adminpageheader.php';
require_once 'library/Images.php';
require_once 'data/RepositoryFactory.php';

$imageService = new ImageService(RepositoryFactory::getInstance());
if (isset($_POST['submit'])){
    $sectionPictures = [ 'desktopbig' => $_FILES['desktopbig'] , 'desktopsmall' => $_FILES['desktopsmall'], 'mobile' => $_FILES['mobile']];
    $titel = $_POST['titel'];
    $salesLine = $_POST['salesline'];
    $imageIds = $imageService->addSectionImages($sectionPictures);
    $sectionService =  new SectionService(RepositoryFactory::getInstance());
    $languageId = 3;
    $productIds = $_POST['products'];
    $sectionService->addSection($titel, $salesLine, $imageIds, $user->id, $languageId,$productIds);
}
$html = '';
$thumbnails = '';
foreach ($_POST['products'] as $product){
        $html .= "<input type='hidden' name='products[]' value='$product'/>";
        $id = $imageService->getProductImageIdById($product);
        $thumbnails .= "<img class='picturethumbnails' src='getimage.php?id=$id' alt='productpic' />";
}
?>
<div id="notjustify" />
    <form action="addsection.php" method="post" enctype="multipart/form-data" id="addingSectionForm">
        <fieldset id="addingSection">
            <legend>Section:</legend>
            <p>
                <label class="inline-block">add big picture for desktop</label>
                <input class="inline-block" type="file" name="desktopbig"/>
            </p>
            <p>
                <label class="inline-block">add small picture for desktop</label>
                <input class="inline-block" type="file" name="desktopsmall"/>
            </p>
            <p>
                <label class="inline-block">add picture for mobile</label>
                <input class="inline-block" type="file" name="mobile" />
            </p>
            <p>
                <label>add titel:</label>
                <input type="text" name="titel" />
            </p>
            <p>
                <label>add salesline</label>
                <textarea type="text" name="salesline"></textarea>
            </p>
        </fieldset>
        <?= $html ?>
        <button type="submit" name="submit" form="addingSectionForm" id="sectionSubmitButton">submit</button>
    </form>
    <div id="sectionProducts">
        <?= $thumbnails ?>
    </div>
        
    
               
</div>
<?php $script= '/js/addsection.js' ?>
<?php require_once 'adminpagefooter.php'?>


