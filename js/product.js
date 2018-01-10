
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

window.onload = selectCountryLanguage(), hideDisplayOptions();
var elCountry = document.getElementById('countries');
var elLanguage = document.getElementById('languages');
elCountry.addEventListener('change', selectCountryLanguage, false);
elLanguage.addEventListener('change', selectCountryLanguage, false);

function hideDisplayOptions(e){
    if(e){
        var target = e.target.nextElementSibling.innerHTML;
    }
    var els =  document.getElementsByClassName('selectOptions');
    var labels = document.querySelectorAll('.selectOptions label');
 
    if(e){
        for ( i = 0; i < labels.length; i++) {
            if ( labels[i].innerHTML.indexOf(target) > 0 && !labels[i].parentElement.classList.contains('showOptions')){
                labels[i].parentElement.classList.add('showOptions');
            }
            else if(labels[i].innerHTML.indexOf(target) > 0) {
                labels[i].parentElement.classList.remove('showOptions');
            }
        }
    }
}

var elcheckboxes =  document.getElementsByClassName('checkbox');
for (var x in elcheckboxes){
    elcheckboxes[x].onchange = function(e) {hideDisplayOptions(e);};
}


function addMorePictures(){
    var index = document.getElementsByClassName('index').length;
    var loopIndex = document.getElementsByClassName('indexValue').length;
    var values = [];
    for(i=0; i < loopIndex; i++){
        value = document.getElementsByClassName('indexValue')[i].value;
        if (value){
            values.push(value);
        }
    }
    var Html =' <p class="checkbox index">\n\
                    <input type="radio" name="' + index + '" value="' + values[0] + '">\n\
                    <label>product</label>\n\
                    <input type="radio" name="' + index + '" value="' + values[1] + '">\n\
                    <label>productinterior</label>\n\
                </p>\n\
                <input type="file" name="pictype' + index + '[]">';
                
    var anchor= document.getElementById('productImages');
    anchor.insertAdjacentHTML('beforeend',Html);
}

var elPlusImages = document.getElementById('addimage');
elPlusImages.addEventListener('click', function(e) {addMorePictures(e); }, false);