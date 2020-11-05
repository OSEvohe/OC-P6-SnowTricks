const $ = require('jquery');
const avatarPath = '/uploads/profiles/';
const defaultAvatar = require('../assets/images/user-icon-image-18.jpg');

export function initLoadMore(container, loadMoreButton, htmlPrototype, appendCallback) {

    let jsonRoute = loadMoreButton.data('jsonroute');
    // set the amount limit of items requested equal to the amount of items displayed when page is loaded
    let limit = container.children().length;
    let offset = limit;

    loadMoreButton.click(function (e) {
        let jsonUrl = jsonRoute + '/' + offset + '/' + limit;

        // immediately display a loading icon while loading data
        let loading = $('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Chargement...</span></div>').prependTo(loadMoreButton);

        $.getJSON(jsonUrl, function (data) {
            // get the html code from the element used as a "template"
            let html = htmlPrototype.get(0);

            // loop on json result and call the specified callBack for each
            data.forEach(function (item) {
                appendCallback(item, html, container);
            })

            // Hide button if last record is reached
            if (data.length < limit){
                $(loadMoreButton).hide();
            }

            // data are loaded, we remove the loading icon
            loading.remove();
        });
        offset = offset + limit;
    });
}

export function appendComment(item, prototype, container) {
    // clone the original html element so we don't modify it
    let html = $(prototype).clone();

    $(html).find('._content_').text(item.content);
    $(html).find('._displayName_').text(item.user.displayName);
    $(html).find('._createdAt_').text(item.createdAt);

    if (item.user.photo) {
        $(html).find('._photo_').attr('src', avatarPath + item.user.photo)
    } else {
        $(html).find('._photo_').attr('src',defaultAvatar.default);
    }

    // comments are added to the page with a fadeIn animation
    $(html).hide();
    $(container).append(html)
    $(html).fadeIn(800)
}

export function appendTrick(item, html) {
   // TODO
}