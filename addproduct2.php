<?php

require_once 'checkauth.php';
require_once 'views/admintemplate.php';
require_once 'library/security.php';
require_once 'data/productRepository.php';
require_once 'data/ImageRepository.php';
require_once 'library/Images.php';

$homepage = new adminTemplate();
if (isset($_POST["submit"])) {
        $imagerepo = new ImageRepository;
        $imagedatas = Images::getImageData($_FILES);
        //$imageData2[] = $imagerepo->getImageData($_FILES['interiorimage']['tmp_name'], $_FILES['interiorimage']['size'], $_FILES['interiorimage']['type']);
        $name = $_POST['product_name'];
        $description = $_POST['product_description'];
        $format = $_POST['format'];
        $category = $_POST['category'];
        $subcategory = $_POST['subcategory'];
        $language_id = $_POST['language'];
        $size = $_POST['size'];
        $material = $_POST['material'];
        $technique = $_POST['technique'];
        $countryindexes = $_POST['countries'];
        $userid = $homepage->user->id;
        $product = new Product(0, $name, $userid);
        $productrep = new ProductRepository();
        try{ 
            $product = $productrep->addProduct($product);
            foreach ($imagedatas as $key => $imagedata) {
                $image = new Image($imagedata['filepath'], $imagedata['size'], $imagedata['mime'], $name, $key == 0 ? 'product' : 'productinterior');
                $imageId = $imagerepo->addImage($image);
                $productrep->addProductImage($product->id, $imageId);
            }
            $productDescription = new ProductDescription();
            $productDescription->product = $product;
            $productDescription->descriptionText = $description;
            $language = new Language();
            $language->id = $language_id;
            $productDescription->language = $language;
            $productDescription->country = Country::create($countryindexes[0], '');
            $productrep->addProductDescriptionToProduct($productDescription);
            foreach ($countryindexes as $countryindex){
                $country = Country::create($countryindex, '');
                $productrep->addCountryForProduct($country, $product);
            }
            $productrep->addProductCategorySubCategory($product->getId(), $category, $subcategory);
            $productrep->addItem($product->getId(), $size, $material, $technique);
            header("location: productshowroom.php?productid= $product->id");
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

} 

$titel = $homepage -> title = 'addproduct';
$productrep = new ProductRepository();
$formats = $productrep->getFormat();
$sizes = $productrep->getSize();
$materials = $productrep->getMaterial();
$techniques = $productrep->getTechnique();
$categories = $productrep->getProductCategory();
$subcategories = $productrep->getProductSubCategory();
$languages = $productrep->getLanguages();
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
