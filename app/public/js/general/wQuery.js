function wQuery(elem) {
    function createWq(value) {
        var v = new wq(value);
        v[Symbol.iterator] = function* () {
            while (!v.atEnd()) yield v.next().value;
        }
        return v;
    }
    
    function wq(value) {
        var elems = value ? (value.isArray ? value : 
                typeof value === "string" ? nodeListToArray(document.querySelectorAll(value)) : 
                NodeList.prototype.isPrototypeOf(value) ? nodeListToArray(value) :
                [value]) : [];
        var currentIndex = 0;
        this.next = function() {
            if (currentIndex < elems.length) {
                return { value: elems[currentIndex++], done: false };
            } else {
                return { done: true };
            }
        };
        this.atEnd = function() {
            return currentIndex >= elems.length;
        };
        this.find = function(name) {
            if (elems.length) {
                return findElem(elems[0], name);
            }
            return null;
        };
        this.first = function() {
            if (elems.length) {
                return elems[0];
            }
            return null;
        };
        this.at = function(i) {
            return i >= 0 && i < elems.length ? elems[i] : null;
        };
        this.elements = function() {
            return elems;
        };
        this.remove = function() {
            for(let elem of elems) {
                var parent = elem.parentElement;
                parent.removeChild(elem);
            }
        };
        this.parent = function() {
            if (elems.length) {
                return createWq(elems[0].parentElement);
            }
            return null;
        };
        this.val = function(v) {
            if (elems.length && 'value' in elems[0]) {
                var elem = elems[0];
                if (typeof v === "undefined") {
                    return elem.value;
                } else {
                    elem.value = v;
                    return createWq(elem);
                }
            }
            return null;
        };
        this.closest = function(match) {
            if (elems.length) {
                var el = elems[0];
                return createWq(closestAncestor(el, match));
            }
            return [];
        }
        
        function findElem(el, name) {
            var res = el.querySelectorAll(name);
            return createWq(res);
        }
        
        function closestAncestor(el, match) {
            if (el.matches(match)) {
                return el;
            }
            if (el.parentElement) {
                return closestAncestor(el.parentElement, match)
            }
            return null;
        }
        
        function nodeListToArray(ns) {
            var a = [];
            for(let n of ns) {
                a.push(n);
            }
            return a;
        }
    }
    
    return createWq(elem);
}

