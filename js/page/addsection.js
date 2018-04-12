/*
 * will perobably delete tyhis function
 * dirty solution of  hiding the excess section preview
 * @returns {undefined}
 */
function deleteLowerWrapperFromSectionDiv(){
    var div = document.querySelector('#section #lowerwrapper');
    div.style.display = 'none';
} 
var section = new Section();

function getSelectedLanguageId(){
    var languages = document.querySelectorAll('#languages OPTION');
    for (let language of languages){
        if (language.selected){ 
        return language.getAttribute('data-language-id');
        };
    }
}

/*
 * event fired in 2 scenarios, change language in copy
 * or change a single line in copy
 * @param {type} e
 *updates the copy
 */

function getCopy(e){
    var elTarget = e.target;
    var dataItem = elTarget.getAttribute('data-item');
    var sectionCopy = section.getSectionCopy(getSelectedLanguageId());
    
    if(elTarget.tagName === 'SELECT'){
        var copyDiv = document.getElementById('copy');
        var inputs = copyDiv.querySelectorAll('INPUT');
        var textarea = copyDiv.querySelector('TEXTAREA');
        textarea.value = sectionCopy.description;
        for( let input of inputs){
            dataItem = input.getAttribute('data-item');
            switch(dataItem) {
                case 'titel':
                    input.value = sectionCopy.titel;
                    break;
                case 'salesline' :
                    input.value = sectionCopy.sline;
                    break;
                case 'salesline2' :
                    input.value = sectionCopy.sline2;
                    break;
            }
        }
    } else {
        var text = {
        line : '',
        text : ''
        }
        switch (dataItem) {
            case 'titel' :
                text.line = 'titel';
                text.text = elTarget.value;
                sectionCopy.titel = text.text;
                break;
            case 'salesline' :
                text.line = 'salesline';
                text.text = elTarget.value;
                sectionCopy.sline = text.text;
                break;
            case 'salesline2' :
                text.line = 'salesline2';
                text.text = e.target.value;
                sectionCopy.sline2 = text.text;
                break;
            case 'sectiondescription' :
                text.line = 'sectiondescription';
                text.text = e.target.value;
                sectionCopy.description = text.text;
                break;
        }
        return updatePreviewCopy(text);
    }
    return updatePreviewLanguage(sectionCopy);
    
    
}

/* called upon from getCopy
 *  fired in event of changing language in copy
 * @param {type} sectionCopy
 * @returns {delete or set new content for preview }
 */
function updatePreviewLanguage(sectionCopy) {
    var contentDiv = document.getElementById('content');
    var h2 = contentDiv.querySelector('H2');
    h2.textContent = sectionCopy.sline;
    var h3 = contentDiv.querySelector('H3');
    h3.textContent = sectionCopy.sline2;
    var titel = contentDiv.querySelector('H1');
    titel.textContent = sectionCopy.titel;
    
    
}
/* called upon from getCopy
 * fired in event of changing an input line in copy field
 * @param {type} text
 * @returns {updates the concerned line}
 */
function updatePreviewCopy(text){
    var h2s = document.querySelectorAll('#section H2');
    var h3 = document.querySelector('#section h3');
    var titelEl = document.querySelector('#section H1');
    if( titelEl.getAttribute('data-item') === text.line){
        titelEl.textContent = text.text;
        return;
    }
    if( h3.getAttribute('data-item') === text.line){
        h3.textContent = text.text;
        return;
    }
    for ( let h2 of h2s){
        if(h2.getAttribute('data-item') === text.line) {
            h2.textContent = text.text;
        }
    }
    
}

function setLanguagesForMarket(){
    var markets = document.querySelectorAll('#markets option');
    var languageSelect = document.getElementById('languages');
    for (let market of markets) {
        if(market.selected){
            languageSelect.innerHTML = '';
            for( language of languagesForCountry[market.value] ){
                var option = document.createElement('OPTION');
                option.setAttribute('data-language-id', language.languageId)
                option.textContent = language.languageName;
                languageSelect.appendChild(option);
            }
            
        }
    }
}

function createImageSpan(imageId, imageName) {
    var div = document.getElementById('addedpictures');
    var para = document.createElement('P');
    para.setAttribute('class', 'row-float');
    var span = document.createElement('SPAN');
    span.setAttribute('class','duo');
    var but = document.createElement('BUTTON');
    but.setAttribute('data-image-id', imageId);
    span.innerText = imageName;
    but.innerText = 'delete';
    para.appendChild(span);
    para.appendChild(but);
    div.appendChild(para);
    
    but.addEventListener('click', function() { 
        section.deleteImage(imageId, function() {
            wQuery(para).remove();
        }); 
    });
    
}

function setImageToPreview(imageId, categoryId){
    var imgEl = document.createElement('IMG');
    imgEl.setAttribute('src', 'getImage.php?id=' + imageId);
    var els = document.querySelectorAll('#imagescategories INPUT');
    for ( let el of els) {
        if(parseInt(el.value) === parseInt(categoryId)){
            var categoryEl = el;
        }
    }
    switch(categoryEl.nextSibling.textContent) {
        case  'sectionsmall' :
            var div = document.querySelector('#leftsection');
            var oldImg = div.querySelector('img');
            oldImg.setAttribute('src', 'getImage.php?id=' + imageId);
            break;
        case 'sectionbig' :
            var div = document.querySelector('#rightsection IMG');
            div.setAttribute('src', 'getImage.php?id=' + imageId);
        case 'sectionmobile' :
            /*not implemented */
            return;
    }
    /*if(div){
    div.appendChild(imgEl);
    } */
    
    
}



window.onload= function() {
    /*deleteLowerWrapperFromSectionDiv(); */
    onSectionLoad();
    addEventListeners();
    setLanguagesForMarket();
}

function addEventListeners() {
    var el = document.getElementById('copy');
    var textarea = el.querySelector('TEXTAREA');
    textarea.addEventListener('blur', getCopy, false);
    var inputs = el.querySelectorAll('INPUT');
    for( let input of inputs){
    input.addEventListener('blur', getCopy, false);
    }
    var el2 = document.getElementById('markets');
    el2.addEventListener('change', setLanguagesForMarket, false);
    var el3 = document.getElementById('languages');
    el3.addEventListener('change', getCopy, false);
    var el4 = document.getElementById("submit");
    el4.addEventListener('click', function(e) { section.saveImage(e); });
}