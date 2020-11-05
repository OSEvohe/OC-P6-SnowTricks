const $ = require('jquery');
const avatarPath = '/uploads/profiles/';
const defaultAvatar = require('../assets/images/user-icon-image-18.jpg');


function endOfData(container, loadMoreButton){
    $(loadMoreButton).hide();
}

export function initLoadMore(container, loadMoreButton, htmlPrototype, appendCallback) {

    let jsonRoute = loadMoreButton.data('jsonroute');
    let limit = container.children().length;
    let offset = limit;

    loadMoreButton.click(function (e) {
        let jsonUrl = jsonRoute + '/' + offset + '/' + limit;

        let loading = $('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Chargement...</span></div>').prependTo(loadMoreButton);
        $.getJSON(jsonUrl, function (data) {
            let html = htmlPrototype.get(0);

            // loop on json result
            data.forEach(function (item) {
                appendCallback(item, html, container);
            })

            // disable button if end of results reached
            if (data.length < limit){
                endOfData(container, loadMoreButton);
            }
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