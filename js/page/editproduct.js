var product = null;

function createImageSpan(imageId, imageName) {
    var div = document.getElementById('addedpictures');
    var para = document.createElement('P');
    para.setAttribute('class', 'row-float');
    var span = document.createElement('SPAN');
    span.setAttribute('class','duo left');
    var but = document.createElement('BUTTON');
    but.setAttribute('data-image-id', imageId);
    but.setAttribute('class', 'right');
    span.innerText = imageName;
    but.innerText = 'delete';
    para.appendChild(span);
    para.appendChild(but);
    div.appendChild(para);
    
    but.addEventListener('click', function() { 
        product.deleteImage(imageId, function() {
            wQuery(para).remove();
        }); 
    });
    
}


function onImageAdded(imageId) {
    product.imageIds.push(imageId);
    var div = document.getElementById('thumbnails');
    var el = document.createElement('DIV');
    el.classList.add('thumbnail');
    var img = document.createElement('IMG');
    img.setAttribute('src', 'getImage.php?id=' + imageId);
    img.setAttribute('alt', 'thumbnail');
    el.appendChild(img);
    div.appendChild(el);
}

function thumbnailDelete(imageId) {
    var divs = document.querySelectorAll('#thumbnails .thumbnail');
    for ( let div of divs) {
        if ( div.innerHTML.indexOf(imageId) >= 0) {
            wQuery(div).remove();
        }
    }
}

function setMarketLanguages(marketId, marketName) {
    if (!marketId) return;
    var languages = countryLanguages[marketId];
    setLanguagesForProductInfo(languages);
    setMaterialsForMarket(marketId, marketName);
    var old = document.getElementById('productInfo');
    wQuery(old).find('DIV').remove();
    wQuery(old).find('DIV').remove();
}

function setMaterialsForMarket(marketId, marketName) {
    if(!marketName.length){
        marketName = 'no items'
    }
    product.currentMarketId = marketId;
    buildItemsBoxLabelHtml(marketName, marketId);
    if(!countryItems[marketId]){
        deletePreviousMaterialsHtml();
        alert('no items for this market');
        return;}
    var items = countryItems[marketId].materials;
    var materials = appendItemsBoxMaterialsHtml(marketId, items, document.getElementById('itemDetails'));
    materials.addEventListener('click', onMaterialsClicked, false);
    
    setSelectedMaterialAndSizes();
}

function onMaterialsClicked(e) {
    var elTarget = e.target;
    if (elTarget.tagName !== "INPUT") {
        return;
    }
    var marketId = elTarget.getAttribute("data-market-id");
    var materialId = elTarget.getAttribute("data-material-id");
    if (!elTarget.checked) {
        clearSizesForMaterial(elTarget, materialId);
    } else {
        setSizesForMaterial(elTarget, marketId, materialId);
    }
}

function deletePreviousMaterialsHtml(){
    var previous = document.getElementById('materialOptions');
    if(previous){
        wQuery(previous).remove();
    }
    return;
}

function appendItemsBoxMaterialsHtml(marketId, items, parent) {
    deletePreviousMaterialsHtml();
    var p = document.createElement('p');
    p.setAttribute('id', 'materialOptions');
    for (let item of items) {
        var par = null;
        par = document.createElement('p');
        par.setAttribute('class', 'duo left')
        var inputEl =  document.createElement('input');
        inputEl.setAttribute('type','checkbox');
        inputEl.setAttribute('data-market-id', marketId);
        inputEl.setAttribute('data-material-id', item.materialId);
        var label = document.createElement('label');
        label.innerText = item.material;
        par.appendChild(inputEl);
        par.appendChild(label);
        p.appendChild(par);
    }
    
    parent.appendChild(p);
    return p;
}


function buildItemsBoxLabelHtml(marketName, marketId) {
    var group = document.getElementById('addCountry');
    for ( let child of group.children) {
        child.removeChild(child.firstChild);
    }
    var a = document.createElement('a');
    a.setAttribute('data-marketId' ,marketId);
    a.innerText = marketName;
    var item = group.firstElementChild;
    item.appendChild(a);
}

