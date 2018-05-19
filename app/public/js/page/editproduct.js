
/*
 * global variables
 */
var product = null;
var itemField = null;
var copyField = null;
var imageField = null;



var localization;
var marketId = 0;
var languageId = 0;
function Localization (){
    
    this.onLanguageChange = function(e)
    {
        onLanguageChangeInternal(e);
       
    };
    this.onMarketChange = function()
    {
        onMarketChangeInternal();
    };
   
    this.registerEventListener = function(eventName, callback) {
        actionListeners.push({eventName: eventName, callback: callback});
    };
    var actionListeners = [];
    function notify(eventName) {
        var listeners = actionListeners.filter(a => a.eventName === eventName);
        for (let listener of listeners) {
            listener.callback();
        }
    };
    
    function setLanguagesForMarket() {
        var languages = countryLanguages[marketId];
        var div = document.querySelector('#underStore1 div');
        deleteElements('#underStore1 DIV SPAN');
        for (let language of languages) {
            var template = new Template();
            template.build('#languageTemplate')
            .addAttributes('SPAN',{'data-languageId' : language.languageId})
            .addContent('SPAN', language.languageName)
            .eventListener
                ('SPAN', 'click',
                    function(e){
                        onLanguageChangeInternal(e);
                    }
                )
            .appendTo(div);
        };
        setSelectedLanguage();
    };
    
    function setSelectedLanguage (e) {
        var spans = document.querySelectorAll('#underStore1 .row SPAN');
        if(!e){
           spans[0].classList.add('selectedLanguage');
        } else {
            for ( let span of spans){
                span.classList.remove('selectedLanguage');
            };
            e.target.classList.add('selectedLanguage');
        };
        setThisLanguageId();
    };
    
    function setSelectedMarket(e){
        var spans = document.querySelectorAll('#underStore2 .row SPAN');
        if(!e){
            spans[0].classList.add('selectedMarket');
            //this.onMarketChange();
        } else {
            for ( let span of spans){
                span.classList.toggle('selectedMarket');
            };
        };
        setThisMarketId();
        
    };
    
    function setThisMarketId(){
        var spans = document.querySelectorAll('#underStore2 .row SPAN');
        for(let span of spans){
            if(span.classList.contains('selectedMarket')){
                marketId = parseInt(span.getAttribute('data-marketId'));
                product.currentMarketId = marketId;
            }
        }
    };
    
    function setThisLanguageId(){
        var spans = document.querySelectorAll('#underStore1 .row SPAN');
        for(let span of spans){
            if(span.classList.contains('selectedLanguage')){
                languageId = parseInt(span.getAttribute('data-languageId'));
                product.currentLanguageId = languageId;
            };
        };
    };
    
    //INIT
    addEventListeners();
    
    
    
    function addEventListeners(){
        var spans = document.querySelectorAll('#underStore2 .row SPAN');
        for(let span of spans){
            span.addEventListener('click', function(e){
                onMarketChangeInternal(e);
            }, false);
        };
    };
    function onMarketChangeInternal(e){
        setSelectedMarket(e);
        setThisMarketId();
        setLanguagesForMarket();
        notify(OnMarketChange);
    }
    function onLanguageChangeInternal(e){
        setSelectedLanguage(e);
        notify(OnLanguageChange);
    }
    
};

/*
 * productItems items
 */

