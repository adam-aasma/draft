var printer = null;

function addItemHtml(){
    var fieldset = document.getElementById('item0');
    
    if(!fieldset.querySelector('#nav')){
        var temp = document.getElementById('itemNavigation');
        var div = temp.content.cloneNode(true);
        
        fieldset.appendChild(div);
        
        var nav = fieldset.querySelector('#nav');
        nav.addEventListener('click', itemNavigation, false);
    }
    
    printer.addItem();
    var clone = fieldset.cloneNode(true);
    var newId ='item' + printer.currentItemIdx;
    clone.querySelector('LEGEND').textContent = 'item' + (printer.currentItemIdx + 1);
    var inputDivs = clone.querySelectorAll('DIV');
    for( let div of inputDivs) {
        if(div.getAttribute('id') === 'nav'){
            continue;
        }
        if(div.querySelector('INPUT').value){
            div.querySelector('INPUT').value = '';
        }
    }
    clone.setAttribute('id', newId);
    
    var nav = clone.querySelector('#nav');
    nav.addEventListener('click', itemNavigation, false);
    
    var parent = fieldset.parentNode;
    var bottom = document.getElementById('bottom');
    parent.insertBefore(clone, bottom);
    inputEventListener(newId, addItem);
    
    displayItem();
}

function itemNavigation(e){
    var id = e.target.getAttribute('id');
    var step = id === 'minus' ? -1 : 1;
    printer.changeCurrentItemIdx(step);
    displayItem();
}

function displayItem() {
    var items = document.querySelectorAll('.items');
    for( let item of items){
        if (item.getAttribute('id') === 'item' + printer.currentItemIdx) {
            item.classList.remove('hidden');
        } else if (!item.classList.contains('hidden')) {
            item.classList.add('hidden');
        }
        else {
            continue;
        }
    }
}

function addPrinter(e) {
    if(e.target.tagName === 'INPUT') {
        var property = '';
        var value = e.target.value;
        switch(e.target.getAttribute('id')){
            case 'companyName' :
                property = 'companyName';
                break;
            case 'email' : 
                property = 'email';
                break;
            case 'telephoneNumber' :
                property = 'telephoneNumber';
                break;
            case 'contactPerson' :
                property = 'contactPerson';
                break;
        }
        
        printer.addPrinter(property, value);
    }
}

function addItem(e){
    if(e.target.tagName === 'INPUT') {
        var property = '';
        var value = e.target.value;
        switch(e.target.getAttribute('id')){
            case 'material' :
                property = 'material';
                break;
            case 'size' :
                property = 'size';
                break;
            case 'technique' :
                property = 'technique';
                break;
            case 'materialPrice' :
                property = 'materialPrice';
                break;
            case 'techniquePrice' :
                property = 'techniquePrice';
                break;
            case 'labourPrice' :
                property = 'labourPrice';
                break;
            case 'totalPrice' :
                property = 'totalPrice';
                break;
        }
        printer.addToItem(property, value);
    }
}

 function setCountry() {
     var options = document.querySelectorAll('#countries OPTION');
     for ( let option of options){
         if(option.selected){
             printer.countryId = option.value;
         }
     }
 }




window.onload = function(){
    printer = new Printer();
    printer.addItem();
    displayItem();
    addEventListeners();
    setCountry();
}

function inputEventListener(id, callback) {
    var inputs = document.querySelectorAll('#' +id + ' INPUT');
    for( let input of inputs){
        input.addEventListener('blur', callback, false);
    }
}

function addEventListeners(){
    var el = document.getElementById('addItem');
    el.addEventListener('click', addItemHtml, false);
    inputEventListener('printer', addPrinter);
    inputEventListener('item0', addItem);
    
    var el1 = document.getElementById('countries');
    el1.addEventListener('change', setCountry, false);
   
    
}