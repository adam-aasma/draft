
function selectCountryLanguage(){
    var country = document.getElementById("countries").value;
    var language = document.getElementById("languages").value;
    showProductInfoForms(country, language);
}

function setCountryLanguages() {
    var country = document.getElementById("countries").value;
    var languages = countryLanguages[country];
    var language = document.getElementById("languages");
    var firstChild = language.firstChild;
    while (firstChild) {
        language.removeChild(firstChild);
        firstChild = language.firstChild;
    }
    for (var i in languages) {
        var opt = document.createElement('OPTION');
        opt.value = languages[i].languageId;
        var text = document.createTextNode(languages[i].languageName);
        opt.appendChild(text);
        language.appendChild(opt);
    }
}

function showProductInfoForms(country, language){
    var i;
    var x = document.getElementsByClassName("productinfo");
    for ( i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    document.getElementById("productinfo_" + country + "_" + language).style.display = "flex";
}

var elCountry = document.getElementById('countries');
var elLanguage = document.getElementById('languages');
elCountry.addEventListener('change', selectCountryLanguage, false);
elLanguage.addEventListener('change', selectCountryLanguage, false);

function hideDisplayOptions(e){
    var els =  document.getElementsByClassName('selectOptions');
    var labels = document.querySelectorAll('.selectOptions label');
 
    
    if(e){
        var target = e.target.nextElementSibling.innerHTML;
    
        for ( i = 0; i < labels.length; i++) {
            if ( labels[i].innerHTML.indexOf(target) > 0 && !labels[i].parentElement.classList.contains('showOptions')){
                labels[i].parentElement.classList.add('showOptions');
            }
            else if(labels[i].innerHTML.indexOf(target) > 0) {
                labels[i].parentElement.classList.remove('showOptions');
            }
        }
    }   else {
        var elInputs = document.querySelectorAll('.control input');
        for ( var i = 0 ; i < elInputs.length; i++){
            var elCheckIfTrue = elInputs[i].checked;
            if (elCheckIfTrue){
                for( var j = 0; j < labels.length; j++){
                    if ( labels[j].innerHTML.indexOf(elInputs[i].nextSibling.innerHTML) > 0 )
                    {
                    labels[j].parentElement.classList.add('showOptions');
                    }
                }
            }
        }
    }
    
}

var elcheckboxes =  document.getElementsByClassName('checkbox');
for (var x in elcheckboxes){
    elcheckboxes[x].onchange = function(e) {hideDisplayOptions(e);};
}


function addMorePictures(e){
    var elTarget = e.target;
    
    if(elTarget.id === 'addimage'){
        var index = document.getElementsByClassName('index').length;
        var loopIndex = document.getElementsByClassName('indexValue').length;
        var values = [];
        for(i=0; i < loopIndex; i++){
            value = document.getElementsByClassName('indexValue')[i].value;
            if (value){
                values.push(value);
            }
        }
        var Html =' <div> \n\
                        <p class="checkbox index">\n\
                            <input type="radio" name="category[' + index + ']" value="' + values[0] + '">\n\
                            <label>product</label>\n\
                            <input type="radio" name="category[' + index + ']" value="' + values[1] + '">\n\
                            <label>productinterior</label>\n\
                        </p>\n\
                        <input type="file" name="pictype' + index + '[]">\n\
                        <span><a class="deleteimage""´>delete</a></span>\n\
                    </div>';
        var anchor= document.getElementById('productImages');
        anchor.insertAdjacentHTML('beforeend',Html);
        
    
    } else if (elTarget.className === 'deleteimage'){
        elParent = document.getElementById("productImages");
        elChild = elParent.lastChild;
        elParent.removeChild(elChild);
        
        
        
    }
    
}

function addEventListener(){
     var el = document.getElementById("productImages");
     el.removeEventListener('click', addMorePictures);
     el.addEventListener('click', addMorePictures, false);
     
     var countryEl = document.getElementById('countries');
     countryEl.addEventListener('change', setCountryLanguages);
}
 
window.onload = function() {
    setCountryLanguages();
    selectCountryLanguage();
    hideDisplayOptions();
    addEventListener();
};

