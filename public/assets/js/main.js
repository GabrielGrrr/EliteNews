//Messages d'avertissement avant action
$(function() {
  $("body").on("click", ".openmodal", function(event) {
    var $modal = $(event.target).children(".modalbg");
    $modal.css("display", "block");
  });

  $("body").on("click", ".cancelmodal", function(event) {
    var $modal = $(event.target).closest(".modalbg");
    $modal.css("display", "none");
  });
});

$(document).ready(function() {
  $("#search-icon").click(function() {
    $("#navsearchform").submit();
  });
});

//Slide / dropdown du formulaire de recherche
$(function() {
  $("#search-icon").mouseenter(function() {
    if ($("#search-form-sm").is(":hidden")) {
      $("#search-form-sm").show("slide", { direction: "left" }, 1000);
    } else {
      $("#search-form-sm").hide("slide", { direction: "left" }, 1000);
    }
  });
});

//Slide / dropdown du menu profil
$(function() {
  $("#account-icon").mouseenter(function() {
    $("#connexion-dropdown").slideToggle(300);
  });
});

//affiche les commentaires d'un article
function showComments() {
  var node = document.getElementById("comments-section");
  if (node.style.display == "block") {
    node.style.display = "none";
  } else {
    node.style.display = "block";
  }
}

/*$(document).ready(function() {
    // open modal
    $('.openmodal').click(function() {
        $('#modalbg').css(
            {
                'display':'block'
            }
        )
    });

    // close modal
    $('.modalcancel').click(function() {
        $('#modalbg').css(
            {
                'display':'none'
            }
        )
    });

}) ;**/
