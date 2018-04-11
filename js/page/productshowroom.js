/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function accordionMenu(e){
    var elTarget = e.target;
    if(!elTarget.tagName ==='SPAN') { return; };
    var spans = document.querySelectorAll('#accordionmenu SPAN');
    notShowNonSelectedAccordion();
    for (let span of spans){
        if (elTarget.getAttribute('id') === span.getAttribute('id')) {
            span.classList.toggle('nobottomborder');
            showContentProductShowRoom(elTarget.getAttribute('id')).classList.toggle('displayblock');
            continue;
        }
        if(span.classList.contains('nobottomborder')){
            span.classList.remove('nobottomborder');
        }
    }
}

function notShowNonSelectedAccordion(){
    var paras = document.querySelectorAll('#accordionmenu P');
    for ( let para of paras) {
        if( para.classList.contains('displayblock')){
            para.classList.remove('displayblock');
        }
    }
}

function showContentProductShowRoom(id){
    switch (id) {
        case 'showroomdescription':
            var el = document.querySelector('#accordionmenu #productdescription');
            break;
        case 'showroomsizes' :
            var el = document.querySelector('#accordionmenu #productsizes ');
            break;
        case 'showroomdelivery' :
            var el = document.querySelector('#accordionmenu #productdelivery');
            break;
    }
    
    return el;
}

var el = document.getElementById('accordionmenu');
el.addEventListener('click', accordionMenu, false);