function appendSizesHtml(materialId, sizes, parent) {
    var p = document.createElement('p');
    p.setAttribute('id', 'sizeOptions');
    p.setAttribute('class', 'inline-block');

    var select = document.createElement('select')
    select.setAttribute('multiple', '');

    p.appendChild(select);
    
    for(let size of sizes) {
        var option = document.createElement('option');
        option.setAttribute("data-material-id", materialId);
        option.setAttribute("data-size-id", size.sizeId);
        select.appendChild(option);
        var sizeName = document.createTextNode(size.size);
        option.appendChild(sizeName);
    }
    
    select.addEventListener("change", onSizesChanged, false);
    parent.appendChild(p);
}

function onSizesChanged(e) {
    e.stopPropagation();
    var elTarget = e.target;
    var options = wQuery(elTarget).find("OPTION");
    for (let option of options) {
        var materialId = option.getAttribute("data-material-id");
        var sizeId = option.getAttribute("data-size-id");
        if (option.selected) {
            product.addSize(materialId, sizeId);
        } else {
            product.removeSize(materialId, sizeId)
        }
    }
    product.saveItems();
}

function clearSizesForMaterial(elem, materialId) {
    /* if material not checked anymore delete it from product obj */
    wQuery(elem).parent().find('P').remove();
    product.removeMaterial(materialId);
    product.saveItems();    
}

function setSizesForMaterial(elem, marketId, materialId) {
    var material = product.getMaterial(materialId);
    var materialsForMarket = countryItems[marketId].materials;
    var material = materialsForMarket.find(function(m) { return m.materialId == materialId; })
    if (material) {
        appendSizesHtml(materialId, material.sizes, elem.parentElement);
    }
    
}

function setSelectedMaterialAndSizes() {
    // Set selection states
    var selectedMaterialIds = product.getMaterialIdsForMarket();
    for (let materialId of selectedMaterialIds) {
        var input = wQuery("#materialOptions").find("input[data-material-id='" +materialId + "']").at(0);
        input.checked = true;
        setSizesForMaterial(input, product.currentMarketId, materialId);
        var selectedSizeIds = product.getSizeIdsForMaterial(materialId);
        var options = wQuery(input).parent().find("option");
        for (let option of options) {
            var sizeId = parseInt(option.getAttribute('data-size-id'));
            if (selectedSizeIds.indexOf(sizeId) >= 0) {
                option.selected = true;
            }
        }
    }    
}

function setLanguagesForProductInfo(languages) {
    var group = document.getElementById("addLanguage");
    var item = group.firstElementChild;
    var html = '';
    deleteLanguagesFromControlBar()
    for (let language of languages) {
        let langEl = item.cloneNode(true);
        let anchor = wQuery(langEl).find("A").first();
        //let anchor = findElem(langEl, "A");
        if (anchor) {
            anchor.setAttribute('data-languageId', language.languageId);
            anchor.innerHTML = language.languageName;
            addingLanguagesToControlBar(language.languageName, language.languageId);
        }
        html += langEl.outerHTML;
    }
    group.innerHTML = html;
}

function deleteLanguagesFromControlBar(){
    var para = document.getElementById('languages');
    para.innerHTML = '';
    return;
}

/*
 * Updates the color wheter language is full
 * @returns {undefined}
 */
function updateLanguagesFromControlBar(){
    var pi = product.getProductInfo();
    var update = product.checkProductInfoStatus(pi.languageId);
    var para = document.getElementById('languages');
    for (let p of para.children){
        if(parseInt(p.getAttribute('data-language-id')) === pi.languageId){
            p.style.color= update ? "green" : "red";
        }
    }
    return;
}

/*
 * adding to the currently bar info wheter language are complete
 * Called from setLanguagesForProductInfo
 */
