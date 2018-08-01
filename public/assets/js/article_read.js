//Scrolle jusqu'à l'ancre définie par id
function jumpToAnchor(x) {
    document.getElementById(x).scrollIntoView();
}

$( document ).ready(function() {
if(window.location.hash) {
    showComments();
    //jumpToAnchor();
}

});