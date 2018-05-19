
function Printer() {
    this.printerId = 0;
    this.countryId;
    this.companyName = '';
    this.email = '';
    this.telephoneNumber = '';
    this.contactPerson = '';
    this.items = [];
    this.currentItemIdx = null;
    
    this.addItem = function() {
        this.items.push(
            {
              itemId   : null,
              material : '',
              size : '',
              technique : '',
              paperPrice : 0,
              techniquePrice : 0,
              labourPrice : 0,
              totalPrice : 0
            });
        this.currentItemIdx = this.items.length - 1;
    }
    
    this.changeCurrentItemIdx = function(step) {
        if (this.items.length) {
            this.currentItemIdx = (this.currentItemIdx + step + this.items.length) % this.items.length;
        }
    }
    
    this.addToItem = function(property, value){
        this.items[this.currentItemIdx][property] = value;
        this.saveItem();
    }
    
    this.addPrinter = function (property, value) {
        this[property] = value;
        this.savePrinter();
    }
    
    this.savePrinter = function() {
        var that = this;
        var f = new formData();
        f.url('update');

        f.addPart('requestType', 'printerGeneral');
        f.addPart('countryId', this.countryId);
        f.addPart('printerId', this.printerId);
        f.addPart('companyName', this.companyName);
        f.addPart('email', this.email);
        f.addPart('telephoneNumber', this.telephoneNumber);
        f.addPart('contactPerson', this.contactPerson);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if(response.printerId){
                that.printerId = response.printerId;
            }
        });

        f.post();
    };
    
    this.saveItem = function() {
        var that = this;
        var item = this.items[this.currentItemIdx];
        var f = new formData();
        f.url('update');

        f.addPart('requestType', 'item');
        f.addPart('printerId', this.printerId);
         f.addPart('countryId', this.countryId);
        f.addPart('itemId', item.itemId);
        f.addPart('size', item.size);
        f.addPart('technique', item.technique);
        f.addPart('material', item.material);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if(response.printerId){
                that.printerId = response.printerId;
                that.items[that.currentItemIdx].itemId = response.itemId;
            }
        });

        f.post();
    };
}
