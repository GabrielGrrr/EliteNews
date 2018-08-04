//affiche les commentaires d'un article
function showComments() {
    var node = document.getElementById("comments-section");
    if(node.style.display == 'block') { 
        node.style.display = 'none';
      } else {
        node.style.display = 'block';
      }
}

//Scrolle jusqu'à l'ancre définie par id
function jumpToAnchor(x) {
    document.getElementById(x).scrollIntoView();
}

$(function() {
    if(window.location.hash) {
        showComments();
        jumpToAnchor(); }

});

//affiche les commentaires d'un article
$(function(){
    $("#comment-count").click(function(){
        $("#comments-section").slideToggle(1000);
    });
});
