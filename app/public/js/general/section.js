
function Section() {
    this.sectionId = 0;
    this.sectionCopy = [];
    this.currentImageId = 0;
    this.imageIds = [];
    this.images = [];
    this.products = [];
    this.marketsProductIds = [];
    this.currentLanguageId = 0;
    this.currentMarketId = 0;
      
    
    var actionListeners = [];
    function notify(eventName) {
        var listeners = actionListeners.filter(a => a.eventName === eventName);
        for (let listener of listeners) {
            listener.callback();
        }
    }
    
    this.registerEventListener = function(eventName, callback) {
        actionListeners.push({eventName: eventName, callback: callback});
    };
    
    
    this.getSectionCopy = function() {
        var lanId = this.currentLanguageId;
        if(this.sectionCopy[lanId]) {
            return this.sectionCopy[lanId];
        }
        this.sectionCopy[lanId] = {
            titel : '',
            sline : '',
            sline2 : '',
            description : ''
        };
        
        return this.sectionCopy[lanId];
    };
    
    this.getSectionProducts = function(){
        return this.marketsProductIds[this.currentMarketId];
    };
    
    this.updateSectionProducts = function(productIds) {
       var marketId = this.currentMarketId;
        this.marketsProductIds[marketId] = [];
        for (let productid of productIds){
            this.marketsProductIds[marketId].push(productid);
        }
        this.saveProductsForSection(this.marketsProductIds[marketId], marketId);
    };
    
    this.saveProductsForSection = function(ids, marketId) {
        var that = this;
        var f = new formData();
        f.url('section/update');
        f.addPart('requestType', 'productsformarket');
        f.addPart('sectionId', this.sectionId);
        f.addPart('marketId', marketId);
        f.addPart('languageId', this.currentLanguageId);
        f.addPart('productIds', ids);
        f.callback(function(response) { 
           console.log(JSON.stringify(response)); 
            if (parseInt(response.sectionId) !== parseInt(that.sectionId)) {
                that.sectionId = response.sectionId;
               
            }
        });
        f.post();
        
        
    };
    
    this.saveSectionCopy = function(){
        var that = this;
        var f = new formData();
        var sectionCopy = this.getSectionCopy();
        f.url('section/update');
        f.addPart('requestType', 'sectioncopy');
        f.addPart('sectionId', this.sectionId);
        f.addPart('languageId', this.currentLanguageId);
        f.addPart('titel', sectionCopy.titel);
        f.addPart('sline', sectionCopy.sline);
        f.addPart('sline2', sectionCopy.sline2);
        f.addPart('description', sectionCopy.description);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (parseInt(response.sectionId) !== parseInt(that.sectionId)) {
                that.sectionId = response.sectionId;
            }
            notify(CopyChanges);
        });
        f.post();
    }
    
    this.saveImage = function(files, categoryId) {
        var that = this;
        var f = new formData();
        f.url('section/update');
        f.addPart('requestType', 'image');
        f.addPart('sectionId', this.sectionId);
        f.addPart('image-category-id', categoryId);
        f.addFile(files[0]);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.imageId) {
                that.imageIds = response.imageId;
                that.currentImageId = response.imageId;
                that.sectionId = response.sectionId;
                that.images.push({
                    id: response.imageId,
                    name: response.imageName,
                    categoryId: response.categoryId,
                    category: ''
                });
                notify(ImageChanges);
            }
        });
        f.post();
    };
 
    this.deleteSection = function(){
        var f = new formData();
        f.url('section/delete');
        f.addPart('requestType', 'deleteSection');
        f.addPart('sectionId', this.sectionId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') {
                
            }
        });
        f.post();
    };
    
    this.deleteImage = function(imageId) {
        var f = new formData();
        f.url('section/update');
        f.addPart('requestType', 'deleteimage');
        f.addPart('sectionId', this.sectionId);
        f.addPart('imageId', imageId);
        f.callback(function(response)
            { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') 
                notify(ImageDeleted);
            }
        );
        f.post();
    };
    
    
    this.loadSection = function(sectionId, callback) {
        var section = this;
        this.sectionId = sectionId;
        var f = new formData();
        f.url('section/update');
        f.addPart('requestType', 'loadSection');
        f.addPart('sectionId', this.sectionId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            section.sectionId = response.sectionId;
            for(let copy of response.copies) {
                section.sectionCopy[copy.languageId] = {
                    titel : copy.title,
                    sline : copy.saleslineheader,
                    sline2 : copy.saleslineParagraph,
                    description : copy.description
                };
            }
            for (let imageBaseinfo of response.imageBaseinfos) {
                section.images.push({
                    imageId: imageBaseinfo.id,
                    imageName: imageBaseinfo.name,
                    categoryId: imageBaseinfo.categoryId,
                    category: imageBaseinfo.category
                });
            }
            section.products = response.products;
            if (section.products.length) {
                section.currentLanguageId = section.products[0].languageId;
                section.currentMarketId = section.products[0].countryId;
            }
            if (callback) {
                callback();
                return;
            }
        });
        f.post();
    };
};
/*
 * this faunctions are relevant only for the section page and preview
 * so better move them to a sectionPage.js
 * in case leftpic and right pic unequal height this function
 *  sets the wrapper to the same height
 *   and overflow is hidden in CSS
 */

function adjustHeightForImg () {
    
    var leftimg = document.getElementById('leftpic');
    var rightimg = document.getElementById('rightPic');
    if(!leftimg || !rightimg || !leftimg.clientHeight || !rightimg.clientHeight){
        return;
    }
    var wrapper = document.getElementById('upperwrapper');
    wrapper.style.cssText = '';
    
    var leftheight = leftimg.clientHeight;
    var rightheight = rightimg.clientHeight;
    console.log(leftheight, rightheight);
    if(leftheight > rightheight) {
        wrapper.style.height = rightheight;
    } else if( rightheight > leftheight ) {
        wrapper.style.height = leftheight;
    }
}


function onSectionLoad(){
        adjustHeightForImg();
        window.addEventListener('resize', adjustHeightForImg, false);
}