var ItemField = function(){
  
    this.coordinator = function(at){
        switch(at){
            case 'init' :
                localization.registerEventListener(OnMarketChange, onMarketChange );
                product.registerEventListener(MaterialsChanged, onMaterialsChanged);
        };
    };
    
    function onMarketChange(){
        appendMaterialsToDocument();
    }
    
    function onMaterialsChanged() {
        appendMaterialsToDocument();
    }
    
    function appendMaterialsToDocument(){
        if(document.querySelector('#itemDetails ARTICLE')){
            deleteElements('#itemDetails ARTICLE');
        };
        for(let material of countryItems[marketId].materials){
            var checked =  checkIfMaterialSelected(marketId, material.materialId);
            var template = new Template();
                template.build('#itemTemplate');
                if (checked) {
                    template.addAttributes('INPUT', {checked: ''});
                }
                template.addAttributes
                (
                    'INPUT',
                    {
                        'data-MaterialId' : material.materialId,
                    }
                )
                .addContent('LABEL', material.material)
                .eventListener
                (
                    "INPUT[data-materialId='" + material.materialId + "']",
                    'change',
                    function(e){
                        displaySizesForMaterial(e.target);
                    }
                )
                .appendTo('#itemDetails')
            ;
          
            if(checked){
                var el = document.querySelector("INPUT[data-materialId='" + material.materialId + "']");
                displaySizesForMaterial(el);
            };
        };
    };
    
    function checkIfMaterialSelected(marketId, materialId) {
        var market = product.getMarket(marketId);
        var materialIds = market.getMaterialIds();
        return materialIds.indexOf(materialId) > -1;
     
    }
   
    function displaySizesForMaterial(el){
        if(!el.checked){
            product.removeMaterial(parseInt(el.getAttribute('data-materialId')));
            return;
        }
        var marketId = product.currentMarketId;
        var materialId = parseInt(el.getAttribute('data-materialId'));
        var sizes = getSizesForMaterialId(materialId, marketId);

        var template = new Template();
        template.build('#itemSizesTemplate');
        var matSizesObjs = getSelectedSizes();
       

        for(let size of sizes){
            var option = document.createElement('OPTION');
            option.textContent = size.size;
            option.setAttribute('data-sizeId', size.sizeId);
            option.setAttribute('data-materialId', materialId);
            if(matSizesObjs){
               var obj = matSizesObjs.find(obj => (obj.materialId === materialId && obj.sizes.indexOf(size.sizeId) >= 0));
            }
            option.selected = obj ? true : false;
            template.appendToElement('SELECT', option);
        }

        var article = findAncestorByTag(el, 'ARTICLE');
        template.eventListener('SELECT','change', function(e){ e.stopPropagation(); onSizesChanged(e.target)}, false).appendTo(article);
    }
    
    function getSizesForMaterialId(materialId, marketId){
        for(let material of countryItems[marketId].materials){
            if(parseInt(material.materialId) === parseInt(materialId)){
                return material.sizes;
            };
        };
    }
    
    function onSizesChanged(el) {
        var options = el.querySelectorAll("OPTION");
        for (let option of options) {
            var materialId = option.getAttribute("data-materialId");
            var sizeId = option.getAttribute("data-sizeId");
            if (option.selected) {
                product.addSize(materialId, sizeId);
            } else {
                product.removeSize(materialId, sizeId)
            }
        }
    }
    
    //REBUILD FOR SIZES??
    function getSelectedSizes(){
        var market = product.getMarket();
        var objs = [];
        for ( let material of market.materials){
            if(material.getSizeIds().length > 0){
                objs.push( {
                    materialId: material.materialId,
                    sizes : material.getSizeIds()
                });
            }
        }
        return objs.length  ? objs : false;
    }
    

};

/*
 * SUMMARY BAR FUNCTIONS
 */
var SummaryField = function() {
    product.registerEventListener(ProductInfoChange, languageSummary);
    product.registerEventListener(ImageChanges, createThumbNail);
    product.registerEventListener(ImageDeleted, deleteThumbNail);
    
    //INIT
    for (let market in countryLanguages) {
        var template = new Template();
            template.build('#summarymarketlanguages')
            .addContent('SPAN', countryItems[market]['name'] );
        for(let language of countryLanguages[market]) {
            let lanMarkUp = document.createElement('DIV');
            lanMarkUp.textContent = language.languageName;
            lanMarkUp.classList.add('ali-right');
            lanMarkUp.classList.add('solo');
            lanMarkUp.setAttribute('data-languageId', language.languageId);
            template.appendToElement('DIV', lanMarkUp);
        }
        template.appendTo('#sumLanguagesWrapper');
    }
    
    
    function languageSummary() {
        var languageId = product.currentLanguageId;
        var bitmask = product.getProductInfoStatus(languageId);
        if (bitmask === ProductInfoAll) {
            setLanguageColorForSummary(languageId, 'Green');        
        } else if (bitmask) {
            setLanguageColorForSummary(languageId, 'Red');        
        } else {
            setLanguageColorForSummary(languageId, 'LightGrey');        
        }
    };
    
    function setLanguageColorForSummary(lanId, color){
        var languages = document.querySelectorAll('#sumLanguagesWrapper div div');
        for ( let language of languages){
            if(parseInt(language.getAttribute('data-languageId')) === parseInt(lanId)){
                language.style.color = color;
            } 
        }
    };
    
    function createThumbNail(){
        var img = getImage();
      
        var category = imageCategoriesNames[img.categoryId];
        var template = new Template();
            template.build('#thumbNailTemplate')
            .addAttributes
            (
                'IMG',
                {
                    src : '/image/get?id=' + img.id,
                    alt : 'thumbnail',
                    class : 'border'
                }
            )
            .addAttributes('FIGURE', {'data-ImageId' : img.id})
            .addContent('figcaption i:nth-of-type(1)', category)
            .addContent('figcaption i:nth-of-type(2)', img.name)
            .addContent('figcaption i:nth-of-type(3)', img.size)
            .appendTo(document.querySelector('#figuresWrapper'));
            setHeightToImgWrapper();
    }
    
    function getImage(){
        return product.images.find( i => i.id === product.currentImageId);
    }
    
    function deleteThumbNail(){
        var img = getImage();
        deleteElement("#figuresWrapper figure[data-imageid='" + img.id + "']");
    }
    
     
    function setHeightToImgWrapper(){
        var img = document.querySelector('#figuresWrapper figure:last-of-type IMG');
        var height = img.offsetHeight;
        var parHeight = height + 16 + 'px';
        img.parentElement.style.height = parHeight;
        
    }
};




