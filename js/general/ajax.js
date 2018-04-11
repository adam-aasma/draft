
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
        if (url.length && (parts.length || files.length)) {
            var formdata = new FormData();
            for(let part of parts) {
                formdata.append(part.name, part.value);
            }
            for(let file of files) {
                formdata.append('images[]', file, file.name)
            }
            ajaxQueue.addToQueue(url, formdata, callback);
        }
    }
    
    return this;
}

var ajaxQueue = {};
(function (ajaxQ) {
    var queue = [];
    
    function sendFromQueue() {
        if (!queue.length) {
            //console.log('Call of sendFromQueue with empty queue');
            return;
        }
        var queueItem = queue[0];
        var request = new XMLHttpRequest();
        request.open("POST", queueItem.url);
        request.responseType = "json";
        request.onreadystatechange = function() {
            if (request.readyState === 4) {
                if (queueItem.callback) {
                    queueItem.callback(request.response);
                }
                queue.splice(0, 1);
                sendFromQueue();
            }
        }
        request.send(queueItem.formdata);  
    }
    
    ajaxQ.addToQueue = function(url, formdata, callback) {
        var sendImmediately = !queue.length;
        queue.push({'url': url, 'formdata': formdata, 'callback' : callback});
        if (sendImmediately) {
            sendFromQueue();
        }
    };
})(ajaxQueue);

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


