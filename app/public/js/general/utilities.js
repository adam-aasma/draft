

function findAncestorByTag(element, tagName ) {
    if( element.tagName === tagName){
        return element;
    }
    if(!element.parentNode){
        return;
    }
    return findAncestorByTag(element.parentElement, tagName);
}

function findAncestorBySelector(el, selector){
    if(el.matches(selector)){
        return el;
    }
    if(!el.parentNode){
        return;
    }
    return findAncestorBySelector(el.parentNode, selector);
}


function deleteElements(selector){
    var elements = document.querySelectorAll(selector);
    for( let element of elements) {
        element.parentNode.removeChild(element);
    }
}

function deleteElement(selector){
    var element = document.querySelector(selector);
    element.parentNode.removeChild(element);
}