/*
 * PRODUCTINFO
 */

var CopyField = function() {
    
    product.registerEventListener(ProductInfoChange, createProductInfoHtml);
    
    this.coordinator =  function(at){
        switch(at){
            case 'init' :
                localization.registerEventListener(OnLanguageChange, createProductInfoHtml);
                localization.registerEventListener(OnMarketChange, createProductInfoHtml);
                break;
        };
    };
    
    function createProductInfoHtml() {
        deleteElements('#productInfo div');
        var productInfo = product.getProductInfo();
        var field = document.getElementById('productInfo');
        var template = new Template();
        template.build('#producInfoTemplate')
        .value('INPUT', productInfo.name)
        .value('#productDescription', productInfo.description)
        .value('#productTags', productInfo.tags)
        .appendTo(field);
        addProductInfoEventListener(field);
    };
    
    function addProductInfoEventListener(field){
        field.querySelector('input')
        .addEventListener('blur', 
            function(e){
                product.updateProductInfoName(e.target.value);
            }, false);
        var textAreas = field.querySelectorAll('textarea');
        for (var el of textAreas){
            el.addEventListener('blur',
                function(e)
                {
                    if ( e.target.getAttribute('id') === 'productDescription'){
                        product.updateProductInfoDescription(e.target.value);
                       // setColorForLanguagesOverview();
                    } else if (e.target.getAttribute('id') === 'productTags'){
                        product.updateProductInfoTags(e.target.value);
                    }
                }, false);
        }    
    };
    
    
};

/*
 * showroom , productshowroom
 */

function ShowRoom(){
    //eventlistener
    var el5 = document.getElementById('productPreview');
    el5.addEventListener('click', 
    execute, false);
    
    var imageIds;
    var description
    var name
    var sizes;
    var desktop;
    //var desktopClone;
    function execute(){
        desktop = document.querySelector('.desktop');
        desktop.classList.add('hidden');
        showRoomProduct(product);
        //desktopClone = desktop.cloneNode(true);
        var temp = new Template();
        var t = temp.build('#productshowroomtemplate');
        //desktop.parentNode.removeChild(desktop);
        buildHtml(t);
        showDivs(slideIndex);
        addShowRoomEventListener();
    }
    
    
    function buildHtml(temp){
        
        setImages(temp);
        setNameAndDescription(temp);
        setSizes(temp);
        addEventListeners(temp);
        temp.appendTo('.flex-wrapper');
    };
    
    function setImages(temp){
        for(let imgId of imageIds){
            let img = document.createElement('IMG');
            img.setAttribute('src', 'image/get?id=' + imgId);
            img.classList.add('JS_slider');
            temp.appendToElement('#showroomimages', img);
        };
    }
    function setNameAndDescription(temp){
       temp.addContent('.product-info H1', name)
       .addContent('#productdescription', description);
    };
    function addEventListeners(temp){
         temp.eventListener('#editButton', 'click', backToEditProduct);
    };
    function setSizes(temp){
        temp.addContent('#productsizes', sizes);
    }
    
    function backToEditProduct(){
        var div = document.querySelector('#showroom');
        //div.parentNode.appendChild(desktopClone);
        div.parentNode.removeChild(div);
        desktop.classList.remove('hidden');
        
    }
    
    
    function showRoomProduct(product) {
        imageIds = [];
        productInfo = product.getProductInfo(product.currentLanguageId);
        sizes = '';
    
        for (let img of product.images){
            imageIds.push(img.id);
        }
        description = productInfo.description;
        name = productInfo.name;
        
        var market = product.getMarket();
        sizes = '';
        if (market.materials.length) {
            for(let material of market.materials) {
                for (let size of material.sizeIds) {
                   sizes += '<span class="showroomsizes">' +sizesObjArray[size] + '' + materialNamesObjArray[material.materialId] + '</span>';
                };
            };
        };
    };
    
    
   
    
};

window.onload = () => {
    //SET DEPENDENCIES
    product = new Product(productId);
    imageField = new ImageField(product);
    localization = new Localization();
    
    copyField = new CopyField();
    copyField.coordinator('init');
    itemField = new ItemField();
    itemField.coordinator('init');
    new ShowRoom;
    
    //INIT SCREEN QUITE CIRCULAR BETTER?
    localization.onMarketChange();
    localization.onLanguageChange();
    new SummaryField();
    
    addEventListeners();
  
    //INITIALIZE PRODUCT
    product.formatId = document.querySelector("select[name='format'").value;
   
   //IF LOAD PRODUCT
    if (productId > 0) {
        ajaxGet("/product/load?productId=" + productId, function(data) {
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
};

function addEventListeners() {
    
  
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
    
    
  
};