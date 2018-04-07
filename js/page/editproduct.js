var product = null;

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

function onLanguageAdded(e) {
    var language = e.target.innerText;
    var para = document.getElementById('languages');
    var span = document.createElement('SPAN');
    span.innerText = language + '->';
    para.appendChild(span);
    
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
    
    product.currentMarketId = marketId;
    
    buildItemsBoxLabelHtml(marketName, marketId);
    
    var items = countryItems[marketId];
    if(!items){ alert('no items for this market'); return;}
    
    var materials = appendItemsBoxMaterialsHtml(marketId, items, document.getElementById('itemDetails'));
    materials.addEventListener('click', onMaterialsClicked, false);
    
    setSelectedMaterialAndSizes();
}

function onMaterialsClicked(e) {
    var elTarget = e.target;
    var marketId = elTarget.getAttribute("data-market-id");
    var materialId = elTarget.getAttribute("data-material-id");
    if (!elTarget.checked) {
        clearSizesForMaterial(elTarget, materialId);
    } else {
        setSizesForMaterial(elTarget, marketId, materialId);
    }
}

function appendItemsBoxMaterialsHtml(marketId, items, parent) {
    var previous = document.getElementById('materialOptions');
    if(previous){
        wQuery(previous).remove();
    }
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
    /* if target matches the checkbox( input elem), build and setting the sizes paragraph */
    var material = product.getMaterial(materialId);
    
    var materialsForMarket = countryItems[marketId];
    var material = materialsForMarket.find(function(m) { return m.materialId == materialId; })
    if (material) {
        appendSizesHtml(materialId, material.sizes, elem.parentElement);
    }
    /*
    } else {
        var materialId = elem.parentElement.getAttribute('materialId');
        for (let child of elem.parentElement.children){
            let sizeId = child.value;
            if (child.selected) {
                product.addSize(materialId, sizeId);
            } else {
                product.removeSize(materialId, sizeId)
            }
        }
        product.saveItems();
    }
    */
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
    for (let language of languages) {
        let langEl = item.cloneNode(true);
        
        let anchor = wQuery(langEl).find("A").first();
        //let anchor = findElem(langEl, "A");
        if (anchor) {
            anchor.setAttribute('data-languageId', language.languageId);
            anchor.innerHTML = language.languageName;
        }
        html += langEl.outerHTML;
    }
    group.innerHTML = html;
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
        }, false);
        var textAreas = el7.querySelectorAll('textarea');
        for ( var el of textAreas){
            el.addEventListener('blur',function(e){
                if ( e.target.previousSibling.innerText === 'DESCRIPTION'){
                    product.updateProductInfoDescription(e.target.value);
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
        setMarketLanguages(
            parseInt(elTarget.getAttribute('data-item')),
            elTarget.innerText);
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
            setMarketLanguages(marketId, "marketName");
            
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
    var els = document.getElementsByClassName('dropDownMenu');
    for (i = 0; i < els.length; i++) {
        els[i].addEventListener('mouseover', openDropDown, false);
    }
    
    var el = document.getElementById('currently');
    el.addEventListener('click', onCurrentlyClicked, false);
    
    var el2 = document.getElementById('addLanguage');
    el2.addEventListener('click', selectLanguage, false);

    var el3 = document.getElementById("editProduct");
    el3.addEventListener('submit', function(e) { product.saveImage(e); });
    
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
    
    var el5 = document.getElementById('saveProduct');
    el5.addEventListener('click', function() { if (product) product.saveProduct(); }, false);
    
    var el6 = document.getElementById('addLanguage');
    el6.addEventListener('click',
        function (e) {
            var languageId = parseInt(e.target.getAttribute('data-languageId'));
            product.currentLanguageId = languageId;
            createProductInfoHtml();
            onLanguageAdded(e);
        },
        false);
    
    var el7 = document.getElementById('addCountry');
    el7.addEventListener('click',
        function(e) {
            var marketId = parseInt(e.target.getAttribute('data-marketId'));
            product.currentMarketId = marketId;
        }, false);
    
}