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

// DROPDOWN MENU LIST PRODUCT //

function openDropDown() {
    var elem = document.getElementById("dropdown");
    elem.classList.toggle("show");
}


window.onclick = function(event) {
  if (!event.target.matches('.dropzone')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

/*
function myFunction() {
      var input, filter, table, tr, td, i;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }       
      }
    }
    */