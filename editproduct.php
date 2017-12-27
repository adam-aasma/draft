<?php

require_once 'checkauth.php';
require_once 'library/security.php';
require_once 'library/FormUtilities.php';
require_once 'data/RepositoryFactory.php';
require_once 'data/ImageRepository.php';
require_once 'library/Images.php';
require_once 'service/ProductService.php';

$title = 'Edit Product';
$keywordContent = "very important SEO stuff";

require_once 'adminpageheader.php';

$productService = new ProductService(RepositoryFactory::getInstance());
$editProduct = null;
$checkedSizes = [];
$checkedMaterials = [];
$checkedTechniques = [];
$productId = null;
$submitName = "Add Product";

$validSize = true;
if (isset($_POST["submit"])) {
    if (!isset($_POST['sizes'])) {
        $validSize = false;
    }
    
    if ($validSize) {
        $imagedatas = Images::getImageData($_FILES);
        try{
            $isUpdate = isset($_POST['productid']);
            if ($isUpdate) {
                $productId = $_POST['productid'];
                // TODO updateProduct
            } else {
                $productId = $productService->addProduct(
                        $imagedatas,
                        $_POST['product_info'],
                        $_POST['format'],
                        $_POST['category'],
                        $_POST['sizes'],
                        $_POST['material'],
                        $_POST['technique'],
                        $user->id
                        );
                }
            header("location: productshowroom.php?productid= $productId");
            die();
        } catch (Exception $e) {
            http_response_code(500); 
            $message = $e->getMessage();
        }
    }
} else if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $submitName = "Edit Product";
    $editProduct = $productService->getProductById($productId);
    foreach($editProduct->items as $item) {
        $checkedSizes[] = $item->sizeId;
        $checkedMaterials[] = $item->materialId;
        $checkedTechniques[] = $item->printTechniqueId;
    }
}

$countries = $user->countries;
$languages = $productService->getCountryLanguages($countries);
$formatOptions = FormUtilities::getAllOptions($productService->getAllFormats(), 'format');
$sizeOptions = FormUtilities::getAllCheckBoxes($productService->getAllSizes(), 'sizes', 'sizes', $checkedSizes);
$materialOptions = FormUtilities::getAllCheckBoxes($productService->getAllMaterials(), 'material', 'material', $checkedMaterials);
$techniqueOptions = FormUtilities::getAllCheckBoxes($productService->getAllPrintTechniques(), 'technique', 'technique', $checkedTechniques);
$countryOptions = FormUtilities::getAllOptions($countries, 'country');
$categoryOptions = FormUtilities::getAllOptions($productService->getAllSections(), 'name');
$languageOptions = FormUtilities::getAllOptions($languages, 'language');
?>

<form action="editproduct.php" method="post" enctype="multipart/form-data">
    <?php if (!empty($productId)) : ?>
    <input type="hidden" name="productid" value="<?= $productId ?>" />
    <?php endif; ?>
    <fieldset>
        <legend>product images</legend>
        <label for="adding-picture1">Select image to upload:</label>
        <input type="file" name="productimage" id="adding-picture1" />
        <label for="adding-picture2">Select interior pic to upload:</label>
        <input type="file" name="interiorimage" id="adding-picture2" />
        <label>countries:</label>
                <select id="countries" onchange="selectCountryLanguage()">
                    <?= $countryOptions ?>
                </select>
        <label for="adding-language">language:</label>
                <select id="languages" onchange="selectCountryLanguage()">
                    <?= $languageOptions ?>
                </select>
    </fieldset>
    <?php foreach ($countries as $country) : ?>
    <?php   foreach ($languages as $language) : ?>
    <?php 
                $nameValue = '';
                $descriptionValue = '';
                if (!empty($editProduct)) {
                    if (isset($editProduct->productDescriptions[$country->id][$language->id])) {
                        $nameValue = $editProduct->productDescriptions[$country->id][$language->id]->name;
                        $descriptionValue = $editProduct->productDescriptions[$country->id][$language->id]->descriptionText;
                    }
                }
    ?>
    <fieldset class="productinfo" id="productinfo_<?= $country->id ?>_<?= $language->id ?>">
        <legend><?= $country->country ?>_<?= $language->language ?></legend>
        <p>
            <label>name:</label>
            <input type="text" name="product_info[<?= $country->id ?>][<?= $language->id ?>][name]" value="<?= $nameValue ?>">
        </p>
        <p>
            <label>product description:</label>
            <textarea type="text" name="product_info[<?= $country->id ?>][<?= $language->id ?>][description]">
                <?= $descriptionValue ?>
            </textarea>
        </p>
    </fieldset>
    <?php   endforeach; ?>
    <?php endforeach; ?>
    <fieldset>
        <legend>price details</legend>
        <label for="select-size" class="checkbox-header">sizes:</label>
        <?= !$validSize ? '<span style="color: red">You must select at least one size</span>' : '' ?>
        <p class="checkbox">
            <?= $sizeOptions ?>
        </p>
        <label class="checkbox-header">paper:</label>
        <p class="checkbox">
            <?= $materialOptions ?>
        </p>
        <label class="checkbox-header">printing:</label>
        <p class="checkbox">
            <?= $techniqueOptions ?>
        </p>
    </fieldset>
    <fieldset>
        <legend>search details</legend>
            <label for="adding-format">format:</label>
                <select name="format">
                    <?= $formatOptions ?>
                </select>
            <label for="chossing-category">category:</label>
            <select name="category">
                <?= $categoryOptions ?>
            </select>
            <label>color:</label>
            <input type="text" name="color">
            <label for="descriping-motive">motive:</label>
            <input type="text" name="motive" id="descipting-motive">
    </fieldset>
    <fieldset>
        <legend>submit</legend>
      <!--  <button type="submit" name="preview">preview in showroom</button> -->
        <button type="submit" name="submit"><?= $submitName ?></button>
    </fieldset>
    <fieldset>
        <legend>notes</legend>
    </fieldset>
</form>
<script>initFunctionTable.push(function() { selectCountryLanguage(); })</script>';

<?php require_once 'adminpagefooter.php'; ?>