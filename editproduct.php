<?php
use Walltwisters\data\RepositoryFactory;

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
    try{$isUpdate = isset($_POST['productid']);
        if ($isUpdate) {
            $productId = $_POST['productid'];
            // TODO updateProduct.... in case we coming from productList then id set..
            
            
        } else {
            //adding new product procedure
            
            $imageCategoryValues= [];
            $inx = 0;
            while ($_POST[$inx]){
                $imageCategoryValues[] = $_POST[$inx];
                $inx++;
            }
            $productId = $productService->addProduct(
                    $imageCategoryValues,
                    $_FILES,
                    $_POST['product_info'],
                    $_POST['format'],
                    $_POST['category'],
                    $_POST['sizes'],
                    $user->id
                    );
            }
            
          // after new product is added redirect for preview in ShowRoom   
        if (headers_sent()) {
            die("Redirect failed");
        } else{
            exit(header("location: productshowroom.php?productid=$productId"));
            die(); 
          }
          // if the add didn't succedd Exception trown
     } catch (Exception $e) {
        http_response_code(500); 
        $message = $e->getMessage();
    }
}

// for editing product
 else if (isset($_GET['id'])) {
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
$materials = $productService->getAllMaterials();
$languages = $productService->getCountryLanguages($countries);
$formatOptions = FormUtilities::getAllOptions($productService->getAllFormats(), 'format');
$materialOptions = FormUtilities::getAllCheckBoxes($materials, 'material', 'material', $checkedMaterials);
$imageCategoryOptions = FormUtilities::getAllRadioOptions($productService->getImageCategoriesBy(),'category', '0');
$countryOptions = FormUtilities::getAllOptions($countries, 'country');
$sectionOptions = FormUtilities::getAllOptions($productService->getAllSections(), 'name');
$languageOptions = FormUtilities::getAllOptions($languages, 'language');
?>

<form action="editproduct.php" method="post" enctype="multipart/form-data">
    <?php if (!empty($productId)) : ?>
    <input type="hidden" name="productid" value="<?= $productId ?>" />
    <?php endif; ?>
    <fieldset id='productImages'>
        <legend>product images</legend>
        <a id="addimage"></a>
        <p class="checkbox index">
            <?=$imageCategoryOptions?>
        </p>
        <input type="file" name="pictype[]"/>
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
        <legend>Item details</legend>
        <p class="checkbox">
            <?= $materialOptions ?>
        </p>
       <?php foreach ($materials as $material) : ?>
            <?php $sizes = $productService->getSizesForMaterial($material); ?>
            <p class="selectOptions">
                <label>select sizes for <?= $material->material?></label>
                <select multiple name="sizes[<?=$material->id?>][]">
                    <?= FormUtilities::getAllOptions($sizes, 'sizes') ?>
               </select>
            </p>
        <?php endforeach; ?> 
    </fieldset>
    <fieldset>
        <legend>search details</legend>
            <label for="adding-format">format:</label>
                <select name="format">
                    <?= $formatOptions ?>
                </select>
            <label for="chossing-category">category:</label>
            <select name="category">
                <?= $sectionOptions ?>
            </select>
            <label>Tag Words</label>
            <input type="text" name="searchtags">
    </fieldset>
    <fieldset class="submitField">
        <legend>submit</legend>
      <!--  <button type="submit" name="preview">preview in showroom</button> -->
        <button type="submit" name="submit"><?= $submitName ?></button>
        <div class="block">
            <p class="inline">
                <label>countries:</label>
                    <select id="countries">
                        <?= $countryOptions ?>
                    </select>
            </p>
            <p class="inline">
                <label for="adding-language">language:</label>
                    <select id="languages">
                        <?= $languageOptions ?>
                    </select>
            </p>
        </div>
    </fieldset>
    <fieldset class="notes">
        <legend>notes</legend>
        <p>
            
        </p>
    </fieldset>
</form>
<?php $script = '/js/product.js'; ?>
<?php require_once 'adminpagefooter.php'; ?>