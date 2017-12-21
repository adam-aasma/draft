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
$repositoryFactory = new RepositoryFactory();
$productrep = $repositoryFactory->productRepository;
$languagerep = $repositoryFactory->languageRepository;
$formatrep = $repositoryFactory->productFormatRepository;
$sizerep = $repositoryFactory->productSizeRepository;
$materialrep = $repositoryFactory->productMaterialRepository;
$techniquerep = $repositoryFactory->productPrintTechniqueRepository;
$categoriesrep = $repositoryFactory->productCategoryRepository;
$subcategoriesrep = $repositoryFactory->productSubCategoryRepository;

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
                $_POST['sizes'],
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

$titel = $homepage ->title = 'addproduct';
$formats = $formatrep->getAllFormats();
$sizes = $sizerep->getAllSizes();
$materials = $materialrep->getAllMaterials();
$techniques = $techniquerep->getAllProductPrintTechniques();
$categories = $categoriesrep->getAllProductCategories();
$subcategories = $subcategoriesrep->getAllProductSubCategories();
$languages = $languagerep->getAllLanguages();
$countries = $repositoryFactory->countryRepository->getAllCountries();

$formatOptions = FormUtilities::getAllOptions($formats, 'format');
$sizeOptions = FormUtilities::getAllCheckBoxes($sizes, 'sizes', 'sizes');
$materialOptions = FormUtilities::getAllCheckBoxes($materials, 'material', 'material');
$techniqueOptions = FormUtilities::getAllCheckBoxes($techniques, 'technique', 'technique');
$countryOptions = FormUtilities::getAllOptions($countries, 'country');
$categoryOptions = FormUtilities::getAllOptions($categories, 'name');
$subcategoryOptions = FormUtilities::getAllOptions($subcategories, 'category');
$languageOptions = FormUtilities::getAllOptions($languages, 'language');

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
                                ' . $countryOptions . '
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
