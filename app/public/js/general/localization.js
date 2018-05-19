
var localization;
function Localization(obj){
    
    this.marketId = 0;
    this.languageId = 0;
    
    this.init = function()
    {
        this.setSelectedMarketId();
        this.setlanguagesForMarket();
        this.addEventListeners();
        this.setSelectedLanguage();
        this.notify(OnMarketChange); 
    };
    
    
    this.registerEventListener = function(eventName, callback) {
        actionListeners.push({eventName: eventName, callback: callback});
    };
    
    this.setSelectedMarketId = function() {
        var options = document.querySelectorAll('#markets OPTION');
        for( let market of options){
            if(market.selected){
                this.marketId = market.getAttribute('data-marketId');
                obj.currentMarketId = this.marketId;
            }
        }
    };
    
    this.setSelectedLanguage = function(){
        var languages = document.querySelectorAll('#languages OPTION');
        for (let language of languages){
            if (language.selected){ 
                this.languageId = language.getAttribute('data-languageId');
                obj.currentLanguageId = this.languageId;
            };
        };
    };
    
    this.setlanguagesForMarket = function(){
        var select = document.querySelector('select#languages');
        select.innerHTML = '';
        
        var marketId = parseInt(this.marketId);
        for (let language of languagesForCountry[marketId]){
            var option = document.createElement('OPTION');
            option.setAttribute('data-languageId', language.languageId);
            option.textContent = language.languageName;
            select.appendChild(option);
        };
    };
    
    var actionListeners = [];
    this.notify = function(eventName) {
        var listeners = actionListeners.filter(a => a.eventName === eventName);
        for (let listener of listeners) {
            listener.callback();
        }
    };
    
    this.addEventListeners = function (){
        var that = this;
        var sel = document.getElementById('markets');
        sel.addEventListener('change',
        function()
            {
                that.setSelectedMarketId();
                that.setlanguagesForMarket();
                that.setSelectedLanguage();
                that.notify(OnMarketChange);
            }
        );
        var sel = document.querySelector('#copy #languages');
        sel.addEventListener('change', function()
            {
                that.setSelectedLanguage();
                that.notify(OnLanguageChange);
            }
        );
    };
    
};


