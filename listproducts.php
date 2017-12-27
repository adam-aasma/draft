<?php
require_once 'checkauth.php';
require_once 'views/admintemplate.php';
require_once 'data/ProductDescriptionRepository.php';
require_once 'service/ProductService.php';
require_once 'data/RepositoryFactory.php';
require_once 'library/FormUtilities.php';


$user = unserialize($_SESSION['user']);
$countries = $user->countries;
$marketHtml = FormUtilities::getAllOptions($countries, 'country');
$productService = new ProductService(RepositoryFactory::getInstance());
$languages = $productService->getCountryLanguages($countries);
$languageHtml = FormUtilities::getAllOptions($languages,'language');
$productlist = $productService->getList($countries[0], $languages[0]);
$Html = '';
foreach ($productlist as $product){
    $id = $product->productId;
    $name = $product->name;
    $description = $product->description;
    $images = "";
    foreach($product->images as $image) {
        $imageId = $image['id'];
        $imageName = $image['name'];
        $images .= "<img style='width: 10px; height: 10px;' alt='$imageName' src='getimage.php?id=$imageId' />";
    }
    $Html .= ' <tr> <td><a href="#">' . $id . '</a></td>
                <td><a href="#">' . $name . '</a></td>
                <td><a href="#">' . $description . '</a></td>
                <td>' . $images . '</td>
                <td>' . $product->itemDetails . '</td>
                <td><a href="#"></a></td></tr>';
}
$homepage = new adminTemplate();
$content = $homepage -> content = '<div class="desktop">
                                        <table class="producttable">
                                            <caption>
                                                <strong>products</strong>
                                            <select>
                                                ' . $marketHtml . '
                                            </select>
                                            <select>
                                                ' . $languageHtml . '
                                            </select>
                                            <button>Find</button>
                                            <nav>
                                                <ul class="productmenu">
                                                    <li><a>slider</a></li>
                                                    <li class="anchor"><a onclick="openDropDown()"  class="dropzone">edit</a>
                                                        <ul id="dropdown" class="dropdown-content">
                                                            <li><a>edit productinfo</a></li>
                                                            <li><a>add market</a></li>
                                                            <li><a>add language</a></li>
                                                            <li><a>add features</a></li>
                                                        </ul>
                                                    </li>
                                                    <li><a>present</a></li>
                                                    <li><a>delete</a></li>
                                                </ul>
                                            </nav>
                                            </caption>
                                            <colgroup>
                                                <col class="col-id">
                                                <col class="col-market">
                                                <col class="col-language">
                                                <col class="col-name">
                                                <col class="col-description>"
                                                <col class="col-pictures">
                                                <col class="col-itemdetails">
                                                <col class="col-statistics">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th scope="col">product id</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">description</th>
                                                    <th scope="col">pictures</th>
                                                    <th scope="col">item details</th>
                                                    <th scope="col">statitstics</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            ' . $Html . '
                                            </tbody>
                                    </table>
                                </div>';
$homepage -> Display();


?>

