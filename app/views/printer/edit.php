<?php
require_once __DIR__ . '/../shared/adminpageheader.php';
?>
<style>
    @import url("/css/pages/editprinterstyles.css");
</style>
    <fieldset id='printer'>
        <legend>printer details</legend>
        <div id="selectCountry">
            <select name='country' id="countries">
                <?= $countryOptions ?>
            </select>
        </div>
        <div >
            <label>company name</label>
            <input type="text" name="printername" id='companyName' />
        </div>
        <div >
            <label>email</label>
            <input type="text"  id='email' />
        </div>
        <div >
            <label>telephonenumber</label>
            <input type='text' id='telephoneNumber'>
        </div>
        <div >
            <label >contact person</label>
            <input type='text' id='contactPerson'>
        </div>
    </fieldset>
    <fieldset id='item0' class="hidden items">
        <legend>Item1</legend>
        <div>
            <label>material</label>
            <input type='text' id='material'>   
        </div>
        <div>
            <label >size</label>
            <input type='text' id='size'>
        </div>
        <div>
           <label >print technique</label>
           <input type='text' id='technique'>
        </div>
        <div>
            <label >material price</label>
            <input type='text' id='materialPrice'>
        </div>
        <div>
            <label>technique price</label>
            <input type='text' id='techniquePrice'>
        </div>
        <div>
            <label>labour price</label>
            <input type='text' id='labourPrice'>
        </div>
        <div>
            <label>total price</label>
            <input type='text' id='totalPrice'>
        </div>
        <template id="itemNavigation">
            <div id="nav">
                <span id="minus"> < </span><span id="plus"> > </span>
            </div>
        </template>
     </fieldset>
     <fieldset id="bottom">
        <legend>submit</legend>
         <span id="addItem">addItem</span>
        <button type='submit' name='submit'>submit</button>
    </fieldset>   
<script type="text/javascript" src="/js/general/printer.js"></script>
<script type="text/javascript" src="/js/page/printer.js"></script>

<?php
require_once __DIR__ . '/../shared/adminpagefooter.php';

