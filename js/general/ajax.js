
function formData() {
    var parts = [];
    var files = [];
    var url = '';
    var callback = null;
    
    this.url = function(val) {
        if (typeof val !== "undefined")
            url = val;
        return url;
    },
    this.callback = function(cb) {
        callback = cb;
    },
    this.addPart = function(name, value) {
        parts.push({ 'name': name, 'value': value});
    }, 
    this.addFile = function(file) {
        files.push(file);
    },
    this.post = function() {
        var request = new XMLHttpRequest();
        request.open("POST", url);
        request.responseType = "json";
        request.onreadystatechange = function() {
            if (request.readyState === 4 && callback) {
                callback(request.response);
            }
        }
        if (url.length && (parts.length || files.length)) {
            var formdata = new FormData();
            for(let part of parts) {
                formdata.append(part.name, part.value);
            }
            for(let file of files) {
                formdata.append('images[]', file, file.name)
            }
            request.send(formdata);            
        }
    }
    
    return this;
}

function ajaxGet(url, callbackSuccess) {
    var request = new XMLHttpRequest();
    request.open("GET", url);
    request.responseType = "json";
    request.onreadystatechange = function() {
        if (request.readyState === 4 && callbackSuccess) {
            callbackSuccess(request.response);
        }
    }
    request.send();
}


