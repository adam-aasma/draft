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

function getSelectedMarketId(){
     var markets = document.querySelectorAll('#markets OPTION');
      for (let market of markets){
        if (market.selected){ 
        return market.value;
        };
    }
}

function setSelectedLanguageAndMarket(languageId, marketId){
    var lanOptions = document.querySelectorAll('#languages OPTION');
    for (let option of lanOptions) {
        if(parseInt(option.getAttribute('data-language-id')) === languageId){
            option.selected = true;
        }
    }
    var marOptions = document.querySelectorAll('#markets OPTION');
    for (let option of marOptions) {
        if(parseInt(option.getAttribute('value')) === marketId){
            option.selected = true;
        }
    }
    
    return;
}

function setCopy(sectionCopy = null){
    if(!sectionCopy){
        sectionCopy = section.getSectionCopy(getSelectedLanguageId());
    }
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
}

/*
 * event fired in 2 scenarios, change language in copy
 * or change a single line in copy
 * @param {type} e
 *updates the copy
 */

function getCopy(e){
    if(!e){
        elTarget  = document.getElementById('languages');
    } else {
        var elTarget = e.target;
    }
    
    if(elTarget.tagName === 'SELECT'){
        setCopy(sectionCopy);
    } else {
        saveCopyAndUpdatePreviewCopy(elTarget);
    }
    getAllProductsForMarketAndLanguage();
    return updatePreviewLanguage(sectionCopy);
    
    
}

/* called upon from getCopy
 *  fired in event of changing language in copy
 * @param {type} sectionCopy
 * @returns {delete or set new content for preview }
 */
function updatePreviewLanguage(sectionCopy) {
    if(!sectionCopy){
        sectionCopy = section.getSectionCopy(getSelectedLanguageId());
    }
    var contentDiv = document.getElementById('content');
    var h2 = contentDiv.querySelector('H2');
    h2.textContent = sectionCopy.sline;
    var h3 = contentDiv.querySelector('H3');
    h3.textContent = sectionCopy.sline2;
    var titel = document.querySelector('#section H1');
    titel.textContent = sectionCopy.titel;
    
    
}

function saveCopyAndUpdatePreviewCopy(elTarget) {
    var dataItem = elTarget.getAttribute('data-item');
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
        section.saveSectionCopy();
        return updatePreviewCopy(text);
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
    span.setAttribute('class','left');
    var but = document.createElement('BUTTON');
    but.setAttribute('data-image-id', imageId);
    but.setAttribute('class', 'right');
    span.innerText = imageName;
    but.innerText = 'delete';
    para.appendChild(span);
    para.appendChild(but);
    div.appendChild(para);
    
    but.addEventListener('click', function() { 
        section.deleteImage(imageId, function() {
            wQuery(para).remove();
        });
        deleteImageFromPreview(imageId);
    });
    
}

function deleteImageFromPreview(imageId){
    var imgs = document.querySelectorAll('#preview IMG');
    for ( let img of imgs){
        var imgAttribute = img.getAttribute('src');
        if(imgAttribute.indexOf(imageId) >= 0){
            img.setAttribute('src', '');
        }
    }
}

function setImageToPreview(imageId, categoryId){
    var els = document.querySelectorAll('#imagescategories INPUT');
    for ( let el of els) {
        if(parseInt(el.value) === parseInt(categoryId)){
            var categoryEl = el;
        }
    }
    switch(categoryEl.nextSibling.textContent) {
        case  'sectionsmall' :
            var figure = document.querySelector('#leftsection');
            var temp = document.getElementById('gradientPic');
            break;
        case 'sectionbig' :
            var figure = document.querySelector('#rightsection');
            var temp = document.getElementById('bigPic');
            break;
        case 'sectionmobile' :
            /*not implemented */
            break;
    }
    var clone = temp.content.cloneNode(true);
    var img = clone.querySelector('IMG');
    img.setAttribute('src', 'getImage.php?id=' + imageId);
    figure.innerHTML = '';
    figure.appendChild(clone);
    
 }

function createImageThumbnails(productId, name, imageId){
    var div  = document.createElement('DIV');
    div.setAttribute('class', 'imagethumbnails')
    var imgEl = document.createElement('IMG');
    imgEl.setAttribute('src', 'getImage.php?id=' + imageId);
    imgEl.setAttribute('data-product-id', productId);
    var span = document.createElement('SPAN');
    span.textContent = name;
    div.appendChild(imgEl);
    div.appendChild(span);
    
    return div;
    
}

