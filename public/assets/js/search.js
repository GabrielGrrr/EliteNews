$(document).on('click', 'button#search_input', function(){
    var categories = document.getElementsByClassName("category-bouton interrupteur-active");
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
    return false;

});