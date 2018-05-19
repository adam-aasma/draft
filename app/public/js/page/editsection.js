
//GLOBALS INITIALIZE
var section;
var imageField;
var sectionCopy;
function SectionCopy() {
   localization.registerEventListener(OnLanguageChange, setCopy);
   localization.registerEventListener(OnMarketChange, setCopy);
    
    this.coordinator = function(at, e){
        
        switch(at){
            case 'init' :
                addEventListeners();
                //setLanguagesForMarket();
                break;
            case 'update' :
                saveCopy(e.target); 
                break;
            case 'onLanguageChange':
                setCopy();
                break;
        };
    };
    
    
    
    function saveCopy(el) {
        var sectionCopy = section.getSectionCopy();
        var dataItem = el.getAttribute('data-item');
            var text = 
            {
                line : '',
                text : ''
            }
        text.text = el.value;
        switch (dataItem) {
            case 'titel' :
                text.line = 'titel';
                sectionCopy.titel = text.text;
                break;
            case 'salesline' :
                text.line = 'salesline';
                sectionCopy.sline = text.text;
                break;
            case 'salesline2' :
                text.line = 'salesline2';
                sectionCopy.sline2 = text.text;
                break;
            case 'sectiondescription' :
                text.line = 'sectiondescription';
                sectionCopy.description = text.text;
                break;
        };
        section.saveSectionCopy();
           
    };
    
    function setCopy(){
        var sectionCopy = section.getSectionCopy();
        var inputs = document.querySelectorAll('#copy INPUT');
        var textarea = document.querySelector('#copy TEXTAREA');
        textarea.value = sectionCopy.description;
        for( let input of inputs){
            var dataItem = input.getAttribute('data-item');
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
    };
    
    function addEventListeners(){
        var el = document.getElementById('copy');
        var textarea = el.querySelector('TEXTAREA');
        textarea.addEventListener('blur',function(e) {sectionCopy.coordinator('update', e);});
        var inputs = el.querySelectorAll('INPUT');
        for( let input of inputs){
            input.addEventListener('blur', function(e) {sectionCopy.coordinator( 'update', e);});
        };
    };
  
    
};
    
var products;
function Products() {
    
    
    this.coordinator = function(at) {
        switch(at){
            case 'init' :
                addEventListeners();
                getAllProductsForMarketAndLanguage();
                localization.registerEventListener(OnMarketChange, getAllProductsForMarketAndLanguage)
                localization.registerEventListener(OnLanguageChange, getAllProductsForMarketAndLanguage);
        }
     
    };
    
  
    
    function getAllProductsForMarketAndLanguage() {
        setAllProductsToZero();
        ajaxGet('section/getproducts?marketId=' + section.currentMarketId + 
                '&languageId=' + section.currentLanguageId +
                 '&sectionId=' + section.sectionId,
            function(datas){
                try {
                    if(datas.includedProducts.length){
                        for (let data of datas.includedProducts){
                            setAllIncludedProducts(createImageThumbnail(data.productid, data.name, data.image_id));
                        }
                    }
                    if(datas.allProducts.length) {
                        for (let data of datas.allProducts){
                            setAllAvailableProducts(createImageThumbnail(data.productid, data.name, data.image_id));
                        }
                    }
                    } catch(error) {
                   
                    }
            });
    };
    function createImageThumbnail(productId, name, imgId){
        var temp = new Template();
        temp.build('#imageThumbNailTemplate')
        .addAttributes('FIGURE', { draggable: true, id : imgId})
        .eventListener( 'FIGURE' , 'dragstart',
            function(e)
                {
                    var el = findAncestorBySelector(e.target, 'FIGURE');
                    drag(e ,el);
                }
        )
        .addAttributes('IMG', 
            { 
                src : '/image/get?id=' + imgId,
                'data-productId' : productId
            }
        )
        .addContent('FIGCAPTION SPAN', name);
        return temp.template;
    }
    
    function setAllIncludedProducts(imageThumbnail){
        var div = document.getElementById('includedProducts');
        div.appendChild(imageThumbnail);
    };
    
    function setAllAvailableProducts(imageThumbnail){
        var div = document.getElementById('allproducts');
        div.appendChild(imageThumbnail);
    };
    
    function setAllProductsToZero(){
        var div = document.getElementById('allproducts');
        var div2 = document.getElementById('includedProducts');
        div.innerHTML = '';
        div2.innerHTML = '';
    };
    
    function addEventListeners(){
        var div1 = document.getElementById('allproducts');
        div1.addEventListener('drop',
        function(e)
        {
            drop(e);
            updateSectionProducts();
        });
        div1.addEventListener('dragover', allowDrop);
        var div2 = document.getElementById('includedProducts');
        div2.addEventListener('drop', 
        function(e)
        {
            drop(e);
            updateSectionProducts();
        });
        div2.addEventListener('dragover', allowDrop);
    };
    
    function allowDrop(e){
        e.preventDefault();
    }
    
    function drop(e){
        e.preventDefault();
        var data = e.dataTransfer.getData("text");
        e.target.appendChild(document.getElementById(data));
    };
    
    function drag(e, el){
        e.dataTransfer.setData("text", el.id );
    }
    
    function updateSectionProducts(){
        var ids = [];
        var imgs = document.querySelectorAll('#includedProducts FIGURE IMG');
        for (let img of imgs){
            ids.push(img.getAttribute('data-productId'));
        }
        section.updateSectionProducts(ids);
    }
    
    

};

var sectionPreview;
function SectionPreview(){
    section.registerEventListener(ImageChanges, setImageToPreview);
    section.registerEventListener(ImageDeleted, deleteImageFromPreview);
    section.registerEventListener(CopyChanges, setCopy);
    
    localization.registerEventListener(OnLanguageChange, setCopy);
    
    this.coordinator = function(at){
        switch(at){
            case 'init' :
                createSectionPlaceholder();
                break;
            case 'onLanguageChange' :
                setCopy();
                break;
        }
    };
    
    function getCurrentImage(){
        return section.images.find( i => i.id === section.currentImageId);
    };
    
    function getSectionCopy(){
        return section.getSectionCopy();
    };
    
    function setImageToPreview(){
        var img = getCurrentImage();
        var sectionCopy = getSectionCopy();
        var condition = imageCategoriesNames[img.categoryId];
        var textContent = false;
        switch(condition) {
            case  'sectionsmall' :
                deleteElement('#smallLeft');
                textContent = true;
                var selector = '#sectionSmall';
                break;
            case 'sectionbig' :
                deleteElement('#bigRight');
                var selector = '#sectionBig';
                break;
            case 'sectionmobile' :
                /*not implemented */
                break;
        }
        var temp = new Template();
        temp.build(selector)
        .addAttributes('IMG',
        { 
            src : '/image/get?id=' + img.id,
            'data-categoryId' : img.categoryId
        });
        if(textContent){
            temp.addContent('P STRONG', sectionCopy.sline);
            temp.addContent('P I', sectionCopy.sline2);
        }
        temp.appendTo('#section DIV');
    };
    
    function deleteImageFromPreview(){
        var imageObj = getCurrentImage();
        var imgs = document.querySelectorAll('#preview IMG');
        for ( let img of imgs){
            var imgAttribute = img.getAttribute('data-categoryId');
            if(parseInt(imgAttribute) === parseInt(imageObj.categoryId)){
                findAncestorBySelector(img, 'DIV').removeChild(img.parentNode);
                switch(imageCategoriesNames[imgAttribute]){
                    case 'sectionsmall' :
                        leftPlaceholder();
                        break;
                    case 'sectionbig' :
                        rightPlaceholder();
                        break;
                    default:
                        break;
                };
            };
        };
    };
    
    function setCopy(){
        var sectionCopy = section.getSectionCopy();
        document.querySelector('#section H1').textContent = sectionCopy.titel;
        document.querySelector('#smallLeft STRONG').textContent = sectionCopy.sline;
        document.querySelector('#smallLeft i').textContent = sectionCopy.sline2;
        
    };
    
    function createSectionPlaceholder(){
        leftPlaceholder();
        rightPlaceholder();
    };
    
    function leftPlaceholder(){
        var tempLeft = new Template();
        var categoryId = getCategoryId('sectionsmall');
        tempLeft.build('#sectionSmall')
        .addAttributes('DIV', { class : 'sectionPlaceholder'})
        .addAttributes('IMG',{ 'data-categoryId' : categoryId})
        .appendTo('#section DIV');
    }
    
    function rightPlaceholder(){
        var tempRight = new Template();
        var categoryId = getCategoryId('sectionbig');
        tempRight.build('#sectionBig')
        .addAttributes('IMG',{ 'data-categoryId' : categoryId})
        .appendTo('#section DIV');
    }
    
    function getCategoryId(categoryString){
        for (let k in imageCategoriesNames){
            if(imageCategoriesNames[k] === categoryString){
                return k;
            }
        };
    }
};

window.onload= function() {
    sectionId = null;
    section = new Section();
    localization = new Localization(section);
    
    
    products = new Products();
    imageField = ImageField(section);
    sectionPreview = new SectionPreview();
    sectionCopy = new SectionCopy();
    
    
    
    localization.init();
    products.coordinator('init');
    sectionCopy.coordinator('init');
    sectionPreview.coordinator('init');
    
    //prepareSectionPreview();
    
    
    
    //ON LOAD
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
    };
};
