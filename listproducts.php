<?php
use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\ProductService;
use Walltwisters\utilities\FormUtilities;





$keywordContent = 'very important stuff';
require_once 'adminpageheaderlogic.php';

$countries = $user->countries;
$marketHtml = FormUtilities::getAllOptions($countries, 'country');
$productService = new ProductService(RepositoryFactory::getInstance());
$languages = $productService->getCountryLanguages($countries);
$languageHtml = FormUtilities::getAllOptions($languages,'language');

if (isset($_POST['countrylanguage'])){
    $country = Walltwisters\model\Country::create($_POST['market'],'');
    $language = Walltwisters\model\Language::create($_POST['language'], '');
    $productlist = $productService->getList($country, $language);
}
else {
    $productlist = $productService->getList($countries[0], $languages[0]);
}
$Html = '';
foreach ($productlist as $product){
    $id = $product->productId;
    $name = $product->name;
    $description = $product->description;
    $itemDetails = $product->itemDetails;
    $images = "";
    foreach($product->images as $image) {
        $imageId = $image['id'];
        $imageName = $image['name'];
        $images .= "<img alt='$imageName' src='getimage.php?id=$imageId' />";
    }
    //TODO add itemdetails
    $Html .= ' <tr>
                    <td><a class="tablerow" href="editproduct.php?id=' . $id . '">' . $id . '</a></td>
                    <td><span class="tablerow">' . $name . '</span></td>
                    <td><div  class="tablerow productDescription" >' . $description . '</div></td>
                    <td><div class="tabelrow productImages">' . $images . '</div></td>
                    <td><div  class="tablerow productItems">' . $itemDetails . '</div></td> 
                    <td>
                        <div class="tablerow previewsliderlinks">
                            <a href="productshowroom.php?id=' . $id . '">preview</a>
                            <a href="addslide.php?id=' . $id . '">addslide</a>
                        </div>
                    </td>
                    <td><div class="tablerow productCheckBoxes""><input type="checkbox" name="products[]" value="' . $id . '" form="adding-section" /></div></td>
                </tr>';
}

require_once 'adminpageheader.php';
?>

    <table class="admintables" id="producttable">
        <caption>
            <div class="inline-flex"/>
                <h2>products</h2>
                <form action="listproducts.php" method="post" enctype="multipart/form-data" id="producttableform">
                    <select name="market">
                        <?=$marketHtml?>
                    </select>
                    <select name="language">
                        <?=$languageHtml?>
                    </select>
                    <button type="submit" name="countrylanguage">Get</button>
                </form>
            </div>
            <div class="inline-flex end">
                <div id="searchProduct">
                    <input type="text" id="searchProductInput" placeholder="Search for names..">
                    <button type="submit" name="submit">Find</button>
                </div>
                <nav id="productlistmenu">
                    <ul>
                        <li><a href="editproduct.php">new product</a></li>
                        <li><a href="listslides.php">Slides</a></li>
                        <li><a href="listsections.php">Sections</a></li>
                        <li><form action="addsection.php" method="post" enctype="multipart/form-data" id="adding-section"><button id="createNewSection">new section</button></form></li>
                    </ul>
                </nav>
            </div>
        </caption>
        <colgroup>
            <col class="col-id">
            <col class="col-name">
            <col class="col-description">
            <col class="col-pictures">
            <col class="col-itemdetails">
            <col class="col-statistics">
            <col class="col-section">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Name</th>
                <th scope="col">description</th>
                <th scope="col">pictures</th>
                <th scope="col">item details</th>
                <th scope="col">additonal</th>
                <th scopt="col">pick section:</th>
            </tr>
        </thead>
        <tbody>
        <?=$Html?>
        </tbody>
</table>
                      
<?php
require_once 'adminpagefooter.php';


?>

