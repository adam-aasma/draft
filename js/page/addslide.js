
function setSliderTitle(e){
    var title = e.target.value;
    
    
}
function setLanguagesForMarket(){
    var markets = document.querySelectorAll('#market select option');
        var languageSelect = document.querySelector('#language SELECT');
        for (let market of markets) {
            if(market.selected){
                languageSelect.innerHTML = '';
                for( language of languagesForCountry[market.value] ){
                    var option = document.createElement('OPTION');
                    option.setAttribute('data-language-id', language.languageId)
                    option.textContent = language.languageName;
                    languageSelect.appendChild(option);
                }

            }
        }
}






window.onload = function(){
    addEventListeners();
    setLanguagesForMarket();
}

function addEventListeners(){
    var el = document.getElementById('addingTitle');
    el.addEventListener('change', setSliderTitle, false);
    var el2 =document.querySelector('#market SELECT');
    el2.addEventListener('change', setLanguagesForMarket, false);
    
}