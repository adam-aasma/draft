
function wQuery(elem) {
    function createWq(value) {
        var v = new wq(value);
        v[Symbol.iterator] = function* () {
            while (!v.atEnd()) yield v.next().value;
        }
        return v;
    }
    
    function wq(value) {
        var elems = value ? (value.isArray ? value : 
                typeof value === "string" ? nodeListToArray(document.querySelectorAll(value)) : 
                NodeList.prototype.isPrototypeOf(value) ? nodeListToArray(value) :
                [value]) : [];
        var currentIndex = 0;
        this.next = function() {
            if (currentIndex < elems.length) {
                return { value: elems[currentIndex++], done: false };
            } else {
                return { done: true };
            }
        };
        this.atEnd = function() {
            return currentIndex >= elems.length;
        };
        this.find = function(name) {
            if (elems.length) {
                return findElem(elems[0], name);
            }
            return null;
        };
        this.at = function(i) {
            return i >= 0 && i < elems.length ? elems[i] : null;
        };
        this.elements = function() {
            return elems;
        };
        this.remove = function() {
            for(let elem of elems) {
                var parent = elem.parentElement;
                parent.removeChild(elem);
            }
        };
        this.parent = function() {
            if (elems.length) {
                return createWq(elems[0].parentElement);
            }
            return null;
        };
        this.val = function(v) {
            if (elems.length && 'value' in elems[0]) {
                var elem = elems[0];
                if (typeof v === "undefined") {
                    return elem.value;
                } else {
                    elem.value = v;
                    return createWq(elem);
                }
            }
            return null;
        };
        
        function findElem(el, name) {
            var res = el.querySelectorAll(name);
            return createWq(res);
        }
        function nodeListToArray(ns) {
            var a = [];
            for(let n of ns) {
                a.push(n);
            }
            return a;
        }
    }
    
    return createWq(elem);
}

function findElem(el, name) {
    if (el.tagName === name) 
        return el;
    for (let child of el.children) {
        var res = findElem(child, name);
        if (res) return res;
    }
    return null;
}

function closestAncestor(el, match) {
    if (el.matches(match)) {
        return el;
    }
    if (el.parentElement) {
        return closestAncestor(el.parentElement, match)
    }
    return null;
}

function removeChildNodes(id){
    var parent = document.getElementById(id);
    parent.innerHTML = '';
}

function getLanguagesForCountry(e) {
    elTarget = e.target;
    if ( elTarget.tagName == 'SPAN') {
        var marketId = parseInt(elTarget.getAttribute('data-item'));
        var marketName = elTarget.innerText;
        if (marketId) {
            setMarketLanguages(marketId, marketName);
        }
    }
}

function setMarketLanguages(marketId, marketName) {
    var languages = countryLanguages[marketId];
    setLanguagesForProductInfo(languages);
    setMarketForItems(marketName, marketId);
    var old = document.getElementById('productInfo');
    wQuery(old).find('DIV').remove();
    wQuery(old).find('DIV').remove();
}

function setMarketForItems(market, marketId) {
    product.currentMarketId = marketId;
    var previous = document.getElementById('materialOptions');
    if(previous){
        wQuery(previous).remove();
    }
    var group = document.getElementById('addCountry');
    for ( let child of group.children) {
        child.removeChild(child.firstChild);
    }
    var item = group.firstElementChild;
    var a = document.createElement('a');
    a.setAttribute('data-marketId' ,marketId);
    a.innerText = market;
    item.appendChild(a);
    var items = countryItems[marketId];
    if(!items){ alert('no items for this market'); return;}
    var p = document.createElement('p');
    p.setAttribute('id', 'materialOptions');
    var field = document.getElementById('itemDetails');
    field.appendChild(p);
    for (let item of items) {
        var par = null;
        par = document.createElement('p');
        par.setAttribute('data-item', item.materialId);
        par.setAttribute('class', 'duo left')
        var inputEl =  document.createElement('input');
        inputEl.setAttribute('type','checkbox');
        inputEl.setAttribute('value','true');
        inputEl.setAttribute('name', item.materialId);
        var label = document.createElement('label');
        label.innerText = item.material;
        par.appendChild(inputEl);
        par.appendChild(label);
        p.appendChild(par);
    }
    p.addEventListener('click', function(e, x) { setItemsForMarket(e.target, marketId) }, false);
    
    setSelectedMaterialAndSizes();
}

