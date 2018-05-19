<?php
use Walltwisters\lib\utilities\FormUtilities;


require_once __DIR__ . '/../shared/adminpageheader.php';

$json_imageCategories = [];
foreach($imageCategories as $category){
    $json_imageCategories[$category->id] = $category->category;
}
?>
<style>
    @import url("/css/pages/editproductstyles.css");
</style>
    <div class="row block">
        <?php $legend = 'product images'; require_once __DIR__ . '/../shared/addimage.php'; ?>
        <fieldset id='general' class="border left">
            <legend>general</legend>
            <div class="wrapper">
                <label>format:</label>
                <select name="format">
                    <?= FormUtilities::getAllOptions($productService->getAllFormats(), 'format') ?>
                </select>
            </div>
            <div class="wrapper">
                <label>Artist</label>
                <input type="text" name="artist"/>
            </div>
        </fieldset>
        <fieldset id='currently' class="border left">
            <legend>Summary</legend>
            <article id="figureswrapper" >
                <h2>pictures</h2>
                <template id="thumbNailTemplate">
                    <figure>
                        <img/>
                        <figcaption>
                            <strong>category: </strong><i></i></br>
                            <strong>name: </strong><i></i></br>
                            <strong>size: </strong><i></i></br>
                        </figcaption>
                    </figure>
                </template>
            </article>
            <article id='sumLanguagesWrapper' >
                <div class='row'>
                    <h2 class='left'>Markets</h2> 
                    <h2 class='right'>languages</h2>
                </div>
                <template id='summarymarketlanguages'>
                    <div class='row'>
                        <span class='left'></span>
                    </div>
                </template>
            </article>
            <article id="sumItemWrapper">
                <div class='row'>
                    <h2 class="left">markets</h2>
                    <h2 class="right">items</h2>
                </div>
                <div class='row'>
                    <span class='left trio'>estonia</span>
                    <div class='bold ali-right'>moahawk</div>
                    <div class='ali-right'>70x50</div>
                    <div class='ali-right' >70x50</div>
                    <div class='ali-right'>70x50</div>
                    <div class='bold ali-right'>fager</div>
                    <div class='ali-right' >70x50</div>
                    <div class='ali-right'>70x50</div>
                </div>
                <div class='row'>
                    <span class='left trio'>sweden</span>
                    <div class='bold ali-right'>moahawk</span>
                    <div class='ali-right'>70x50</div>
                    <div class='ali-right' >70x50</div>
                    <div class='ali-right'>70x50</div>
                </div>
            </article>
            <button id="productPreview">preview</button>
        </fieldset>
    </div>
    <div class="row block">
        <div id="underStore1" class='left'>
            <div class="row">
                <template id="languageTemplate">
                    <span class="right"></span>
                </template>
            </div>
            <fieldset id='productInfo' class="border row">
                <legend>product info</legend>
                <template id='producInfoTemplate'>
                    <div class="left solo">
                        <label>name</label>
                        <input type='text' id='productName'/>
                    </div>
                    <div class="left block duo">
                        <label>description</label>
                        <textarea id='productDescription'></textarea>
                    </div>
                    <div class=" right block duo">
                        <label>tags</label>
                        <textarea id='productTags'></textarea>
                    </div>
                </template>
        </div>
        <div id="underStore2" class='left'>
            <div class="row">
                <?php foreach($countries as $country) : ?>
                    <span class="right"
                          data-marketId="<?= $country->id ?>"
                          data-marketName="<?= $country->country ?>"
                    >
                    <?= $country->country ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <fieldset id='itemDetails'class="border">
                <legend>Items</legend>
                <template id="itemTemplate">
                    <article>
                        <div>
                            <label>material</label>
                            <input type="checkbox" />
                        </div>
                            <template id="itemSizesTemplate">
                                <div class="JS_sizes">
                                   
                                    <select multiple>
                                        
                                    </select>
                                </div>
                            </template>
                     </article>
                </template>
              
            </fieldset>   
        </div>
    </div>
<script>
    var countryLanguages = <?= json_encode($countrylanguages) ?>;
    var countryItems = <?= json_encode($countryItems) ?>;
    var productId = <?= $productId?>; 
    var sizesObjArray = <?= $productService->sizestojson() ?>;
    var materialNamesObjArray = <?= $productService->materialstojson() ?>;
    var imageCategoriesNames = <?= json_encode($json_imageCategories) ?>
</script>
<script src="/js/general/product.js" type="text/javascript"></script>
<script src="/js/general/template.js" type="text/javascript"></script>
<script src="/js/general/addimage.js" type="text/javascript"></script>
<script src="/js/page/editproduct.js" type="text/javascript"></script>
<script src="/js/page/productshowroom.js" type="text/javascript"></script>


<?php
require_once 'productshowroom.php'; 
?>
<?php
require_once __DIR__ . '/../shared/adminpagefooter.php';

?>
