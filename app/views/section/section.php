<?php
require_once __DIR__ . '/../shared/adminpageheader.php';
require_once __DIR__ . '/../shared/templates/imagethumbnail.html';
?>
<style>
    @import url("/css/pages/editsectionstyles.css");
</style>
<div id="lowerwrapper">
    <div id="currently" >
        <span>nr.products: <i>0</i></span>
        <span>market: estonia</span>
        <span>languages: estonian, russian</span>
        <span>desktop: yes</span>
        <span>mobile: yes</span>
        <button>preview fullscreen</button>
    </div>
    <div id="preview">
        <?php require_once 'templates/section.php' ?>
    </div>
</div>
<div id="upperwrapper">
    <?php
    $legend = 'section Images';
    require_once __DIR__ . '/../shared/addimage.php';
    ?>
    <fieldset id="copy" class="border">
        <legend>selection copy</legend>
        <select id="languages">
            <option>language</option>
        </select>
        <p class="block solo">
            <label>Titel</label>
            <input type="text" data-item="titel"/>
        </p>
        <p class=" block solo">
            <label>SLine</label>
            <input type="text" data-item="salesline"/>
        </p>
        <p class=" block solo">
            <label>SLine</label>
            <input type="text" data-item="salesline2"/>
        <p class="block solo">
            <label class="block">section description</label>
            <textarea type="text" data-item="sectiondescription" class="block solo">
            </textarea>
        </p>
    </fieldset>
    <fieldset id="products" class="border">
        <legend>products</legend>
            <select id="markets">
            <?php foreach($user->countries as $country) : ?>
                <option data-marketId="<?=$country->id ?> "><?= $country->country ?></option>
            <?php endforeach; ?>
        </select>
        <label>included products</label>
        <div id="includedProducts" class="border">

        </div>
        <label>all products</label>
        <div id="allproducts" class=" border">

        </div>
    </fieldset>
</div>

<script src="/js/page/editsection.js" type="text/javascript"></script>
<script src="/js/page/localization.js" type="text/javascript"></script>
<script src="/js/general/section.js" type="text/javascript"></script>
<script>
    var languagesForCountry = <?= json_encode($countryLanguages) ?>
</script>
<?php /*if($loadSection) : */ ?>
 <!--   <script> var sectionId = --> <?php /* $sectionId; */ ?><!--</script> -->
<?php /*endif; */?>


<?php require_once __DIR__ . '/../shared/adminpagefooter.php'; ?>