
/* function addDeleteMarket(e){
    var elTarget = e.target;
    var count = document.getElementsByClassName('addingGenerals').length;
    
    if ( elTarget.className === 'addIcon' ){
       var sections = document.getElementsByClassName('addingGenerals');
       var lastSection = sections[sections.length - 1];
       var clone = lastSection.cloneNode(true);
       clone.id = 'market_' + count;
       insertAfter(clone, lastSection);
       window.setTimeout(function() {
            setValueCountryLanguageForSection('market_' + count);
        }, 10);
    } else if(elTarget.className === 'deleteIcon' && count > 1){
        var section = elTarget.parentNode.parentNode;
        section.remove();
    }
   
}

function insertAfter(el, referenceNode) {
    referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);
}

function setValueCountryLanguageForSection(sectionId) {
    var countryId = getSelectedCountry(sectionId);
    var languageId = getSelectedLanguage(sectionId);
    var inputs = document.querySelectorAll('#' + sectionId + ' input[type="text"]');
    for (var i = 0; i < inputs.length; i++) {
        var inp = inputs[i];
        if (inp.name.indexOf('title') >= 0) {
            setCountryAndLanguageInName(inp, 'title', countryId, languageId);
        } else if (inp.name.indexOf('saleslineheader') >= 0) {
            setCountryAndLanguageInName(inp, 'saleslineheader', countryId, languageId);
        } else if (inp.name.indexOf('saleslineparagraph') >= 0) {
            setCountryAndLanguageInName(inp, 'saleslineparagraph', countryId, languageId);
        }
    }
}

function setValueCountryLanguage(e){
    var elTarget = e.target;
    if (elTarget.name === 'language' || elTarget.name === 'country'){
        var fsId = elTarget.parentElement.parentElement.id;
        setValueCountryLanguageForSection(fsId);
    }
}

function setCountryAndLanguageInName(inputElem, name, countryId, languageId) {
    inputElem.name = 'section_info[' + countryId + '][' + languageId + '][' + name + ']'; 
}

function getSelectedCountry(idElem) {
    var opt = document.querySelector('#' + idElem + ' select[name="country"] option:checked');
    return opt.value;
}

function getSelectedLanguage(idElem) {
    var opt = document.querySelector('#' + idElem + ' select[name="language"] option:checked');
    return opt.value;
}*/

function deleteFromSection(e){
    moveElementBetweenSections(e, 'sectionAllProducts', '');
}

function findChildrenByNodeName(el, nodeName){
    var children = [];
    for( var i in el.children){
        var child = el.children[i];
        if(child.nodeName == nodeName){
            children.push(child);
        }
    }
    return children;
}

function addProductToSection(e){
    moveElementBetweenSections(e, 'sectionProducts', 'products[]');
}

function moveElementBetweenSections(e, targetId, inputname) {
    var elTarget = e.target;
    var parent = elTarget.parentElement
    var clone = parent.cloneNode(true);
    var inputs = findChildrenByNodeName(clone, 'INPUT');
    parent.remove();
    var newHome = document.getElementById(targetId);
    newHome.appendChild(clone);
    inputs[0].name = inputname;
}

function addEventListener(){
     var elAllProducts = document.getElementById('sectionAllProducts');
     elAllProducts.addEventListener('click', addProductToSection, false);
      var elAllProducts = document.getElementById('sectionProducts');
     elAllProducts.addEventListener('click', deleteFromSection, false);
}
 
window.onload = function() {
    addEventListener();
};


