const $ = require('jquery');

const avatarPath = '/uploads/profiles/';
const coverPath = '/uploads/tricks/';

const defaultAvatar = require('../assets/images/user-icon-image-18.jpg');
const defaultCover = require('../assets/images/figure1.jpg');

export function initLoadMore(container, loadMoreButton, htmlPrototype, appendCallback, scrollUp = false) {

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
                // clone the original html element so we don't modify it
                appendCallback(item, $(html).clone(), container);
            })

            // Hide button if last record is reached
            if (data.length < limit) {
                $(loadMoreButton).hide();
            }

            // data are loaded, we remove the loading icon
            loading.remove();

            // display the scroll up button if scrollUp si set to true
            if (scrollUp){
                console.log($('.scroll-up a').show());
            }
        });
        offset = offset + limit;
    });
}

export function appendComment(item, html, container) {

    $(html).find('._content_').text(item.content);
    $(html).find('._displayName_').text(item.user.displayName);
    $(html).find('._createdAt_').text(item.createdAt);

    if (item.user.photo) {
        $(html).find('._photo_').attr('src', avatarPath + item.user.photo)
    } else {
        $(html).find('._photo_').attr('src', defaultAvatar.default);
    }

    append(html,container);
}

export function appendTrick(item, html, container) {

    $(html).find('._name_').text(item.name);
    $(html).find('._title_name_').attr('title', item.name);
    $(html).find('._href_detail_route_').attr('href', item.slug.detail_route);
    $(html).find('._href_edit_route_').attr('href', item.slug.edit_route);
    $(html).find('._btn_delete_').attr('data-id', '{"value":"' + item.slug.delete_route + '", "type":"href", "selector":".btn-delete"}');
    $(html).find('._btn_delete_ span').text('Supprimer Trick : ' + item.name);

    if (item.cover) {
        $(html).find('._cover_content_').css('background-image', 'url(' + coverPath + item.cover.content + ')');
    } else {
        $(html).find('._cover_content_').css('background-image', 'url(' + defaultCover.default + ')');
    }

    append(html,container);
}

function append(html, container){

    // new elements are added to the page with a fadeIn animation
    $(html).hide();
    $(container).append(html)
    $(html).fadeIn(1000)
}