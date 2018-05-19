

// Events
//const ProductInfoChange = 'productInfoChange';
//const ImageChanges = 'imageChange';
//const ImageDeleted = 'imageDeleted';


function Product(productId) {
    this.currentLanguageId = 0;
    this.currentMarketId = 0;
    this.currentImageId = 0;
    this.productId = productId;
    this.images = [];
    this.formatId = null;
    this.artistId = null;
    this.productInfos = [];
    this.markets = [];
    
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
    
    this.getProductInfo = function(languageId) {
        if (typeof languageId === "undefined") {
            languageId = this.currentLanguageId;
        }
        var productInfo = this.productInfos.find(p => p.languageId === languageId);
        if (!productInfo) {
            productInfo = {
                languageId: languageId,
                name: '',
                description: '',
                tags: ''
            };
            this.productInfos.push(productInfo);
        }
        return productInfo;
    };
    
    this.getProductInfoStatus = function(languageId) {
        if(!languageId){
            languageId = this.currentLanguageId;
        }
        var result = ProductInfoNoInfo;
        var productInfo = this.productInfos.find(p => p.languageId === languageId); 
        if (productInfo) { 
            if (productInfo.name.length) {
                result |= ProductInfoHasName;
            }
            if (productInfo.description.length) {
                result |= ProductInfoHasDescription;
            }
            if (productInfo.tags && productInfo.tags.length) {
                result |= ProductInfoHasTags;
            }
        };
        
        return result;
    };
    
    this.getFirstLanguageIdInSet = function(languageIds) {
        if (!languageIds || !languageIds.length) {
            return 0;
        }
        for(let productInfo of this.productInfos) {
            if (languageIds.indexOf(productInfo.languageId) >= 0) {
                return productInfo.languageId;
            }
        }
        return 0;
    };
    
    this.getMaterialIdsForMarket = function() {
        var market = this.getMarket();
        return market.getMaterialIds();
    };
    
    this.getSizeIdsForMaterial = function(materialId) {
        var market = this.getMarket();
        var material = market.getMaterial(materialId);
        return material.sizeIds;
    };
        
    this.getMarket = function(marketId) {
        if (typeof marketId === "undefined") {
            marketId = this.currentMarketId;
        }
        var market = this.markets.find(function(m) { return m.marketId === marketId; } );
        if (!market) {
            market = new Market(marketId);
            this.markets.push(market);
        }
        return market;
    };
    
    this.getFirstMarketId = function() {
        if (this.markets.length) {
            return this.markets[0].marketId;
        }
        return 0;
    };
    
    this.getMaterial = function(materialId) {
        var market = this.getMarket();
        return market.getMaterial(materialId);
    };
    
    this.removeMaterial = function(materialId) {
        var market = this.getMarket();
        if (market.removeMaterial(materialId)) {
            this.saveItems();            
        }
    };
    
    this.addSize = function(materialId, sizeId) {
        var material = this.getMaterial(materialId);
        if (material.addSizeId(sizeId)) {
            this.saveItems();            
        }
    };
    
    this.removeSize = function(materialId, sizeId) {
        var material = this.getMaterial(materialId);
        if (material.removeSizeId(sizeId)) {
            this.saveItems();
        }
    };
    
    this.updateProductInfoName = function(name) {
        var pi = this.getProductInfo();
        pi.name = name;
        this.saveProductInfo();
    };
    
    this.updateProductInfoDescription = function(value) {
        var pi = this.getProductInfo();
        pi.description = value;
        this.saveProductInfo();
    };
    
    this.updateProductInfoTags = function(value) {
        var pi = this.getProductInfo();
        pi.tags = value;
        this.saveProductInfo();    
    };
    
    this.setProductInfo = function(languageId, name, description, tags) {
        var pi = this.getProductInfo(languageId);
        pi.name = name;
        pi.description = description;
        pi.tags = tags;
    };
    
    this.setItem = function(marketId, materialId, sizeId) {
        var market = this.getMarket(marketId);
        var material = market.getMaterial(materialId);
        material.addSizeId(sizeId);
    };
    
    this.setImage = function(imageId, imageName, categoryId, category) {
        this.images.push
        (
            {
                id : imageId,
                name : imageName,
                categoryId : categoryId,
                category : category,
                size : ''
            }
        );
    };
    
    this.saveProductInfo = function() {
        var that = this;
        var pi = this.getProductInfo();
        var f = new formData();
        f.url('product/update');

        f.addPart('requestType', 'productinfo');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        f.addPart('languageId', pi.languageId);
        f.addPart('name', pi.name);
        f.addPart('description', pi.description);
        f.addPart('tags', pi.tags);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.productId) {
                that.productId = response.productId;
                notify(ProductInfoChange);
            }
        });

        f.post();
    };
    
    this.saveProduct = function() {
        var that = this;
        var f = new formData();
        f.url('product/update');
        f.addPart('requestType', 'product');
        var i = 0;
        for (let imageid of this.imageIds){
            f.addPart('imageId[' + (i++) + ']', imageid);
        }  

        f.addPart('formatId', this.formatId);
        f.addPart('artistId', this.artistId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.productId) {
                that.productId = response.productId;
            }
        });

        f.post();
    };
    
    this.saveArtist = function(artist) {
        var that = this;
        var f = new formData();
        f.url('product/update');
        f.addPart('requestType', 'artist');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        if(this.artistId){
            f.addPart('artistId', this.artistId);
        }
        f.addPart('artistDesigner', artist);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.artistId) {
                that.artistId = response.artistId;
            }
        });
        f.post();
    };
    
    this.saveItems = function() {
        var that = this;
        var f = new formData();
        f.url('product/update');
        f.addPart('requestType', 'productitems');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        var market = this.getMarket();
        if ( market.materials.length) {
            for(let material of market.materials) {
                for (let size of material.sizeIds) {
                    f.addPart('item[' + market.marketId + '][' + material.materialId + '][]', size);
                }
            }
        } else {
            f.addPart('item[' + market.marketId + ']', '');
            //f.addPart('marketId', market.marketId);
        }
        f.callback(function(response) { 
            that.productId = response.productId;
            console.log(JSON.stringify(response)); 
            notify(MaterialsChanged);
        });
        f.post();
    };
    
    this.saveImage = function(files, categoryId) {
        var that = this;
        var f = new formData();
        f.url('product/update');
        f.addPart('requestType', 'image');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        f.addPart('image-category-id', categoryId);
        f.addFile(files[0]);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.imageId) {
                that.productId = response.productId;
                that.currentImageId = response.imageId;
                that.images.push(
                    {
                        id : response.imageId,
                        name : response.imageName,
                        categoryId : response.categoryId,
                        size : response.size
                    }
                );
                notify(ImageChanges);
            }
        });
        f.post();
    };
 
    
    this.deleteImage = function(imageId) {
        var that = this;
        var f = new formData();
        f.url('product/update');
        f.addPart('requestType', 'deleteimage');
        f.addPart('productId', this.productId);
        f.addPart('imageId', imageId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.imageId) {
                var img = that.images.find(i => i.id === imageId);
                that.currentImageId = img.id;
                notify(ImageDeleted);
            }
        });
        f.post();
    };
    
    this.loadProduct = function(data) {
        this.currentMarketId = null;
        this.currentLanguageId = null;
        for (let item of data.items) {
            if (this.currentMarketId === null) {
                this.currentMarketId = item.countryId;
            }
            this.setItem(item.countryId, item.materialId, item.sizeId);
        }
        validLanguages = [];
        if (this.currentMarketId !== null) {
            validLanguages = countryLanguages[this.currentMarketId].map(lan => lan.languageId);
        }
        for (let pd of data.productDescriptions) {
            if (this.currentLanguageId === null) {
                if (validLanguages.indexOf(pd.languageId) >= 0) {
                    this.currentLanguageId = pd.languageId;
                }
            }
            this.setProductInfo(pd.languageId, pd.name, pd.description, pd.tags);
        }
        for(let image of data.images) {
            this.setImage(image.id, image.name, image.categoryId, image.category);
        }
        marketId = this.currentMarketId;
        languageId = this.currentLanguageId;
        
        notify(ProductInfoChange);
        notify(MaterialsChanged);
        notify(ImageChanges);
    };
}

