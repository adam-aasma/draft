<?php
use Walltwisters\data\RepositoryFactory;


require_once 'library/security.php';
require_once 'library/FormUtilities.php';
require_once 'data/RepositoryFactory.php';
require_once 'data/ImageRepository.php';
require_once 'library/Images.php';
require_once 'service/ProductService.php';

$title = 'Edit Product';
$keywordContent = "very important SEO stuff";

require_once 'adminpageheaderlogic.php';

$productService = new ProductService(RepositoryFactory::getInstance());
$editProduct = null;
$checkedSizes = [];
$checkedMaterials = [];
$checkedTechniques = [];
$productId = null;
$submitName = "Add Product";

$validSize = true;
if (isset($_POST["submit"])) {
    try{
        $isUpdate = isset($_POST['productid']);
        $imageCategoryValues= [];
        $inx = 0;
        while (isset($_POST['category'][$inx])){
            $imageCategoryValues[] = $_POST['category'][$inx];
            $inx++;
        }

        if ($isUpdate) {
            $productId = $_POST['productid'];
            $productService->updateProduct(
                    $productId,
                    $imageCategoryValues,
                    $_FILES,
                    $_POST['product_info'],
                    $_POST['format'],
                    $_POST['section'],
                    $_POST['material'],
                    $_POST['sizes'],
                    $user->id
                    );
        } else {
            //adding new product procedure            
            $productId = $productService->addProduct(
                    $imageCategoryValues,
                    $_FILES,
                    $_POST['product_info'],
                    $_POST['format'],
                    $_POST['section'],
                    $_POST['material'],
                    $_POST['sizes'],
                    $user->id
                    );
        }
            
          // after new product is added redirect for preview in ShowRoom   
        if (headers_sent()) {
            die("Redirect failed");
        } else {
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
$countrylanguages = $productService->getCountryLanguages2($countries);
$materialOptions = FormUtilities::getAllCheckBoxes($materials, 'material', 'material', $checkedMaterials);
//$languageOptions = FormUtilities::getAllOptions($languages, 'language');

require_once 'adminpageheader.php';
?>

<form action="editproduct.php" method="post" enctype="multipart/form-data">
    <?php if (!empty($productId)) : ?>
    <input type="hidden" name="productid" value="<?= $productId ?>" />
    <?php endif; ?>
    <fieldset id='productImages'>
        <legend>product images</legend>
        <a id="addimage"></a>
        <?php if (!empty($editProduct)) : ?>
        <?php $idx = 0; foreach ($editProduct->imageBaseInfos as $imageBaseInfo) : ?>
            <div>
                <p class="checkbox index">
                    <?= FormUtilities::getAllRadioOptions($productService->getImageCategoriesBy(['product', 'productinterior']),'category', "category[$idx]", [$imageBaseInfo->categoryId]) ?>
                </p>
                <span><?= $imageBaseInfo->imageName ?></span>
                <span><aclass="deleteimage">delete</a></span>
            </div>

        <?php $idx++; endforeach; ?>
        <?php else : ?>
            <div>
                <p class="checkbox index">
                    <?= FormUtilities::getAllRadioOptions($productService->getImageCategoriesBy(['product', 'productinterior']),'category', "category[0]");?>
                </p>
                <input type="file" name="pictype[]"/>
            </div>
        <?php endif; ?>
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
        <p class="checkbox control">
            <?= $materialOptions ?>
        </p>
       <?php foreach ($materials as $material) : ?>
            <?php $sizes = $productService->getSizesForMaterial($material); ?>
            <p class="selectOptions">
                <label>select sizes for <?= $material->material?></label>
                <select multiple name="sizes[<?=$material->id?>][]">
                    <?php if(!empty($editProduct)) : ?>
                        <?php $checkedIds = []; ?>
                        <?php foreach($editProduct->items as $item) : ?>
                            <?php $checkedIds[] = $item->sizeId ?>
                        <?php endforeach; ?>
                        <?= FormUtilities::getAllOptions($sizes, 'sizes', $checkedIds) ?>
                    
                        <?php else : ?>
                        <?= FormUtilities::getAllOptions($sizes, 'sizes') ?>
                    <?php endif; ?>
               </select>
            </p>
        <?php endforeach; ?> 
    </fieldset>
    <fieldset>
        <legend>search details</legend>
            <label for="adding-format">format:</label>
                <select name="format">
                    <?php if(!empty($editProduct)) : ?>
                       <?php $formatId[] = $editProduct->formatId ?>
                       <?= FormUtilities::getAllOptions($productService->getAllFormats(), 'format', $formatId) ?>
                    <?php else : ?>
                    <?= FormUtilities::getAllOptions($productService->getAllFormats(), 'format') ?>
                    <?php endif; ?>
                </select>
            <label for="choosing-section">section:</label>
            <select name="section">
                <?php if(!empty($editProduct)) : ?>
                    <?php $sectionId[] = $editProduct->sectionId ?>
                    <?= FormUtilities::getAllOptions($productService->getAllSections(), 'titel',  $sectionId)?>
                <?php else : ?>
                <?= FormUtilities::getAllOptions($productService->getAllSections(), 'titel');?>
                <?php endif; ?>
            </select>
            <label>Tag Words</label>
            <input type="text" name="searchtags">
    </fieldset>
    <fieldset class="submitField">
        <legend>submit</legend>
        <button type="submit" name="submit"><?= $submitName ?></button>
        <?php if(!empty($editProduct)) : ?>
            <button type="submit" name="delete">Delete product</button>
        <?php endif; ?>
        <div class="block">
            <p class="inline">
                <label>countries:</label>
                    <select id="countries">
                        <?= FormUtilities::getAllOptions($countries, 'country') ?>
                    </select>
            </p>
            <p class="inline">
                <label for="adding-language">language:</label>
                    <select id="languages">
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
<script>
    var countryLanguages = <?= json_encode($countrylanguages) ?>
</script>
<?php $script = '/js/product.js'; ?>
<?php require_once 'adminpagefooter.php'; ?>