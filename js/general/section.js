

/*
 * section obj set and get functions
 * @returns {Section}
 */
function Section() {
    this.sectionCopy = [],
        
    
    
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
