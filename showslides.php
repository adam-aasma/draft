<?php

require_once 'data/productRepository.php';
$keywordContent= '';
require_once 'adminpageheaderlogic.php';
require_once 'adminpageheader.php';
$salesmessage = 'add a sales line';
$titel = 'titel';
$imagesHtml = '';
if (!$_GET['slider']) {
    echo 'no slider selected' ;
} else {
    $productrep = new Walltwisters\data\ProductRepository;
    $sliderid = $_GET['slider'];
    $slider = $productrep->getShowslider($sliderid);
    $imageId = $slider['image_id'];
    $salesmessage = $slider['sales_message'];
    $titel = $slider['title'];
    $imagesHtml = "<img class='slider' src='getimage.php?id=$imageId' alt='sliderimage'>\n";
}
?>
<section>
    <?= $imagesHtml ?>
    <div class="gradient">
        <h2><?=$titel ?></h2>
        <p>
        <?=$salesmessage ?>
        </p>
       <h3> / 35 €</h3>
        <div class="button center"> <a href="#">vaata lähemalt</a> </div>
    </div>
</section>';
<?php $script ='/js/showlider.js' ?>
<?php require_once 'adminpagefooter.php'; ?>

