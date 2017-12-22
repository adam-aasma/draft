/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var slideIndex = 1;
showDivs(slideIndex);

function initPage() {
    for (var i = 0; i < initFunctionTable.length; i++) {
        initFunctionTable[i]();
    }
}

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("slider");
    if (!x.length) return;
    if (n > x.length) {slideIndex = 1} 
    if (n < 1) {slideIndex = x.length} ;
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
    }
    
    x[slideIndex-1].style.display = "flex";
    if (slideIndex !== 0){
        x[slideIndex].style.border = "none";
    }
}
function selectCountryLanguage(){
    var country = document.getElementById("countries").value;
    var language = document.getElementById("languages").value;
    showProductInfoForms(country, language);
}


function showProductInfoForms(country, language){
    var i;
    var x = document.getElementsByClassName("productinfo");
    for ( i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    document.getElementById("productinfo_" + country + "_" + language).style.display = "flex";
}

