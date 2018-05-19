

var section = null;

function getAllSections(){
    insertTablesHeader('sectionTableBody');
    section = new Section();
    ajaxGet('ajaxlistscontroller.php?requestType=getAllSections',
        function(datas){
            try {
                console.log(datas);
                if(datas.length){
                    var tBody = document.querySelector('#tbody');
                    for (let data of datas){
                        setSectionRow(data, tBody);
                        
                    }
                }
                
            } catch(error) {
                /*no products for this market or language?
                 * or an error
                 */
            }
          
        });
}

function deleteSection(e){
    var elTarget = e.target;
    section.sectionId = elTarget.getAttribute('data-sectionId');
    section.deleteSection();
    getAllSections();
    
}

function setSectionRow(data, tBody){
    var temp = document.getElementById('sectionListRow');
    var clone = temp.content.cloneNode(true);
    var id = clone.querySelector('.id A');
    id.setAttribute('href', 'addsection2.php?id=' + data.sectionId);
    id.textContent = data.sectionId;
    var button = clone.querySelector('.section-edit DIV BUTTON');
    button.setAttribute('data-sectionId', data.sectionId);
    
    for ( let copy of data.copies){
       var clone = addCopyToSectionRow(copy, clone);
    }
    for( let picture of data.imageBaseinfos){
        addPicturesToSectionRow(picture, clone.querySelector('.section-pictures DIV'));
    }
    for( let products of data.products){
        addproductsToSectionRow(products, clone.querySelector('.section-products DIV'));
    }
    button.addEventListener('click', deleteSection, false);
    return tBody.appendChild(clone);
    
}

function addproductsToSectionRow(products, clone){
    var temp = document.getElementById('sectionProducts');
    var base = temp.content.cloneNode(true);
    var clone1 = base.querySelector('DIV');
   
    
    if(parseInt(products.countryId) < 0){
        var p = document.createElement('P');
        clone1 = base;
        p.textContent = 'please add products for market';
        p.setAttribute('class', 'sectionProducts-alt contentmissingwarning');
        for( let child of clone1.children){
            clone1.removeChild(child);
        }
        clone1.appendChild(p);
        
    }
    else {
    var h2 = clone1.querySelector('H2');
    h2.textContent = products.country;
    h2.setAttribute('data-country-id', products.countryId);
    
    var span = clone1.querySelector('SPAN I');
    span.textContent = products.productCount;
    }
    
    return clone.appendChild(clone1);
}


function addPicturesToSectionRow(picture, clone){
    var t = document.getElementById('sectionPictures');
    var temp = t.content.querySelector('DIV');
    var clone1 = temp.cloneNode(true);
    var divs = clone1.querySelectorAll('DIV');
    var h2 = clone1.querySelector('DIV H2');
    var message1 =!picture.category? '?': picture.category.length? picture.category : '?';
    h2.textContent =  message1;
    
    var span = clone1.querySelector('DIV SPAN');
    if(picture.id){
        var message2 =!picture.name? 'name missing' :picture.name.length? picture.name : 'name missing';
        span.textContent = message2;
        var img = document.createElement('IMG');
        img.setAttribute('src','getImage.php?id=' + picture.id);
        img.setAttribute('class','imagethumbnails');
        divs[1].appendChild(img);
    }
    else{
        span.setAttribute('class','contentmissingwarning');
        span.textContent = 'please add picture'
    }
    
    
    
    
    
    return clone.appendChild(clone1);
}

function addCopyToSectionRow(copy, clone){
    var temp = document.getElementById('sectionCopy');
    var div = temp.content.querySelector('DIV');
    
    /*
     * setting title
     */
    var clone1 = div.cloneNode(true);
    var h2_1 = clone1.querySelector('H2');
    if(copy.language){ 
        h2_1.textContent = copy.language;
        h2_1.setAttribute('data-language-id', copy.languageId);
        var span_1 = clone1.querySelector('SPAN');
        var message1 = '';
        if(copy.title && copy.title.length){
            var message1 =  copy.title;
        } else {
            span_1.setAttribute('class','contentmissingwarning');
            message1 = 'missing title';
        }
        span_1.textContent = message1;
    }else {
        
        h2_1.setAttribute('class','contentmissingwarning');
        h2_1.textContent = 'missing';
    }
    var title = clone.querySelector('.section-title DIV');
    title.appendChild(clone1);
    
    /*
     * setting salesline
     */
    var clone2 = div.cloneNode(true);
    var h2_2 = clone2.querySelector('H2');
    if(copy.language){
        h2_2.textContent = copy.language;
        h2_2.setAttribute('data-language-id', copy.languageId);
        var span_2 = clone2.querySelector('SPAN');
        var message2 = '';
            if(copy.saleslineheader && copy.saleslineParagraph && copy.saleslineheader.length && copy.saleslineParagraph.length){
                message2 = copy.saleslineheader + '' + copy.saleslineparagraph;
            } else {
                span_2.setAttribute('class','contentmissingwarning' );
                message2 = 'please add salesline';
            }
        span_2.textContent =  message2;
    } else {
        h2_2.textContent = 'missing';
        h2_2.setAttribute('class','contentmissingwarning');
    }
    var salesline = clone.querySelector('.section-salesline DIV');
    salesline.appendChild(clone2);
    
    /*
     * setting description
     */
    var clone3 = div.cloneNode(true);
    var h2_3 = clone3.querySelector('H2');
    var message3 = '';
    if(copy.langage){
        h2_3.textContent = copy.language;
        h2_3.setAttribute('data-language-id', copy.languageId);
        var span_3 = clone3.querySelector('SPAN');
        if(copy.description && copy.description.length){
            span_3.textContent = copy.description;
        }else {
            span_3.setAttribute('class', 'contentmissingwarning');
            span_3.textContent = 'missing description';
        }
        span_3.textContent =message3;
    } else {
        h2_3.setAttribute('class','contentmissingwarning');
        h2_3.textContent = 'missing';
    }
    var description = clone.querySelector('.section-description DIV');
    description.appendChild(clone3);
    
    return clone;

}