function Material(materialId) {
    this.materialId = materialId;
    this.sizeIds = [];
    this.addSizeId = function(id) {
        var sizeId = parseInt(id);
        if (this.sizeIds.indexOf(sizeId) < 0) {
            this.sizeIds.push(sizeId);
            return true;
        }
        return false;
    };
    this.removeSizeId = function(id) {
        var sizeId = parseInt(id);
        var ix = this.sizeIds.indexOf(sizeId);
        if (ix >= 0) {
            this.sizeIds.splice(ix, 1);
            return true;
        }
        return false;
    };
    
    this.getSizeIds = function(){
        return this.sizeIds;
    };
}

function Market(marketId) {
    this.marketId = marketId;
    this.materials = [];
    this.getMaterial = function(materialId) {
        var material = this.materials.find(m => m.materialId === parseInt(materialId));
        if (!material) {
            material = new Material(parseInt(materialId));
            this.materials.push(material);
        }
        return material;
    };
    
    this.removeMaterial = function(materialId) {
        var ix = this.materials.findIndex(m => m.materialId === parseInt(materialId));
        if (ix >= 0) {
            this.materials.splice(ix, 1);
            return true;
        }
        return false;
    };
    
    this.getMaterialIds = function() {
        return this.materials.map(m => m.materialId);
    };
    
    
}
