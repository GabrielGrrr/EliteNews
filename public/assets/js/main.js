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

//Slide / dropdown du formulaire de recherche
$(function(){
    
    $("#search-icon").mouseenter(function(){
        $("#search-form-sm").slideToggle(300);
    });
});

//Slide / dropdown du menu profil
$(function(){
    
    $("#account-icon").mouseenter(function(){
        $("#connexion-dropdown").slideToggle(300);
    });
});