function addingLanguagesToControlBar(languageName, languageId) {
    var para = document.getElementById('languages');
    var span = document.createElement('SPAN');
    span.setAttribute('data-language-id', languageId);
    span.innerText = languageName + '->';
    if (product.checkProductInfoStatus(languageId)){
        span.style.color = "green";
    }
    para.appendChild(span);
}

function createProductInfoHtml(){
    var productInfo = product.getProductInfo();
    var old = document.getElementById('productInfo');
    if(old.querySelector('DIV')){
        wQuery(old).find('DIV').remove();
        wQuery(old).find('DIV').remove();
    }
    var field = document.getElementById('productInfo');
    var divs = [];
    var pEls = [];
    var labels = [];
    var textField = [];
    var text = '';
    var input = document.createElement('input');
    input.setAttribute('type','text');
    input.setAttribute('name', 'product_info');
    input.value = productInfo.name;
    for ( var i = 0; i < 2 ; i++){
        let style = '';
        divs[i] = document.createElement('div');
        textField[i] = document.createElement('textarea');
        textField[i].setAttribute('type', 'text');
        textField[i].setAttribute('name','product_info');
        textField[i].value = i === 0 ? productInfo.description : productInfo.tags;
        if( i > 0) {
            style = 'duo right';
            divs[i].setAttribute('id', 'tags')
        } else {
            style = 'duo left';
        }
        divs[i].setAttribute('class', style);
        }
        for ( var ind = 0; ind < 3 ; ind++){
            let style = 'block';
            pEls[ind] = document.createElement('p');
            pEls[ind].setAttribute('class', 'wrapper');
            labels[ind] = document.createElement('label');
            if( ind === 0) {
                text = 'name';
                labels[ind].appendChild(document.createTextNode(text));
                pEls[ind].appendChild(labels[ind]);
                pEls[ind].appendChild(input);
                divs[0].appendChild(pEls[ind]);
            } else {
                if (ind === 1) {
                    index = 0;
                    text = 'description';
                } else { 
                    text = 'tags';
                    index = 1;
                }
                labels[ind].setAttribute('class', 'block');
                labels[ind].appendChild(document.createTextNode(text))
                pEls[ind].appendChild(labels[ind]);
                pEls[ind].appendChild(textField[ind -1 ]);
                divs[index].appendChild(pEls[ind]);
                field.appendChild(divs[index]);
            }
        }
        var el7 = document.getElementById('productInfo');
        el7.querySelector('input').addEventListener('blur', function(e){
            product.updateProductInfoName(e.target.value);
            updateLanguagesFromControlBar();
            
        }, false);
        var textAreas = el7.querySelectorAll('textarea');
        for ( var el of textAreas){
            el.addEventListener('blur',function(e){
                if ( e.target.previousSibling.innerText === 'DESCRIPTION'){
                    product.updateProductInfoDescription(e.target.value);
                    updateLanguagesFromControlBar();
                }
                else if (e.target.previousSibling.innerText === 'TAGS'){
                    product.updateProductInfoTags(e.target.value);
                }
            }, false)
        }
    
}

function selectLanguage(e) {
    var elTarget = e.target;
    if (elTarget.tagName === 'A') {
        for(let sibling of elTarget.parentElement.parentElement.children) {
            sibling.classList.remove('selected');
        }
        elTarget.parentElement.classList.add('selected');
    }
}

function getLanguageIdsForMarket(marketId) {
    if (countryLanguages[marketId]) {
        return countryLanguages[marketId].map(c => c.languageId);
    }
    return [];
}

function onCurrentlyClicked(e) {
    elTarget = e.target;
    if ( elTarget.tagName === 'SPAN') {
        var marketId = parseInt(elTarget.getAttribute('data-item'));
        if (countryItems[marketId]){
            var marketName = countryItems[marketId].name;
            setMarketLanguages(marketId, marketName);
        } else {
            setMarketLanguages(marketId, '');
        }
        
    }    
}

