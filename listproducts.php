<?php
require_once 'checkauth.php';
require_once 'views/admintemplate.php';

$user = unserialize($_SESSION['user']);
$countries = $user->countries;
$productrep = new productRepository();
$productlist = $productrep->getProductList($countries);
$Html = '';
foreach ($productlist as $product){
    $id = $product['id'];
    $name = $product['name'];
    $countryid = $product['countryid'];
    $languageid = $product['languageid'];
    $description = $product['description'];
    $Html .= ' <tr> <td><a href="#">' . $id . '</a></td>
                <td><a href="#">' . $countryid . '</a></td>
                <td><a href="#">' . $languageid . '</a></td>
                <td><a href="#">' . $name . '</a></td>
                <td><a href="#">' . $description . '</a></td>
                <td><a href="#"></a></td>
                <td><a href="#"></a></td>
                <td><a href="#"></a></td></tr>';
}
$homepage = new adminTemplate();
$content = $homepage -> content = '<div class="desktop">
                                        <table class="producttable">
                                            <caption>
                                                <strong>products</strong>
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
                                                    <th scope="col">Market</th>
                                                    <th scope="col">language</th>
                                                    <th scope="col">name</th>
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

