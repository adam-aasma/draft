function getAllProducts(){
    insertTablesHeader('productsTableBody');
    ajaxGet('ajaxlistscontroller.php?requestType=getAll',
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
}

function getAllSections(){
    insertTablesHeader('sectionTableBody');
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

function setSectionRow(data, tBody){
    var temp = document.getElementById('sectionListRow');
    var clone = temp.content.cloneNode(true);
    var id = clone.querySelector('.id');
    id.textContent = data.sectionId;
    for ( let copy of data.copies){
       var clone = addCopyToSectionRow(copy, clone);
    }
    for( let picture of data.imageBaseinfos){
        addPicturesToSectionRow(picture, clone.querySelector('.section-pictures'));
    }
    for( let products of data.products){
        addproductsToSectionRow(products, clone.querySelector('.section-products'));
    }
    
    return tBody.appendChild(clone);
    
}

function addproductsToSectionRow(products, clone){
    var temp = document.getElementById('sectionProducts');
    var div = temp.content.querySelector('DIV');
    var clone1 = div.cloneNode(true);
    
    if(parseInt(products.countryId) < 0){
        var p = document.createElement('P');
        p.textContent = 'missing products for market'
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
    var temp = document.getElementById('sectionPictures');
    var div = temp.content.querySelector('DIV');
    var clone1 = div.cloneNode(true);
    var h2 = clone1.querySelector('H2');
    var message1 =!picture.category? 'missing': picture.category.length? picture.category : 'missing';
    h2.textContent =  message1;
    
    var span = clone1.querySelector('SPAN');
    var message2 =!picture.name? 'missing' :picture.name.length? picture.name : 'missing';
    span.textContent = message2;
    
    var img = clone1.querySelector('IMG');
    img.setAttribute('src','getImage.php?id=' + picture.id);
    
    return clone.appendChild(clone1);
}

function addCopyToSectionRow(copy, clone){
    var temp = document.getElementById('sectionCopy');
    var div = temp.content.querySelector('DIV');
    
    var clone1 = div.cloneNode(true);
    var h2_1 = clone1.querySelector('H2');
    h2_1.textContent = copy.languageId;
    var span_1 = clone1.querySelector('SPAN');
    var message1 = !copy.title? 'missing': copy.title.length? copy.title : 'missing';
    span_1.textContent = message1;
    var title = clone.querySelector('.section-title');
    title.appendChild(clone1);
    
    var clone2 = div.cloneNode(true);
    var h2_2 = clone2.querySelector('H2');
    h2_2.textContent = copy.languageId;
    var span_2 = clone2.querySelector('SPAN');
    var message2 =(!copy.saleslineheader || !copy.saleslineParagraph)? 'missing' :(copy.saleslineheader.lenght && copy.saleslineParagraph.length)? copy.saleslineheader + '' + copy.saleslineParagraph  : 'missing';
    span_2.textContent =  message2;
    var salesline = clone.querySelector('.section-salesline');
    salesline.appendChild(clone2);
    
    var clone3 = div.cloneNode(true);
    var h2_3 = clone3.querySelector('H2');
    h2_3.textContent = copy.languageId;
    var span_3 = clone3.querySelector('SPAN');
    var message3 = !copy.description? 'missing' : copy.description.length? copy.description : 'missing';
    span_3.textContent =message3;
    var description = clone.querySelector('.section-description');
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

function setProductRow(data, tBody){
    var temp = document.getElementById('productListRow');
    var clone = temp.content.cloneNode(true);
    var id = clone.querySelector('.id');
    id.textContent = data.id;
    var rowName = clone.querySelector('.product-name DIV');
    var rowDescription = clone.querySelector('.product-description DIV');
    for (let name of data.names){
       rowName.appendChild(addNamesToProductRow(name));
    }
    var description = clone.querySelector('.product-description');
    for (let name of data.descriptions){
       rowDescription.appendChild(addNamesToProductRow(name));
    }
    var pictures = clone.querySelector('.product-pictures DIV');
    var picH2 = clone.querySelector('.product-pictures H2');
    picH2.textContent = 'nr of pictures: ' + data.pictures.length;
    for (let picture of data.pictures){
        pictures.appendChild(addPictureCol(picture));
        
    }
    var item = clone.querySelector('.product-items');
    for (let itemdetail in data.itemdetails){
        item.textContent = itemdetail.materialAndSize;
    }
    
    tBody.appendChild(clone);
}

function addPictureCol(picture){
    var content = document.getElementById('productPictures');
    var div = content.content.querySelector('DIV');
    var clone = div.cloneNode(true);
    var span = clone.querySelector('SPAN');
    span.textContent = picture.category;
    var img = clone.querySelector('IMG');
    img.setAttribute('src', 'getImage.php?id=' + picture.imageId);
    return clone;
}

function addNamesToProductRow(name){
    var content = document.getElementById('productContent');
    var div = content.content.querySelector('DIV');
    var clone = div.cloneNode(true);
    var h2 = clone.querySelector('H2');
    h2.textContent = name.language;
    h2.setAttribute('data-language-id', name.languageId);
    var par = clone.querySelector('P');
    par.textContent =  name.name;
    return clone;
}

function getAll(e){
    var elTarget = e.target;
    var options = elTarget.querySelectorAll('OPTION');
    for( let option of options){
        if(option.selected){
            var selected = option.textContent
        }
    }
    switch(selected){
        case 'products' :
            getAllProducts();
            break;
        case 'sections' :
            getAllSections();
            break;
    }
    
    
}


window.onload = function(){
    getAllProducts();
    addEventListeners()
    
}

function addEventListeners() {
        var el = document.getElementById('listCategory');
        el.addEventListener('change', getAll, false);
    }



