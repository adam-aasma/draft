<?php
use Walltwisters\lib\repository\RepositoryFactory;
use Walltwisters\lib\service\ProductService;
use Walltwisters\lib\service\SectionService;
use Walltwisters\lib\utilities\FormUtilities;


$titel = 'listslides';
$keywordContent = '';
require_once 'adminpageheaderlogic.php';
$sectionservice = new SectionService(RepositoryFactory::getInstance());
require_once 'adminpageheader.php';
$countries = $user->countries;
$languages = $sectionservice->getCountryLanguages($countries);

$marketHtml = FormUtilities::getAllOptions($countries, 'country');
$languageHtml = FormUtilities::getAllOptions($languages, 'language');;
$Html = '';
if (isset($_POST['submit'])){
    $country = Walltwisters\lib\model\Country::create($_POST['market'], '');
    $language = Walltwisters\lib\model\Language::create($_POST['language'], '');
    $sectionListRows = $sectionservice->getSectionListBy($country, $language);
} else {
    $sectionListRows = $sectionservice->getSectionListBy($countries[0], $languages[0]);
}

foreach ($sectionListRows as $sectionListRow){
    $numberOfProducts = count($sectionListRow->productIds);
    $Html .= " <tr>
                    <td><a href='addsection.php?sectionid=$sectionListRow->id'>$sectionListRow->id</a></td>
                    <td><span class='tablerow'>$sectionListRow->titel</span></td>
                    <td><div  class='tablerow'>$sectionListRow->salesLineHeader $sectionListRow->salesLineParagraph</div></td>
                    <td><div class='tabelrow productImages'> $sectionListRow->desktopImages</div></td>
                    <td><div class='tabelrow productImages'> $sectionListRow->mobileImage</div></td>
                    <td><div class='tablerow'>$numberOfProducts</div></td> 
                </tr>
                    ";
}
?>
<table class="admintables" id="">
        <caption>
            <div class="inline-flex">
                <h2>Sections</h2>
                <form action="listsections.php" method="post" enctype="multipart/form-data">
                    <select name="market">
                        <?=$marketHtml?>
                    </select>
                    <select name="language">
                        <?=$languageHtml?>
                    </select>
                    <button type="submit" name="countrylanguage">Get</button>
                </form>
            </div>
        </caption>
        <colgroup>
            <col class="col-id">
            <col class="col-name">
            <col class="col-description">
            <col class="col-pictures">
            <col class="col-actions">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Titel</th>
                <th scope="col">Salesline</th>
                <th scope="col">Desktop</th>
                <th scope="col">mobile</th>
                <th scope="col">Nr of products</th>
                <th scopt="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?=$Html?>
        </tbody>
</table>
<?php $script = '';
require_once 'adminpagefooter.php'; ?>
