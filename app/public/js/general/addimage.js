var ImageField = function(obj) {
    obj.registerEventListener(ImageChanges, createImageSpan);
    obj.registerEventListener(ImageDeleted, onImageDeleted);
    
    this.saveImage = function(e){
        e.preventDefault();
        var div = findAncestorBySelector(e.target,"#addImageDiv");
        var fileElem = div.querySelector("INPUT[type=file]");
        var categoryElem = div.querySelector("INPUT[type=radio]:checked")
        if (fileElem && fileElem.files.length && categoryElem) {
            obj.saveImage(fileElem.files, categoryElem.value);
        }
    }
    
    
    var imageCategoryClone = [];
    function resetCategoryOnDelete(){
        var img = getImage();
        if(!imageCategoryClone[img.categoryId]){
            return;
        };
        var parent = document.querySelector('#imageCheckBoxWrapper');
        var sibling = parent.querySelector('DIV');
        parent.insertBefore(imageCategoryClone[parseInt(img.categoryId)], sibling);
    }
    
    function deleteImageCategory(){
        var img = getImage();
        var imgcatId = img.categoryId;
        if(imageCategoriesNames[parseInt(imgcatId)] === 'productinterior'){
            imageCategoryClone[imgcatId] = false;
            return;
        };
        var input = document.querySelector("#addImageDiv INPUT[type='radio'][value='" + imgcatId + "']");
        var parent =  findAncestorBySelector(input,'#imageCheckBoxWrapper');
        imageCategoryClone[imgcatId] = input.parentNode.cloneNode(true);
        parent.removeChild(input.parentNode);
        var checkbox = document.querySelector('#imageCheckBoxWrapper input');
        checkbox.checked = true;
        
    }
    
    function getImage(){
        return obj.images.find(i => i.id === obj.currentImageId);
    }
    
    
    function createImageSpan() {
        document.querySelector("#addPictures input[type='file']").value = '';
        var img = obj.images.find( i => i.id === obj.currentImageId);
        var template = new Template();
        template.build('#createImageAddedRow')
        .addContent('SPAN', img.name)
        .addAttributes('BUTTON',{ 'data-imageId' : img.id})
        .addAttributes('DIV', {'data-imageId' : img.id})
        .eventListener('BUTTON', 'click',
            function()
            { 
                obj.currentImageId = img.id;
                obj.deleteImage(img.id);
            }
         )     
        .appendTo("#addPictures");
        deleteImageCategory();
    };
   
  
    function onImageDeleted(){
        resetCategoryOnDelete();
        deleteElement("#addPictures div[data-imageId='" + obj.currentImageId + "' ");
    }
    
    var button = document.querySelector("#addPictures button[type='submit']")
    button.addEventListener('click', this.saveImage);
};