function showRoomPreview() {
  var temp = document.getElementsByTagName("template")[0];
  var field = document.getElementsByClassName('desktop');
  var showroomDiv = temp.content.querySelector('#showroomimages');
  var showroomproduct = new showRoomProduct(product);
  for (let imageId of showroomproduct.imageIds){
      let img = document.createElement('IMG');
      img.setAttribute('src', 'getImage.php?id=' + imageId);
      img.classList.add('slider');
      showroomDiv.insertBefore(img, showroomDiv[0]);
  }
  setNameAndDescriptionForShowRoom(temp, showroomproduct);
  setSizesForShowRoom(temp, showroomproduct); 
  var clon = temp.content.cloneNode(true);
  field[0].appendChild(clon);
  field[0].childNodes[1].style.display = 'none';
  showDivs(slideIndex);
  addShowRoomEventListener();
}

function setNameAndDescriptionForShowRoom(temp, showRoomProduct) {
    var productNameEl = temp.content.querySelector('.product-info h1');
    var productDescriptionPara = temp.content.querySelector('#productdescription');
    var editButton = temp.content.querySelector('#editButton');
    editButton.addEventListener('click', goBackFromShowRoom, false);
    productNameEl.textContent = showRoomProduct.productInfo.name;
    productDescriptionPara.textContent = showRoomProduct.productInfo.description;
    productDescriptionPara.classList.add('displayblock');
    return;
}

function setSizesForShowRoom(template, showRoomProduct) {
    var sizesPara = template.content.querySelector('#productsizes');
    sizesPara.innerHTML = '';
    sizesPara.innerHTML = showRoomProduct.sizes;
    return;
}

function goBackFromShowRoom() {
    var field = document.getElementsByClassName('desktop');
    field[0].childNodes[1].style.display = 'flex';
    var showRoom = document.querySelector('#showRoom');
    field[0].removeChild(showRoom);
}

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var x = document.getElementsByClassName("slider");
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


window.onload = () => {
    addEventListeners();
    
    
    product = new Product(productId);
    product.formatId = wQuery("select[name='format'").val();
    
    if (productId > 0) {
        ajaxGet("ajaxtest.php?productId=" + productId, function(data) {
            console.log(JSON.stringify(data)); 
            product.loadProduct(data);
            
            var marketId = product.getFirstMarketId();
            setMarketLanguages(marketId, countryItems[marketId].name);
            
            var languageId = product.getFirstLanguageIdInSet(getLanguageIdsForMarket(marketId));
            if (languageId) {
                var el = wQuery("#addLanguage a[data-languageId='" + languageId + "']");
                if (el.at(0)) {
                    el.at(0).click();
                }
            }
        });
    }
}

function addEventListeners() {
    var el = document.getElementById('currently');
    el.addEventListener('click', onCurrentlyClicked, false);
    
    var el2 = document.getElementById('addLanguage');
    el2.addEventListener('click', selectLanguage, false);

    
    
    var el4 = document.getElementById("general");
    el4.querySelector('input').addEventListener('blur', function(e) {
        var artist = e.target.value;
        product.saveArtist(artist);
        },
        false);
        
    el4.querySelector('select').addEventListener('change', 
        function(e) { 
            var formatId = parseInt(e.target.value); 
            product.formatId = formatId;
        },  
        false);
    
    var el5 = document.getElementById('productPreview');
    el5.addEventListener('click', showRoomPreview, false);
    
    var el6 = document.getElementById('addLanguage');
    el6.addEventListener('click',
        function (e) {
            var languageId = parseInt(e.target.getAttribute('data-languageId'));
            product.currentLanguageId = languageId;
            createProductInfoHtml();
         
        },
        false);
    
    var el7 = document.getElementById('addCountry');
    el7.addEventListener('click',
        function(e) {
            var marketId = parseInt(e.target.getAttribute('data-marketId'));
            product.currentMarketId = marketId;
        }, false);
      
    
}