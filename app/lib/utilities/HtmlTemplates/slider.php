<?php
use \Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\SliderService;
use Walltwisters\utilities\FormUtilities;
$keywordContent = '';
$title = '';
$css = 'css/addsection2.css';

$sliderService = new SliderService(RepositoryFactory::getInstance());
$countries = $user->countries;
$countrylanguages = $sliderService->getCountryLanguages2($user->countries);
?>
<link href="css/addslider.css" type="text/css" rel="stylesheet">
<div id="slider" class=" upperwrapper center">
    <figure class="center">
        <img src="/img/slider1.jpg"/>
        <div id="salesmessage" class="gradient">
            <h2>titel</h2>
            <p>
            salesmessage
            </p>
            <h3> / 35 €</h3>
            <div class="button center"> <a href="#">vaata lähemalt</a> </div>
        </div>
    </figure>

    
</div>
<div class="lowerwrapper">
    <fieldset id="picture">
        <legend>picture</legend>
            <label for="adding-picture1">Select image:</label>
            <input type="file" name="sliderimage" id="adding-picture1">
    </fieldset>
    <fieldset id="copy">
        <legend>copy</legend>
        <div id="language">
            <select>
                <option>language</option>
            </select>
        </div>
        <div>
            <label>title</label>
            <input type="text" id="addingTitle">
        </div>
        <div>
            <label>sline</label>
            <input type="text" id="addingTitle">
        </div>
        <div>
            <label>offer</label>
            <input type="text" id="addingTitle">
        </div>
       
    </fieldset>
    <fieldset>
        <legend>pointer</legend>
        <div id="market">
            <select class="right"><?= FormUtilities::getAllOptions($countries, 'country', $checkedIds = [], $class ='') ?></select>
        </div>
        <div>
            
            <label>product</label>
            <input type="radio"/>
            <label>section</label>
            <input type="radio"/>
        </div>
        <select>
            <option>pointer</option>
        </select>
    </fieldset>
    
</div>
<script>
    var languagesForCountry = <?= json_encode($countrylanguages)  ?>
</script>
<script src="js/page/addslide.js" type="text/javascript"></script>