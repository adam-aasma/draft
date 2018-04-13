

/*
 * section obj set and get functions
 * @returns {Section}
 */
function Section() {
    this.sectionId = 0;
    this.sectionCopy = [],
    this.imageIds = [];
        
    
    
    this.getSectionCopy = function(languageId) {
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
    
   
        
    
    
    
    
    
    
    
}
/*
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
