<?php
use Walltwisters\repository\RepositoryFactory;
use Walltwisters\service\PrinterService;
use Walltwisters\utilities\FormUtilities;
use Walltwisters\utilities\Security;

$titel = 'wally';
$keywordContent = 'important stuff';

require_once 'adminpageheaderlogic.php';
require_once 'library/FormUtilities.php';
require_once 'library/security.php';


$countries = $user->countries;
$countryOptions = FormUtilities::getAllOptions($countries, 'country');
$printerservice = new PrinterService(RepositoryFactory::getInstance());

if (isset($_POST['submit'])){
    $printer = Walltwisters\model\Printer::create($_POST['printername'], $_POST['email'], $_POST['telephonenumber'], $_POST['contactperson'], $_POST['country'],$user->id);
    $printerid = $printerservice->addPrinter($printer);
    $items = [];
    $itemPrices = [];
    for ($i = 0; $i < count($_POST['paper']); $i++){
        $item = Walltwisters\model\Item2::create($_POST['paper'][$i], 
                               $_POST['technique'][$i],
                               $_POST['size'][$i]);
        $itemPrice = Walltwisters\model\ItemPrice::create($_POST['paperprice'][$i],
                                       $_POST['techniqueprice'][$i],
                                       $_POST['labourprice'][$i],
                                       $_POST['total_price'][$i]);
        $items[] = $item;
        $itemPrices[] = $itemPrice;
    }
    $ok = $printerservice->addItem($items, $printerid, $itemPrices);
}
require_once 'adminpageheader.php';
?>
<form action="addprinter.php" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>printer details</legend>
        <label for="adding-name">company name</label>
        <input type="text" name="printername" id="adding-name" />
        <label for="adding-email">email</label>
        <input type="text" name="email" id="adding-email" />
        <label for='adding-tephonenumber'>telephonenumber</label>
        <input type='text' name='telephonenumber' id='adding-tephonenumber'>
        <label for='adding-contactperson'>contact person</label>
        <input type='text' name='contactperson' id='adding-contact'>
        <label>country:</label>
                <select name='country' id="countries">
                    <?= $countryOptions ?>
                </select>
    </fieldset>
    <fieldset id='firstitem'>
        <legend>Item</legend>
        <table>
            <tr>
                <td>
                    <label for='adding-paper'>paper</label>
                    <input type='text' name='paper[]' id='adding-paper'>   <!-- more clear version like - item[][paper] - change later? -->
                </td>
                <td>
                    <label for='adding-size'>size</label>
                    <input type='text' name='size[]' id='adding-size'>
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                   <label for='adding-technique'>print technique</label>
                   <input type='text' name='technique[]' id='adding-technique'>
                </td>
            </tr>
            <tr>
                <td>
                    <label for='adding-paper-price'>paper price</label>
                    <input type='text' name='paperprice[]' id='adding-paper-price'>
                </td>
                <td>
                    <label for='adding-technique-price'>technique price</label>
                    <input type='text' name='techniqueprice[]' id='adding-technique-price'>
                </td>
            </tr>
            <tr>
                <td>
                    <label for='labour-price'>labour price</label>
                    <input type='text' name='labourprice[]' id='labour-price'>
                </td>
                <td>
                    <label for='total-price'>total price</label>
                    <input type='text' name='total price[]' id='total-price'>
                </td>
            </tr>
        </table>
     </fieldset>
     <fieldset>
        <legend>submit</legend>
        <button type='submit' name='submit'>submit</button>
        <input type="number" value="number" id="numberItem">
        <button id='additem'>add item</button>
    </fieldset>   
</form>

<?php $script = '/js/printer.js'; ?>
<?php require_once 'adminpagefooter.php'; ?>
