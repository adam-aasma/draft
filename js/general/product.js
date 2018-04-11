

function Product(productId) {
    this.currentLanguageId = 0;
    this.currentMarketId = 0;
    this.productId = productId;
    this.imageIds = [];
    this.formatId = null;
    this.artistId = null;
    this.productInfos = [];
    this.markets = [];
         
    
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
    
    this.checkProductInfoStatus = function(languageId) {
        var productInfo = this.productInfos.find(p => p.languageId === languageId); 
        if (!productInfo) { return;};
        if(productInfo.name.length && productInfo.description.length) {
            return true;
        } else{
            return false;
        }
        
       
    }
    
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
        market.removeMaterial(materialId);
    };
    
    this.addSize = function(materialId, sizeId) {
        var material = this.getMaterial(materialId);
        material.addSizeId(sizeId);
    };
    
    this.removeSize = function(materialId, sizeId) {
        var material = this.getMaterial(materialId);
        material.removeSizeId(sizeId);
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
    
    /*
     * This function is not encapsulated but dependent of functions defined in editproduct.js
     */
    this.setImages = function(id, name) {
        createImageSpan(id, name);
        onImageAdded(id);
        
    }
    
    this.setItem = function(marketId, materialId, sizeId) {
        var market = this.getMarket(marketId);
        var material = market.getMaterial(materialId);
        material.addSizeId(sizeId);
    };
    
    this.saveProductInfo = function() {
        var pi = this.getProductInfo();
        var f = new formData();
        f.url('ajaxtest.php');

        f.addPart('requestType', 'productinfo');
        f.addPart('productId', this.productId);
        f.addPart('formatId', this.formatId);
        f.addPart('languageId', pi.languageId);
        f.addPart('name', pi.name);
        f.addPart('description', pi.description);
        f.addPart('tags', pi.tags);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
        });

        f.post();
    };
    
    this.saveProduct = function() {
        var that = this;
        var f = new formData();
        f.url('ajaxtest.php');
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
        f.url('ajaxtest.php');
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
        f.url('ajaxtest.php');
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
        });
        f.post();
    };
    /*
     * Not encapsulated may have a second look at the image handling logic
     */
    this.saveImage = function(e) {
        var that = this;
        e.preventDefault();
        var div = wQuery(e.target).closest("DIV .pictureBar").first();
        //var div = closestAncestor(e.target, "DIV .pictureBar");
        if (div) {
            var fileElem = div.querySelector("INPUT[type=file]");
            var categoryElem = div.querySelector("INPUT[type=radio]:checked")
            if (fileElem && fileElem.files.length && categoryElem) {
                var f = new formData();
                f.url('ajaxtest.php');
                f.addPart('requestType', 'image');
                f.addPart('productId', this.productId);
                f.addPart('formatId', this.formatId);
                f.addPart('image-category-id', categoryElem.value);
                f.addFile(fileElem.files[0]);
                f.callback(function(response) { 
                    console.log(JSON.stringify(response)); 
                    if (response.imageId) {
                        onImageAdded(response.imageId);
                        that.productId = response.productId;
                        createImageSpan(response.imageId, response.imageName);
                        wQuery("input[type='file']").val('');
                    }
                });
                f.post();
            }
        }
    };
    
    this.deleteImage = function(imageId, onImageDeleted) {
        var f = new formData();
        f.url('ajaxtest.php');
        f.addPart('requestType', 'deleteimage');
        f.addPart('productId', this.productId);
        f.addPart('imageId', imageId);
        f.callback(function(response) { 
            console.log(JSON.stringify(response)); 
            if (response.status === 'ok') {
                if (onImageDeleted) {
                    onImageDeleted(imageId);
                    thumbnailDelete(imageId);
                }
            }
        });
        f.post();
    };
    
    this.loadProduct = function(data) {
        for (let pd of data.productDescriptions) {
            this.setProductInfo(pd.languageId, pd.name, pd.description, pd.tags);
        }
        for (let item of data.items) {
            this.setItem(item.countryId, item.materialId, item.sizeId);
        }
        for(let image of data.images) {
            this.setImages(image.id, image.name)
        }
    };
}


function Material(materialId) {
    this.materialId = materialId;
    this.sizeIds = [];
    this.addSizeId = function(id) {
        if (this.sizeIds.indexOf(id) < 0) {
            this.sizeIds.push(id);
        }
    };
    this.removeSizeId = function(id) {
        var ix = this.sizeIds.indexOf(id);
        if (ix >= 0) {
            this.sizeIds.splice(ix, 1);
        }
    };
}

function Market(marketId) {
    this.marketId = marketId;
    this.materials = [];
    this.getMaterial = function(materialId) {
        var material = this.materials.find(m => m.materialId === materialId);
        if (!material) {
            material = new Material(materialId);
            this.materials.push(material);
        }
        return material;
    };
    this.removeMaterial = function(materialId) {
        var ix = this.materials.findIndex(m => m.materialId === materialId);
        if (ix >= 0) {
            this.materials.splice(ix, 1);
        }
    };
    this.getMaterialIds = function() {
        return this.materials.map(m => m.materialId);
    };
    
}
