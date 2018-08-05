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
        document.getElementById("first-post").scrollIntoView(); }

        var arguments = window.location.pathname.split( '/' );
        if(document.getElementById("editmode") != null) {
            showComments();
            document.getElementById("editmode").scrollIntoView(); alert(1); }
        else if(document.getElementById("anchor") != null) {
            showComments();
            document.getElementById("anchor").scrollIntoView(); }
        else if(arguments[3] == 'browse') {
            showComments();
            document.getElementById("first-post").scrollIntoView();  }
});

//affiche les commentaires d'un article
$(function(){
    $("#comment-count").click(function(){
        $("#comments-section").slideToggle(1000);
    });
});
