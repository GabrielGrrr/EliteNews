$(document).ready(function() {
    // open modal
    $('#open_modal').click(function() {
        $('#modal_to_open').css(
            {
                'display':'block'
            }
        )
    });

    // close modal
    $('.delete').click(function() {
        $('#modal_to_open').css(
            {
                'display':'none'
            }
        )
    });

}) ;

//JS du Carousel
var slideIndex = 1;
var timeoutRef;
getslide(slideIndex);

function slide(n) {
    getslide(slideIndex += n);
}

function autoslide() {
    getslide(slideIndex += 1);
}

function getslide(n) {
  var i;
  var slides = document.getElementsByClassName("carousel-slide");
  var headers = document.getElementsByClassName("carousel-textcontent");
  //clearTimeout(timeoutRef);
  //var dots = document.getElementsByClassName("demo");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
     headers[i].style.display = "none";
     slides[i].style.display = "none";  
  }
  /*for (i = 0; i < dots.length; i++) {
     dots[i].className = dots[i].className.replace(" w3-red", "");
  }*/
  slides[slideIndex-1].style.display = "block";
  headers[slideIndex-1].style.display = "inline";
  //timeoutRef = setTimeout(autoslide, 6000);
  //dots[slideIndex-1].className += " w3-red";
}


//JS du panel de contrôle de recherche
function coche_categorie(categorie_elmt, categorie_name) {
    if(categorie_elmt.classList.contains('interrupteur-active')) {
        categorie_elmt.classList.remove('interrupteur-active');
        categorie_elmt.classList.add('interrupteur-inactive');
    }
    else {
        categorie_elmt.classList.remove('interrupteur-inactive');
        categorie_elmt.classList.add('interrupteur-active');
    }
}

//affiche les commentaires d'un article
function showComments() {
    var node = document.getElementById("comments-section");
    if(node.style.display == 'block') { 
        node.style.display = 'none';
      } else {
        node.style.display = 'block';
      }
}