function setItemsForMarket(elem, marketId) {
    if(elem.tagName === 'INPUT' && !elem.checked){
        wQuery(elem).parent().find('P').remove();
        product.removeMaterial(elem.getAttribute('name'));
        product.saveItems();
        return;
    } else if(elem.tagName === 'INPUT'){
        var p = document.createElement('p');
        p.setAttribute('id', 'sizeOptions');
        p.setAttribute('class', 'inline-block');
        var materialId = parseInt(elem.getAttribute('name'));
        product.getMaterial(materialId);
        var itemsForMarket = countryItems[marketId];
        var itemForMarket = itemsForMarket.find(i => i.materialId == materialId);
        var select = document.createElement('select')
        select.setAttribute('multiple', '');
        select.setAttribute('materialId', materialId);
        p.appendChild(select);
        var sizes = itemForMarket.sizes;
        for( let size of sizes) {
            var option = document.createElement('option');
            option.setAttribute("value", size.sizeId);
            select.appendChild(option);
            var sizeName = document.createTextNode(size.size);
            option.appendChild(sizeName);
        }
        var material = document.getElementById('materialOptions');
        var paras = material.querySelectorAll('P');
        for( let para of paras) {
            if(para.getAttributeNode('data-item')) {
               if (para.getAttributeNode('data-item').value === materialId) {para.appendChild(p)};
            }
        }
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
}

function setSelectedMaterialAndSizes() {
    // Set selection states
    var selectedMaterialIds = product.getMaterialIdsForMarket();
    for (let materialId of selectedMaterialIds) {
        var input = wQuery("#materialOptions").find("input[name='" +materialId + "']").at(0);
        input.checked = true;
        setItemsForMarket(input, product.currentMarketId);
        var selectedSizeIds = product.getSizeIdsForMaterial(materialId);
        var options = wQuery(input).parent().find("option");
        for (let option of options) {
            if (selectedSizeIds.indexOf(option.value) >= 0) {
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
        
        let anchor = findElem(langEl, "A");
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

function hideDisplayOptions(e) {
    
}


function formData() {
    var parts = [];
    var files = [];
    var url = '';
    var callback = null;
    
    this.url = function(val) {
        if (typeof val !== "undefined")
            url = val;
        return url;
    },
    this.callback = function(cb) {
        callback = cb;
    },
    this.addPart = function(name, value) {
        parts.push({ 'name': name, 'value': value});
    }, 
    this.addFile = function(file) {
        files.push(file);
    },
    this.post = function() {
        var request = new XMLHttpRequest();
        request.open("POST", url);
        request.responseType = "json";
        request.onreadystatechange = function() {
            if (request.readyState === 4 && callback) {
                callback(request.response);
            }
        }
        if (url.length && (parts.length || files.length)) {
            var formdata = new FormData();
            for(let part of parts) {
                formdata.append(part.name, part.value);
            }
            for(let file of files) {
                formdata.append('images[]', file, file.name)
            }
            request.send(formdata);            
        }
    }
    
    return this;
}

function ajaxGet(url, callbackSuccess) {
    var request = new XMLHttpRequest();
    request.open("GET", url);
    request.responseType = "json";
    request.onreadystatechange = function() {
        if (request.readyState === 4 && callbackSuccess) {
            callbackSuccess(request.response);
        }
    }
    request.send();
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

function Material(materialId) {
    this.materialId = materialId;
    this.sizeIds = [];
    this.addSizeId = function(id) {
        if (this.sizeIds.indexOf(id) < 0) {
            this.sizeIds.push(id);
        }
    };
    this.removeSizeId = function(id) {
        var ix = this.sizeIds.indexOf(id);
        if (ix >= 0) {
            this.sizeIds.splice(ix, 1);
        }
    };
}

function Market(marketId) {
    this.marketId = marketId;
    this.materials = [];
    this.getMaterial = function(materialId) {
        var material = this.materials.find(m => m.materialId === materialId);
        if (!material) {
            material = new Material(materialId);
            this.materials.push(material);
        }
        return material;
    };
    this.removeMaterial = function(materialId) {
        var ix = this.materials.findIndex(m => m.materialId === materialId);
        if (ix >= 0) {
            this.materials.splice(ix, 1);
        }
    };
    this.getMaterialIds = function() {
        return this.materials.map(m => m.materialId);
    };
    
}

function Product(productId) {
    this.currentLanguageId = 0;
    this.currentMarketId = 0;
    this.productId = 0;
    this.imageIds = [];
    this.formatId = null;
    this.artistId = null;
    this.productInfos = [];
    this.markets = [];
         
    
    this.getProductInfo = function(languageId) {
        if (typeof languageId === "undefined") {
            languageId = this.currentLanguageId;
        }
        var productInfo = this.productInfos.find(p => p.languageId === languageId);
        if (!productInfo) {
            productInfo = {
                languageId: languageId,
                name: '',
                description: '',
                tags: ''
            };
            this.productInfos.push(productInfo);
        }
        return productInfo;
    };
    
    this.getFirstLanguageIdInSet = function(languageIds) {
        if (!languageIds || !languageIds.length) {
            return 0;
        }
        for(let productInfo of this.productInfos) {
            if (languageIds.indexOf(productInfo.languageId) >= 0) {
                return productInfo.languageId;
            }
        }
        return 0;
    };
    
    this.getMaterialIdsForMarket = function() {
        var market = this.getMarket();
        return market.getMaterialIds();
    };
    
    this.getSizeIdsForMaterial = function(materialId) {
        var market = this.getMarket();
        var material = market.getMaterial(materialId);
        return material.sizeIds;
    };
        
    this.getMarket = function(marketId) {
        if (typeof marketId === "undefined") {
            marketId = this.currentMarketId;
        }
        var market = this.markets.find(m => m.marketId === marketId);
        if (!market) {
            market = new Market(marketId);
            this.markets.push(market);
        }
        return market;
    };
    
    this.getFirstMarketId = function() {
        if (this.markets.length) {
            return this.markets[0].marketId;
        }
        return 0;
    };
    
    this.getMaterial = function(materialId) {
        var market = this.getMarket();
        return market.getMaterial(materialId);
    };
    
    this.removeMaterial = function(materialId) {
        var market = this.getMarket();
        market.removeMaterial(materialId);
    };
    
    this.addSize = function(materialId, sizeId) {
        var material = this.getMaterial(materialId);
        material.addSizeId(sizeId);
    };
    
    this.removeSize = function(materialId, sizeId) {
        var material = this.getMaterial(materialId);
        material.removeSizeId(sizeId);
    };
    
    this.updateProductInfoName = function(name) {
        var pi = this.getProductInfo();
        pi.name = name;
        this.saveProductInfo();
    };
    
    this.updateProductInfoDescription = function(value) {
        var pi = this.getProductInfo();
        pi.description = value;
        this.saveProductInfo();
    };
    
    this.updateProductInfoTags = function(value) {
        var pi = this.getProductInfo();
        pi.tags = value;
        this.saveProductInfo();    
    };
    
    this.setProductInfo = function(languageId, name, description, tags) {
        var pi = this.getProductInfo(languageId);
        pi.name = name;
        pi.description = description;
        pi.tags = tags;
    };
    
    this.setItem = function(marketId, materialId, sizeId) {
        var market = this.getMarket(marketId);
        var material = market.getMaterial(materialId);
        material.addSizeId(sizeId);
    };
    
    this.saveProductInfo = function() {
        var pi = this.getProductInfo();
        var f = new formData();
        f.url('ajaxtest.php');

        f.addPart('requestType', 'productinfo');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        f.addPart('languageId', pi.languageId);
        f.addPart('name', pi.name);
        f.addPart('description', pi.description);
        f.addPart('tags', pi.tags);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
        });

        f.post();
    };
    
    this.saveProduct = function() {
        var f = new formData();
        f.url('ajaxtest.php');
        f.addPart('requestType', 'product');
        var i = 0;
        for (let imageid of this.imageIds){
            f.addPart('imageId[' + (i++) + ']', imageid);
        }  

        f.addPart('formatId', this.formatId);
        f.addPart('artistId', this.artistId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.productId) {
                this.productId = response.productId;
            }
        });

        f.post();
    };
    
    this.saveArtist = function(artist) {
        var f = new formData();
        f.url('ajaxtest.php');
        f.addPart('requestType', 'artist');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        if(this.artistId){
            f.addPart('artistId', this.artistId);
        }
        f.addPart('artistDesigner', artist);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.artistId) {
                this.artistId = response.artistId;
            }
        });
        f.post();
    };
    
    this.saveItems = function() {
        var f = new formData();
        f.url('ajaxtest.php');
        f.addPart('requestType', 'productitems');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        var market = this.getMarket();
        if ( market.materials.length) {
            for(let material of market.materials) {
                for (let size of material.sizes) {
                    f.addPart('item[' + market.marketId + '][' + material.materialId + '][]', size);
                }
            }
        } else {
            f.addPart('item[' + market.marketId + ']', '');
            //f.addPart('marketId', market.marketId);
        }
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
        });
        f.post();
    };

    this.saveImage = function(e) {
        e.preventDefault();
        var div = closestAncestor(e.target, "DIV .pictureBar");
        if (div) {
            var fileElem = div.querySelector("INPUT[type=file]");
            var categoryElem = div.querySelector("INPUT[type=radio]:checked")
            if (fileElem && fileElem.files.length && categoryElem) {
                var f = new formData();
                f.url('ajaxtest.php');
                f.addPart('requestType', 'image');
                f.addPart('productId', this.productId);
                f.addPart('formatId', this.formatId);
                f.addPart('image-category-id', categoryElem.value);
                f.addFile(fileElem.files[0]);
                f.callback(function(response) { 
                    console.log(JSON.stringify(response)); 
                    if (response.imageId) {
                        onImageAdded(response.imageId);
                        this.productId = response.productId;
                        createImageSpan(response.imageId, response.imageName);
                        wQuery("input[type='file']").val('');
                    }
                });
                f.post();
            }
        }
    };
    
    this.deleteImage = function(imageId, onImageDeleted) {
        var f = new formData();
        f.url('ajaxtest.php');
        f.addPart('requestType', 'deleteimage');
        f.addPart('productId', this.productId);
        f.addPart('imageId', imageId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') {
                if (onImageDeleted) {
                    onImageDeleted(imageId);
                    thumbnailDelete(imageId);
                }
            }
        });
        f.post();
    };
    
    this.loadProduct = function(data) {
        for (let pd of data.productDescriptions) {
            this.setProductInfo(pd.languageId, pd.name, pd.description, pd.tags);
        }
        for (let item of data.items) {
            this.setItem(item.countryId, item.materialId, item.sizeId);
        }
        for(let image of data.images) {
            
        }
    };
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

function getLanguageIdsForMarket(marketId) {
    if (countryLanguages[marketId]) {
        return countryLanguages[marketId].map(c => c.languageId);
    }
    return [];
}

function addEventListeners() {
    var els = document.getElementsByClassName('dropDownMenu');
    for (i = 0; i < els.length; i++) {
        els[i].addEventListener('mouseover', openDropDown, false);
    }
    
    var el = document.getElementById('currently');
    el.addEventListener('click', getLanguagesForCountry, false);
    
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