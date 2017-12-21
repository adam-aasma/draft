<?php

require_once 'checkauth.php';
require_once 'views/admintemplate.php';
require_once 'library/security.php';

require_once 'data/RepositoryFactory.php';
require_once 'data/ImageRepository.php';
require_once 'library/Images.php';
require_once 'service/ProductService.php';

$homepage = new adminTemplate();
$repositoryFactory = new RepositoryFactory();
$productrep = $repositoryFactory->productRepository;
$languageRepo = $repositoryFactory->languageRepository;

if (isset($_POST["submit"])) {
    $imagerepo = new ImageRepository;
    $productService = new ProductService($repositoryFactory->productRepository, $repositoryFactory->languageRepository, $imagerepo);

    $imagedatas = Images::getImageData($_FILES);
    try{ 
        $productId = $productService->addProduct(
                $imagedatas,
                $_POST['product_name'],
                $_POST['product_description'],
                $_POST['format'],
                $_POST['category'],
                $_POST['subcategory'],
                $_POST['language'],
                $_POST['size'],
                $_POST['material'],
                $_POST['technique'],
                $_POST['countries'],
                $homepage->user->id
                );
        header("location: productshowroom.php?productid= $productId");
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
} 

$titel = $homepage -> title = 'addproduct';
$formats = $productrep->getFormat();
$sizes = $productrep->getSize();
$materials = $productrep->getMaterial();
$techniques = $productrep->getTechnique();
$categories = $productrep->getProductCategory();
$subcategories = $productrep->getProductSubCategory();
$languages = $languageRepo->getAllLanguages();
$formatOptions = '';
$sizeOptions = '';
$materialOptions = '';
$techniqueOptions = '';
$subcategoryOptions = '';
$categoryOptions = '';
$languageOptions = '';
$countriesOptions = '';
foreach($formats as $format) {
    $val = $format->id;
    $text = $format->format;
    $formatOptions .= "<option value='" . $val . "' name='format'>" . $text . "</option>";
}
foreach($sizes as $size) {
    $val = $size->id;
    $text = $size->sizes;
    $name = $size->name;
    $sizeOptions .= '<input type="checkbox" value="' . $val . '" name="size[]"><label>' . $text . '</label>';
}
foreach($materials as $material) {
    $val = $material->id;
    $text = $material->material;
    $materialOptions .= '<input type="checkbox" value="' . $val . '" name="material[]"><label>' . $text . '</label>';
}
foreach($techniques as $technique) {
    $val = $technique->id;
    $text = $technique->technique;
    $techniqueOptions .= '<input type="checkbox" value="' . $val . '" name="technique[]"><label>' . $text . '</label>';
                        
}
foreach($categories as $category) {
    $val = $category->id;
    $text = $category->name;
    $categoryOptions .= '<option value="' . $val. ' ">' . $text . '</option>';
                        
}
foreach($subcategories as $subcategory) {
    $val = $subcategory->id;
    $text = $subcategory->category;
    $subcategoryOptions .= '<option value="' . $val . '">' . $text . '</option>';
                        
}
foreach($languages as $language) {
    $val = $language->id;
    $text = $language->language;
    $languageOptions .= '<option value="' . $val . '">' . $text . '</option>';
                        
}
foreach($homepage->user->countries as $country) {
    $val = $country->id;
    $text = $country->country;
    $countriesOptions .= '<option value="' . $val . ' ">' . $text . '</option>';
                        
}

$content = $homepage -> content = '
            <div class="fieldset-wrapper2">
            <form action="addproduct2.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>product images</legend>
                    <label for="adding-picture1">Select image to upload:</label>
                    <input type="file" name="productimage" id="adding-picture1" />
                    <label for="adding-picture2">Select interior pic to upload:</label>
                    <input type="file" name="interiorimage" id="adding-picture2" />
                </fieldset>
                <fieldset>
                    <legend>product details</legend>
                    <p>
                    <label>countries:</label>
                            <select multiple  name="countries[]">
                                ' . $countriesOptions . '
                            </select>
                    </p>
                    <label for="adding-language">language:</label>
                            <select  name="language">
                                ' . $languageOptions . '
                            </select>
                    </p>
                    <p>
                        <label for="adding-name">name:</label>
                        <input type="text" name="product_name" value="name" id="adding-name">
                    </p>
                    <p>
                        <label for="adding-description">product description:</label>
                        <textarea type="text" name="product_description" value="$description" id="adding-description">
                        
                        </textarea>
                    </p>
                    
                </fieldset>
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
                    <p>
                        <label for="adding-format">format:</label>
                            <select name="format">
                                ' . $formatOptions . '
                            </select>
                    </p>
                    <p>
                        <label for="chossing-category">category:</label>
                        <select name="category">
                            ' . $categoryOptions . '
                        </select>
                    </p>
                    <p>
                        <label>subcategory:</label>
                        <select name="subcategory">
                            ' . $subcategoryOptions . '
                        </select>
                    </p>
                  <!--  <p>
                        <label>color:</label>
                        <input type="text" name="color">
                    </p>-->
                    
                 <!--   <p>
                        <label for="descriping-motive">motive:</label>
                        <input type="text" name="motive" id="descipting-motive">
                    </p> -->
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
      </form>';



$homepage -> Display();


?>
