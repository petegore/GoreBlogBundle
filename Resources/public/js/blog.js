/* 
 * Javascript used by the GoreBlogBundle
 */


// We use jQuery
$(function(){
    // put initalizing code here
    
    
    // all listeners for click, change, etc... are declared here
    
    // we have to use on() in order to apply the listeners to future childs loaded dynamicaly with AJAX
    $(".articles-container").on("mouseenter", ".thumbnail-article, .main-article", function(){
        $("div:first", this).addClass("behind");
    })
    .on("mouseleave", ".thumbnail-article", function(){
        $("div:first", this).removeClass("behind");
    });
    
    $(".main-articles-container").on("mouseenter", ".main-article", function(){
        $(".content div:first", this).addClass("behind");
    })
    .on("mouseleave", ".main-article", function(){
        $(".content div:first", this).removeClass("behind");
    });
    
    
    // listener of the button "load more"
    $("#load-more").click(function(){
        var page = $(this).data("page");
        if (page === 0) return false;
        if (page === undefined) page = 2;
        loadNextArticles(page, $(this));
    });
    // listeners - end
});




function loadNextArticles(page, caller){
    $.ajax({
        url : loadMoreUrl,
        method : 'POST',
        data : 'page=' + page,    // @TODO : real page
        dataType : 'html',
        success : function(result, status, error){
            if (!result.match(/^\d{3}$/)){
                $("#small-articles .articles-container").append(result);
                caller.data("page", parseInt(page) + 1);
            } else {
                if (result === "204"){
                    // no more result, let's hide the button
                    caller.data("page", 0);
                    caller.hide();
                } else {
                    alert("Couldn't load the next articles. Error " + result + ". Please contact the webmaster.");
                }
            }
        },
        error : function(code_html, statut){
            alert("Couldn't load the next articles. Error 500. Please contact the webmaster.");
        }
    });
}