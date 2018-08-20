/*$("#search_article_keyword").keyup(function(event) {
    clearTimeout($.data(this, 'timer'));
    if (event.keyCode == 13) //On presse enter
      search(true);
    else
      $(this).data('timer', setTimeout(search, 500));
});

function search(force) {
    var inputcontent = $("#searchString").val();
    if (!force && inputcontent.length < 3) return; //Pas d'input enter, pas plus de 2 caractères
    $.ajax({
        url:'{{ (path(/search)) }}',
        type: "POST",
        dataType: "json",
        data: {
            "search_exp": "some_var_value",
            "categories" : categories
        },
        async: true,
        success: function (data)
        {
            console.log(data)
            $('section.article-list').html(data.output);

        }
    });
}*/

/*var categories = document.getElementsByClassName("category-bouton interrupteur-active");
    var searchfield = $(this);
    $.ajax({
        url:'{{ (path(/search)) }}',
        type: "POST",
        dataType: "json",
        data: {
            "search_exp": "some_var_value",
            "categories" : categories
        },
        async: true,
        success: function (data)
        {
            console.log(data)
            $('section.article-list').html(data.output);

        }
    });
    return false;*/