function insertTablesHeader(templateId){
    var temp = document.getElementById(templateId);
    var clone = temp.content.cloneNode(true);
    var div = document.getElementById('adminlistbody');
    div.innerHTML = '';
    div.appendChild(clone);
}

var sections;
function sectionList(){
    this.coordinate = function(at){
        switch(at){
            case 'init' :
             
             break;
         case 'getAll' : 
             get();
             
        }
    }
    function get(){
    insertTablesHeader('sectionTableBody');
    section = new Section();
    ajaxGet('ajaxlistscontroller.php?requestType=getAllSections',
        function(datas){
            try {
                console.log(datas);
                if(datas.length){
                    var tBody = document.querySelector('#tbody');
                    for (let data of datas){
                        setSectionRow(data, tBody);
                        
                    }
                }
                
            } catch(error) {
                /*no products for this market or language?
                 * or an error
                 */
            }
          
        });
}
    
}

var viewHeader;
function ViewHeader() {
    this.coordinate = function(at){
        switch(at){
            case 'init' :
             setUrlForAddNew('products'); 
             break;
         case 'getAll' : 
             getAll();
        }
       
    };
    
    function getAll(){
        var selected = getSelectedCategory();
        var category = selected.getAttribute('data-category');
        setUrlForAddNew(category);
        switch(category){
            case 'products' :
                getAllProducts();
                break;
            case 'sections' :
                getAllSections();
                break;
            case 'slides' :
                break;
        };
    };
    
    function getSelectedCategory(){
        var options = document.querySelectorAll('#listCategory OPTION');
        for ( let option of options){
            if(option.selected){
                return option;
            }
        }
    }

    function setUrlForAddNew(category){
        var url = '';
        switch(category){
            case 'products' :
                url = '/product';
                break;
            case 'sections' :
                url = '/section';
                break;
            case 'slides' :
                url = '/slide/edit';
                break;
        }
        var addNew = document.querySelector('#addNew SPAN A');
        addNew.setAttribute('href', url);
    }
};

var productList;
function ProductList(){
    
    this.coordinate = function(at){
        switch(at){
            case 'init' :
                insertTablesHeader('productsTableBody');
                get();
                break;
        }
       
    }
    
    function get(){
        ajaxGet('/content/product',
            function(datas){
                try {
                    console.log(datas);
                    if(datas.length){
                        var tBody = document.querySelector('#tbody');
                        for (let data of datas){
                           setProductRow(data, tBody);

                        }
                    }

                } catch(error) {
                    /*no products for this market or language?
                     * or an error
                     */
                }

            });
    };
    
    function setProductRow(data, tBody){
        var temp = new Template();
        temp.build('#productListRow')
        .addAttributes('.id A', { href : "product/editProduct?id=" + data.id})
        .addContent('.id A', data.id);
        for(let name of data.names){
            temp.appendToElement('.product-name DIV', addNamesToProductRow(name));
        }
        for( let description of data.descriptions){
            temp.appendToElement('.product-description DIV', addNamesToProductRow(description));
        }
        for (let picture of data.pictures){
            temp.appendToElement('.product-pictures DIV', addPictureCol(picture));
        }
        temp.addContent('.product-pictures H2', 'nr of pictures: ' + data.pictures.length);
        for(let item in data.itemDetails){
            temp.addContent('.product-items', item.materialAndSize);
        }
        temp.appendTo(tBody);
       
    }
    
    function addPictureCol(picture){
        var temp = new Template();
        temp.build('#productPictures')
        .addAttributes('IMG', {src :'/image/get?id='  + picture.imageId })
        .addContent('SPAN', picture.category)
        return temp.template;
}
    
    function addNamesToProductRow(data){
        var temp = new Template();
        temp.build('#productContent')
        .addAttributes('H2', {'data-languageId' : data.languageId})
        .addContent('H2',data.language)
        .addContent('P', data.name);
        return temp.template;
}

    
    
}



window.onload = function(){
    viewHeader = new ViewHeader();
    productList = new ProductList();
    viewHeader.coordinate('init');
    productList.coordinate('init');
    addEventListeners();
    
}

function addEventListeners() {
        var el = document.getElementById('listCategory');
        el.addEventListener('change', function(e){ viewHeader.coordinate('getAll'); }, false);
        
        
    }



