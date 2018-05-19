


var Template = function(){
    
    this.template = null;
    
    this.build = function(elemSelector){
        var temp = document.querySelector(elemSelector);
        var clone = temp.content.cloneNode(true);
        this.template = clone;
        return this;
    }
    
    this.buildInner = function(elemSelector){
       var temp = document.querySelector(elemSelector);
        var clone = temp.content.cloneNode(true);
        this.template = clone.querySelector('DIV');
        return this;
    }
    
    this.addAttributes = function (element, attributes) {
        var el = this.template.querySelector(element);
        for( let key in attributes){
            if(key){
                el.setAttribute(key, attributes[key]);
            }
        }
        return this;
    }
    
    this.addContent = function(element, content) {
        var el = this.template.querySelector(element);
        el.textContent = content;
        return this;
    }
    
    this.eventListener = function(element, eventOf, callback, bubbling = false) {
        var el = this.template.querySelector(element);
        el.addEventListener(eventOf, callback, bubbling);
        return this;
    }
    
    this.appendTo = function(parent) {
        if(typeof parent === 'string'){
            var parent = document.querySelector(parent);
        } 
        parent.appendChild(this.template);
    }
    
    this.appendToElement = function(parentEl,childEl) {
        var parent = this.template.querySelector(parentEl);
        parent.appendChild(childEl);
        return this;
    }
    
    this.value = function(selector, value){
        var el = this.template.querySelector(selector);
        el.value = value;
        return this;
    }
    
    this.getElement = function (selector){
        return this.template.querySelector(selector);
    }
    
    this.removeElement = function (selector){
        var el = this.template.querySelector(selector);
        el.parentNode.removeChild(el);
        return this;
    }
    
    
    
    
    
    
    
    
}