function setAllProductsToZero(){
    var div = document.getElementById('allproducts');
    var div2 = document.getElementById('includedProducts');
    div.innerHTML = '';
    div2.innerHTML = '';
}
function setAllAvailableProducts(imageThumbnail){
    var div = document.getElementById('allproducts');
    div.appendChild(imageThumbnail);
}
function setAllIncludedProducts(imageThumbnail){
    var div = document.getElementById('includedProducts');
    div.appendChild(imageThumbnail);
}

function getAllProductsForMarketAndLanguage() {
    setAllProductsToZero();
    ajaxGet('ajaxsectioncontroller.php?marketid=' + getSelectedMarketId() + 
            '&languageid=' + getSelectedLanguageId() +
            '&getproductrequest=true' +
             '&sectionId=' + section.sectionId,
        function(datas){
            try {
                if(datas.includedProducts.length){
                    for (let data of datas.includedProducts){
                        setAllIncludedProducts(createImageThumbnails(data.productid, data.name, data.image_id));
                    }
                }
                if(datas.allProducts.length) {
                    for (let data of datas.allProducts){
                        setAllAvailableProducts(createImageThumbnails(data.productid, data.name, data.image_id));
                    }
                }
            } catch(error) {
                /*no products for this market or language?
                 * or an error
                 */
            }
        });
}

function updateNumberOfProducts(productIds) {
    var number = productIds.length;
    var span = document.querySelector('#currently SPAN I');
    span.textContent = number;
    
    
}

function addSectionProducts() {
    var includedProducts = document.querySelectorAll('#includedProducts .imagethumbnails IMG');
    var productIds = [];
    for (let includedProduct of includedProducts){
        let productId = includedProduct.getAttribute('data-product-id');
        productIds.push(productId)
        
    }
    section.updateSectionProducts(productIds, getSelectedMarketId());
    updateNumberOfProducts(section.getSectionProducts());
    
}

function includeExcludeProduct(e, useCase) {
    var elTarget = e.target;
    if(elTarget.tagName === 'IMG'){
        var imgdiv = elTarget.parentElement.cloneNode(elTarget);
        elTarget.parentElement.parentElement.removeChild(elTarget.parentElement);
        var inclDiv = document.getElementById(useCase);
        inclDiv.appendChild(imgdiv);
        
    }
    addSectionProducts();
    return;
    
    
}

function prepareSectionPreview(){
    var temp = document.getElementById('sectionUpper');
    var clone = temp.content.cloneNode(true);
    var div = document.getElementById('section');
    div.appendChild(clone);
}



window.onload= function() {
    addEventListeners();
    setLanguagesForMarket();
    prepareSectionPreview();
    if(sectionId){
        section.loadSection(sectionId, function() {
            setSelectedLanguageAndMarket(section.currentLanguageId, section.currentMarketId);
            setCopy(section.getSectionCopy(section.currentLanguageId));
            updatePreviewLanguage(section.getSectionCopy(section.currentLanguageId));
            for(let image of section.images){
                if(!image.imageId){
                    continue;
                }
                createImageSpan(image.imageId, image.imageName);
                setImageToPreview(image.imageId, image.categoryId);
            }
            setTimeout(function() {
                adjustHeightForImg();
                getAllProductsForMarketAndLanguage();
                }, 3000);
            return;
            }
        );
    } else {
        setCopy();
    }
    adjustHeightForImg();
   
    
    
    
    
    
    
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
    el2.addEventListener('change', function() { setLanguagesForMarket(); getAllProductsForMarketAndLanguage();}, false);
    var el3 = document.getElementById('languages');
    el3.addEventListener('change', function() {setCopy(null); updatePreviewLanguage(null); getAllProductsForMarketAndLanguage() }, false);
    var el4 = document.getElementById("submit");
    el4.addEventListener('click', function(e, callback) { section.saveImage(e, function(response){
                            createImageSpan(response.imageId, response.imageName);
                            setImageToPreview(response.imageId, response.categoryId);
                            wQuery("input[type='file']").val('');
                            });
                        });
    var el5 = document.getElementById('allproducts');
    el5.addEventListener('click', function(e){
            var x = 'includedProducts';
            includeExcludeProduct(e,x);
    }, false);
    var el6 = document.getElementById('includedProducts');
    el6.addEventListener('click', function(e){
            var x = 'allproducts';
            includeExcludeProduct(e,x);
    }, false);
}