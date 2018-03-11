<?php
use \Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\SectionService;
use Walltwisters\service\ImageService;
use Walltwisters\utilities\Images;
use Walltwisters\utilities\FormUtilities;

$titel = 'addsection';
$keywordContent = '';
$css = 'css/addSection.css';

require_once 'adminpageheaderlogic.php';
require_once 'adminpageheader.php';


$sectionService =  new SectionService(RepositoryFactory::getInstance());
$imageService = new ImageService(RepositoryFactory::getInstance());
$countries = $user->countries;
$languages = $sectionService->getCountryLanguages($user->countries);
$isUpdate = false;
$imagehtml = '';
$html = '';
$thumbnails = '';
$selectedProducts = null;
if (isset($_GET['sectionid'])){
    $isUpdate = true;
    $editsection = $sectionService->getCompleteSectionsById($_GET['sectionid']);
    foreach($editsection->imageBaseInfos as $imageBaseInfo){
        $imagehtml .= "<div class='row'>
                        <label>$imageBaseInfo->category </label>
                        <img src='getimage.php?id=$imageBaseInfo->id' style='width: 20px;'
                       </div>";
    }
    $selectedProducts = $sectionService->getSelectedproductsById($editsection->productIds, true);
} else {
    $imagesCategories = $sectionService->getImageCategoriesBy('sectionImageCategories');
    foreach ( $imagesCategories as $imageCategory){
        $imagehtml .= "<div class='row'>
                        <label>$imageCategory->category </label>
                        <input type='file' name='$imageCategory->category'/>
                      </div>";
    }
}
if (isset($_POST['submit'])){
    $sectionPictures = [ 'desktopbig' => $_FILES['sectionbig'] , 'desktopsmall' => $_FILES['sectionsmall'], 'mobile' => $_FILES['sectionmobile']];
    $imageIds = $imageService->addSectionImages($sectionPictures);
    $countryId = $_POST['country'];
    $languageId = $_POST['language'];
    $productIds = null;
    if(!empty($_POST['products'])){
        $productIds = $_POST['products'];
    }
    $sectionInfos = ['title' => $_POST['title'], 'saleslineheader' => $_POST['saleslineheader'], 'saleslineparagraph' => $_POST['saleslineparagraph']];
    $sectionIds = $sectionService->addSection($sectionInfos, $imageIds, $user->id, $countryId, $languageId, $productIds);
}

if(isset($_POST['products'])){
    $selectedProducts = $sectionService->getSelectedproductsById($_POST['products'], true);
    } 
?>
<div id="addSection" class="center" />
    <form action="addsection.php"  method="post" enctype="multipart/form-data" id="addingSectionForm">
            <fieldset class="addingGenerals">
                <?php if($isUpdate) : ?>
                  <legend>Create Section</legend>
                    <?php foreach($editsection->sectionBaseInfos as $sectionBaseInfo) : ?>
                        <p class="Inline-flex space-between">
                            <label>title:</label>
                            <input type="text" name="title"  value="<?=$sectionBaseInfo->title?>"/>
                        </p>
                        <p class="Inline-flex space-between">
                            <label>salesline header:</label>
                            <input type="text" name="saleslineheader" value="<?=$sectionBaseInfo->saleslineHeader?>"></input>
                        </p>
                        <p class="Inline-flex space-between">
                            <label>salesline paragraph:</label>
                            <input type="text" name="saleslineparagraph" value="<?=$sectionBaseInfo->salelineParagraph?>"></input>
                        </p>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="inline-flex space-around rowHeader">
                        <legend>Create Section</legend>
                        <select name="country">
                            <?= FormUtilities::getAllOptions($user->countries, 'country');?>
                        </select>
                        <select name="language">
                            <?= FormUtilities::getAllOptions($sectionService->getCountryLanguages($user->countries), 'language') ?>
                        </select>
                    </div>
                    <p class="Inline-flex space-between row">
                        <label>title:</label>
                        <input type="text" name="title" />
                    </p>
                    <p class="Inline-flex space-between row">
                        <label>salesline header:</label>
                        <input type="text" name="saleslineheader"></input>
                    </p>
                    <p class="Inline-flex space-between row">
                        <label>salesline paragraph:</label>
                        <input type="text" name="saleslineparagraph"></input>
                    </p>
                <?php endif; ?>
            </fieldset>
        <fieldset id="addingSectionImages">
            <legend class="rowHeader">add images</legend>
            <?= $imagehtml ?>
        </fieldset> 
   
        <div id="addSectionActionBar">
            <select name="sectionName">
                <option value='' disabeled selected>New Section</option>
                <?= FormUtilities::getAllOptions($sectionService->getAllSectionNamesBy($countries[0], $languages[0]), 'title') ?>
            </select>
            <div id="sectionProductsHeader" class="Inline-flex">
                <span>included in section</span>
                <span>all products</span>
            </div>
            <div class="Inline-flex space-between">
                <div class="sectionProducts" id="sectionProducts">
                    <?php if($selectedProducts) : ?>
                        <?= $selectedProducts ?>
                    <?php endif; ?>
                </div>
                <div class="sectionProducts" id="sectionAllProducts">
                    <?php $fullProductList = $sectionService->getAvailableProductsforSection($countries[0], $languages[0]) ?>
                    <?= $fullProductList?>
                </div>
                <?php $isUpdate == true ? $buttonName= 'edit' : $buttonName ='add'?>
                <button type="submit" name="submit" form="addingSectionForm" id="sectionSubmitButton"><?= $buttonName ?></button>
            </div>
        </div>
    </form>
</div>
<?php $script= 'js/addsection.js'; ?>
<?php require_once 'adminpagefooter.php'; ?>


