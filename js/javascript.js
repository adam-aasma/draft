/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function openDropDown() {
    var elem = document.getElementById("myDropdown");
    elem.classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.wt-dropdownmenu')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}


var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var x = document.getElementsByClassName("mySlides");
    if (!x.length)
        return;
    if (n > x.length) {slideIndex = 1;} 
    if (n < 1) {slideIndex = x.length;} ;
    for (var i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
    }
    x[slideIndex-1].style.display = "block"; 
}

function insertSlideshow(imageIds) {
    var slideContainer = document.getElementById("slideshow");
    var htmltext = '';
    for (var i = 0; i < imageIds.length; i++) {
        var imageUrl = 'getImage.php?id=' + imageIds[i];
        htmltext += '<img class="mySlides" src="' + imageUrl + '" height="669"+ " width="1024"' 
        + (i === 1 ? '' : ' style="display:none"') + '>';

    }
    htmltext += '<div class="buttons">';
    for (var i = 1; i <= imageIds.length; i++) {
        htmltext += '<button class="btn-slide' + (i === 1 ? ' btn-slide-active' : '') + '" onclick="selectPic(' + i + ')"></button>';
    }
    htmltext += '</div>';
    slideContainer.innerHTML = htmltext;
}

function initDocument() {
    insertSlideshow(imageIds);
    //insertPics();
}

function selectPic(n) {
    var x = document.getElementsByClassName("mySlides");
    var buttons = document.getElementsByClassName("btn-slide");
    if (n > x.length || n <= 0)
        return;
    for (var i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
        buttons[i].classList.remove("btn-slide-active");
    }
    x[n - 1].style.display = "block"; 
    buttons[n - 1].classList.add("btn-slide-active");
}

function insertPics() {
    var pics = document.getElementById("pictures");
    var sections = [
        { 
            title: 'GRAAFILINE DISAIN',
            pics: ['img/2kolmnurka.jpg', 'img/2kolmnurka.jpg', 'img/2kolmnurka.jpg', 'img/2kolmnurka.jpg']
        }, 
        { 
            title: 'FOTOGRAAFIA',
            pics: ['img/2kolmnurka.jpg', 'img/2kolmnurka.jpg', 'img/2kolmnurka.jpg', 'img/2kolmnurka.jpg']
        }, 
        { 
            title: 'ARTSY',
            pics: ['img/2kolmnurka.jpg', 'img/2kolmnurka.jpg', 'img/2kolmnurka.jpg', 'img/2kolmnurka.jpg']
       }
    ];
    var htmltext = '';
    for (var i = 0; i < sections.length; i++) {
        htmltext += '<div class="wt-section" >' +
                '<h1> ' + sections[i].title + '</h1>' +
                '<img  src="img/pic.left.jpg" height="456">' +
                '<img  src="img/pic.right.png" height="456">' +                
                '<div class="framedpics">';
                for (var j = 0; j < sections[i].pics.length; j++) {
                   htmltext += '<img  src="' + sections[i].pics[j] + '" height="312" >';
                }
                htmltext += '</div>';
                htmltext += '<div>';
                for (var j = 0; j < sections[i].pics.length; j++) {
                   htmltext += '<button>LISA OSTUKORVI</button>';
                }
                htmltext += '</div></div>';
    }
    pics.innerHTML = htmltext;
}