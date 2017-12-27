<?php

require_once 'checkauth.php';
require_once 'views/admintemplate.php';
require_once 'library/security.php';
require_once 'library/FormUtilities.php';
require_once 'data/RepositoryFactory.php';
require_once 'data/ImageRepository.php';
require_once 'library/Images.php';
require_once 'service/ProductService.php';

$homepage = new adminTemplate();
$productService = new ProductService(RepositoryFactory::getInstance());

if (isset($_POST["submit"])) {

    $imagedatas = Images::getImageData($_FILES);
    try{ 
        $productId = $productService->addProduct(
                $imagedatas,
                $_POST['product_info'],
                $_POST['format'],
                $_POST['category'],
                $_POST['sizes'],
                $_POST['material'],
                $_POST['technique'],
                $homepage->user->id
                );
        header("location: productshowroom.php?productid= $productId");
        die();
    } catch (Exception $e) {
        http_response_code(500); 
        $message = $e->getMessage();
    }
} 

$titel = $homepage ->title = 'addproduct';
$countries = $homepage->user->countries;
$languages = $productService->getCountryLanguages($countries)
$formatOptions = FormUtilities::getAllOptions($productService->getAllFormats(), 'format');
$sizeOptions = FormUtilities::getAllCheckBoxes($productService->getAllSizes(), 'sizes', 'sizes');
$materialOptions = FormUtilities::getAllCheckBoxes($productService->getAllMaterials(), 'material', 'material');
$techniqueOptions = FormUtilities::getAllCheckBoxes($productService->getAllPrintTechniques(), 'technique', 'technique');
$countryOptions = FormUtilities::getAllOptions($countries, 'country');
$categoryOptions = FormUtilities::getAllOptions($productService->getAllSections(), 'name');
$languageOptions = FormUtilities::getAllOptions($languages, 'language');
$infoHtml = '';
foreach ($countries as $country){
    foreach ($languages as $language){
        $infoHtml .= '<fieldset class="productinfo" id="productinfo_' . $country->id . '_' . $language->id . '">
                    <legend>' . $country->country . '_' . $language->language .  '</legend>
                    <p>
                        <label>name:</label>
                        <input type="text" name="product_info[' . $country->id .'][' . $language->id . '][name]">
                    </p>
                    <p>
                        <label>product description:</label>
                        <textarea type="text" name="product_info[' . $country->id .'][' . $language->id . '][description]">
                        
                        </textarea>
                    </p>
                 </fieldset>';
        
    }
}

$content = $homepage -> content = '
            <div class="desktop">
            <form action="addproduct2.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>product images</legend>
                    <label for="adding-picture1">Select image to upload:</label>
                    <input type="file" name="productimage" id="adding-picture1" />
                    <label for="adding-picture2">Select interior pic to upload:</label>
                    <input type="file" name="interiorimage" id="adding-picture2" />
                    <label>countries:</label>
                            <select id="countries" onchange="selectCountryLanguage()">
                                ' . $countryOptions . '
                            </select>
                    <label for="adding-language">language:</label>
                            <select id="languages" onchange="selectCountryLanguage()">
                                ' . $languageOptions . '
                            </select>
                </fieldset>
                    ' . $infoHtml . '
                <fieldset>
                    <legend>price details</legend>
                    <label for="select-size" class="checkbox-header">sizes:</label>
                    <p class="checkbox">
                        ' . $sizeOptions . '
                    </p>
                    <label class="checkbox-header">paper:</label>
                    <p class="checkbox">
                        ' . $materialOptions . '
                    </p>
                    <label class="checkbox-header">printing:</label>
                    <p class="checkbox">
                        ' . $techniqueOptions . '
                    </p>
                </fieldset>
                <fieldset>
                    <legend>search details</legend>
                        <label for="adding-format">format:</label>
                            <select name="format">
                                ' . $formatOptions . '
                            </select>
                        <label for="chossing-category">category:</label>
                        <select name="category">
                            ' . $categoryOptions . '
                        </select>
                        <label>color:</label>
                        <input type="text" name="color">
                        <label for="descriping-motive">motive:</label>
                        <input type="text" name="motive" id="descipting-motive">
                </fieldset>
                <fieldset>
                    <legend>submit</legend>
                  <!--  <button type="submit" name="preview">preview in showroom</button> -->
                    <button type="submit" name="submit">add product</button>
                </fieldset>
                <fieldset>
                    <legend>notes</legend>
                </fieldset>
            </div>
      </form>
      <script>initFunctionTable.push(function() { selectCountryLanguage(); })</script>';



$homepage -> Display();


?>
