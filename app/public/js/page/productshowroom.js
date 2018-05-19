/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
function showRoomProduct(product) {
    this.imageIds = [];
    this.productInfo = product.getProductInfo(product.currentLanguageId);
    this.sizes = '';
    
        for (let imageId of product.imageIds){
            this.imageIds.push(imageId);
        }
        this.description = productInfo.description;
        this.name = productInfo.name;
        
        var market = product.getMarket();
        var sizes = '';
        if (market.materials.length) {
        for(let material of market.materials) {
            for (let size of material.sizeIds) {
               this.sizes += '<span class="showroomsizes">' +sizesObjArray[size] + '' + materialNamesObjArray[material.materialId] + '</span>';
            }
        }
    }
    
    
    return this;
}
 */   

function accordionMenu(e){
    var elTarget = e.target;
    if(!elTarget.tagName ==='SPAN') { return; };
    var spans = document.querySelectorAll('#accordionmenu SPAN');
    notShowNonSelectedAccordion();
    for (let span of spans){
        if (elTarget.getAttribute('id') === span.getAttribute('id')) {
            span.classList.toggle('nobottomborder');
            showContentProductShowRoom(elTarget.getAttribute('id')).classList.toggle('displayblock');
            continue;
        }
        if(span.classList.contains('nobottomborder')){
            span.classList.remove('nobottomborder');
        }
    }
}

function notShowNonSelectedAccordion(){
    var paras = document.querySelectorAll('#accordionmenu P');
    for ( let para of paras) {
        if( para.classList.contains('displayblock')){
            para.classList.remove('displayblock');
        }
    }
}

function showContentProductShowRoom(id){
    switch (id) {
        case 'showroomdescription':
            var el = document.querySelector('#accordionmenu #productdescription');
            break;
        case 'showroomsizes' :
            var el = document.querySelector('#accordionmenu #productsizes ');
            break;
        case 'showroomdelivery' :
            var el = document.querySelector('#accordionmenu #productdelivery');
            break;
    }
    
    return el;
}
function addShowRoomEventListener(){
    var el = document.getElementById('accordionmenu');
    el.addEventListener('click', accordionMenu, false);
}

var slideIndex = 1;


function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var x = document.getElementsByClassName("JS_slider");
    if (!x.length) return;
    if (n > x.length) {slideIndex = 1} 
    if (n < 1) {slideIndex = x.length} ;
    for (let i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
    }
    
    x[slideIndex-1].style.display = "flex";
    if (slideIndex !== 0){
        x[slideIndex].style.border = "none";
    }
}