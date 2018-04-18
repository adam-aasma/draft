<?php
use \Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\SectionService;
use Walltwisters\service\ImageService;
use Walltwisters\utilities\Images;
use Walltwisters\utilities\FormUtilities;

$keywordContent = '';
$title = '';
$css = 'css/addsection2.css';
require_once 'adminpageheaderlogic.php';
require_once 'adminpageheader.php';
$sectionService =  new SectionService(RepositoryFactory::getInstance());
$imageService = new ImageService(RepositoryFactory::getInstance());
$countries = $user->countries;
$countrylanguages = $sectionService->getCountryLanguages2($user->countries);
$imagesCategories = $sectionService->getImageCategoriesBy('sectionImageCategories');

    

?>
<div id="upperwrapper">
    <fieldset id='pictures' class="border">
        <legend>product images</legend>
        <div class="pictureBar">
            <form method="POST" action="ajaxsectioncontroller.php" id="picture"></form>
            <div class="trio left">
                <input type="file" />
            </div>
            <div id="imagescategories" class="left radios" >
                <?= FormUtilities::getAllRadioOptions($imagesCategories,'category', 'category', [$imagesCategories[0]->id]);?>
            </div>
            <div class="right center saveImage">
                <button type="submit" id="submit" value="save" form="picture">Add</button>
            </div>
        </div>
        <div id="addedpictures">
            <!-- same solution as for edit product with creating delete element for added picture -->
        </div>
    </fieldset>
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
        </P>
        
    </fieldset>
    <fieldset id="products" class="border">
        <legend>products</legend>
        <select id="markets">
            <?= FormUtilities::getAllOptions($countries, 'country', $checkedIds = [], $class ='') ?>
            
        </select>
        <label>included products</label>
        <div id="includedProducts" class="border">
            
        </div>
        <label>all products</label>
        <div id="allproducts" class=" border">
            
        </div>
        
    </fieldset>
    
</div>
<div id="lowerwrapper">
    
    <div id="currently">
        <span>nr.products: <i>0</i></span>
        <span>market: estonia</span>
        <span>languages: estonian, russian</span>
        <span>desktop: yes</span>
        <span>mobile: yes</span>
        <button>preview fullscreen</button>
        
    </div>
    <div id="preview">
        <?php require_once 'app/lib/utilities/HtmlTemplates/section.php' ?>
        
    </div>
</div>
<script>
    var languagesForCountry = <?= json_encode($countrylanguages)  ?>
</script>
<script src="js/page/addsection.js" type="text/javascript"></script>

<?php require_once 'adminpagefooter.php' ?>