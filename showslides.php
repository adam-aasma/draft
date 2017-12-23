<?php
require_once 'checkauth.php';
require_once 'views/admintemplate.php';
$productrep = new ProductRepository;
$sliderid = $_GET['slider'];
$slider = $productrep->getShowslider($sliderid);
$imageId = $slider['image_id'];
$salesmessage = $slider['sales_message'];
$titel = $slider['titel'];
$imagesHtml = "<img class='slider' src='getimage.php?id=$imageId'>\n";

$homepage = new adminTemplate();
$titel = $homepage -> title = 'wally';
$content = $homepage -> content = '<section class="desktop">
                ' . $imagesHtml . '
                <div class="gradient">
                    <h2>' . $titel . '</h2>
                    <p>
                    ' . $salesmessage . '
                    </p>
                   <h3> / 35 €</h3>
                    <div class="button center"> <a href="#">vaata lähemalt</a> </div>
                </div>
            </section>';

$homepage -> Display();

?>