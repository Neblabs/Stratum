(function($){
    var homePage = (typeof stratumSiteUrl !== 'undefined')? stratumSiteUrl.url : $('meta[name="coreboxSiteUrl"]').attr('content');

    $.get(homePage);
})(jQuery);