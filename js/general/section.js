

/*
 * section obj set and get functions
 * @returns {Section}
 */
function Section() {
    this.sectionId = 0;
    this.sectionCopy = [],
    this.imageIds = [];
    this.marketsProductIds = [];
    this.currentLanguageId = 0;
    this.currentMarketId = 0;
        
    
    
    this.getSectionCopy = function(languageId) {
        this.currentLanguageId = languageId;
        if(this.sectionCopy[languageId]) {
            return this.sectionCopy[languageId];
        }
        this.sectionCopy[languageId] = {
            titel : '',
            sline : '',
            sline2 : '',
            description : ''
        }
        
        return this.sectionCopy[languageId];
    }
    
    this.getSectionProducts = function(){
        return this.marketsProductIds[this.currentMarketId];
    }
    
    this.updateSectionProducts = function(productIds, marketId) {
        this.currentMarketId = marketId;
        this.marketsProductIds[marketId] = [];
        for (let productid of productIds){
            this.marketsProductIds[marketId].push(productid);
        }
        this.saveProductsForSection(this.marketsProductIds[marketId], marketId);
    }
    
    this.saveProductsForSection = function(ids, marketId) {
        var that = this;
        var f = new formData();
        f.url('ajaxsectioncontroller.php');
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
        
        
    }
    
    this.saveSectionCopy = function(){
        var that = this;
        var f = new formData();
        var sectionCopy = this.getSectionCopy(this.currentLanguageId);
        f.url('ajaxsectioncontroller.php');
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
        });
        f.post();
    }
    
    this.saveImage = function(e) {
        var that = this;
        e.preventDefault();
        var div = wQuery(e.target).closest("DIV .pictureBar").first();
        if (div) {
            var fileElem = div.querySelector("INPUT[type=file]");
            var categoryElem = div.querySelector("INPUT[type=radio]:checked")
            if (fileElem && fileElem.files.length && categoryElem) {
                var f = new formData();
                f.url('ajaxsectioncontroller.php');
                f.addPart('requestType', 'image');
                f.addPart('sectionId', this.sectionId);
                f.addPart('image-category-id', categoryElem.value);
                f.addFile(fileElem.files[0]);
                f.callback(function(response) { 
                    console.log(JSON.stringify(response)); 
                    if (response.imageId) {
                        that.imageIds = response.imageId;
                        that.sectionId = response.sectionId;
                        createImageSpan(response.imageId, response.imageName);
                        setImageToPreview(response.imageId, response.categoryId);
                        wQuery("input[type='file']").val('');
                    }
                });
                f.post();
            }
        }
    }
    
    this.deleteSection = function(){
        var f = new formData();
        f.url('ajaxsectioncontroller.php');
        f.addPart('requestType', 'deleteSection');
        f.addPart('sectionId', this.sectionId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') {
                
            }
        });
        f.post();
    }
    
    this.deleteImage = function(imageId, onImageDeleted) {
        var f = new formData();
        f.url('ajaxsectioncontroller.php');
        f.addPart('requestType', 'deleteimage');
        f.addPart('sectionId', this.sectionId);
        f.addPart('imageId', imageId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') {
                if (onImageDeleted) {
                    onImageDeleted(imageId);
                    
                }
            }
        });
        f.post();
    };
    
    
    this.loadSection = function(sectionId) {
        this.sectionId = sectionId;
        var f = new formData();
        f.url('ajaxsectioncontroller.php');
        f.addPart('requestType', 'loadSection');
        f.addPart('sectionId', this.sectionId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') {
                
            }
        });
        f.post();
    }
    
    
   
        
    
    
    
    
    
    
    
}
/*
 * this faunctions are relevant only for the section page and preview
 * so better move them to a sectionPage.js
 * in case leftpic and right pic unequal height this function
 *  sets the wrapper to the same height
 *   and overflow is hidden in CSS
 */

function adjustHeightForImg () {
    
    var leftimg = document.getElementById('leftpic');
    var rightimg = document.getElementById('rightpic');
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
