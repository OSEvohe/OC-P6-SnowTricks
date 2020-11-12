import './styles/global.scss';

const $ = require('jquery');
require('bootstrap');
const loadMore = require('./loadmore');

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();

    // Show or hide "See Medias" button
    let resizeTimer;
    showMediaList();
    $(window).resize(function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(showMediaList, 100);
    });

    // Display a modal
    $('.modal').on('show.bs.modal', function (event) {
        event.stopPropagation();
        let trigger = $(event.relatedTarget);
        let data = trigger.data()
        for (let key in data) {
            console.log(key + ':' + data[key].selector);
            if (data[key].selector !== undefined) {
                setModalValue(event, data[key], $(this), trigger)
            }
        }
    })

    // Edit media Modal
    // Add new media modal, show only the selected media type
    $('#modal-add-image').css('display', 'none')
    $('#modal-add-video').css('display', 'none')

    $('#media_type_0').click(function () {
        $('#modal-add-image').css('display', 'block')
        $('#modal-add-video').css('display', 'none')
    })

    $('#media_type_1').click(function () {
        $('#modal-add-image').css('display', 'none')
        $('#modal-add-video').css('display', 'block')
    })


    /** Display additional media form **/

        // set containers
    let addMediaPrototype = $('div#trick_trickMedia')
    let addMediaContainer = $('div#additional_medias')
    let index = {nb: addMediaContainer.find('.trick-media-form').length}

    // bind addLink event for each Media type
    bindAddLink(addMediaPrototype, addMediaContainer, $('#add_trickMedia'), index)

    // add delete link to already existing media type
    addMediaContainer.find('.trick-media-form').each(function () {
        addDeleteLink($(this));
    });

    function addTrickMedia(prototype, container, index) {
        index.nb++
        let template = prototype.attr('data-prototype')
            .replace(/__name__label__/g, 'Media')
            .replace(/__name__/g, index.nb);

        let form = $(template);
        addDeleteLink(form);
        showBootstrapFileInputValue($(form).find('.custom-file-input'));
        container.append(form);
    }

    function bindAddLink(prototype, container, link, index) {
        $(link).click(function (e) {
            addTrickMedia(prototype, container, index);
            e.preventDefault();
            return false;
        });
    }

    function addDeleteLink(prototype) {
        let deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');
        prototype.append(deleteLink);

        deleteLink.click(function (e) {
            prototype.remove();
            e.preventDefault();
            return false;
        })
    }

    loadMore.initLoadMore(
        $('#comments-list-container'),
        $('#loadmorecomment'),
        $('.comment-item').last(),
        loadMore.appendComment,
    )

    loadMore.initLoadMore(
        $('#tricks-list-container'),
        $('#loadmoretricks'),
        $('.trick-item').last(),
        loadMore.appendTrick,
        true
    )

    showBootstrapFileInputValue($('.custom-file-input'));
});


// hide or show the media list
function showMediaList() {
    let cssValue = $('#show-media-button').css('display');
    if (cssValue === 'none') {
        $('#media-list').collapse('show');
    } else {
        $('#media-list').collapse('hide');
    }
}

/**
 * Set form and modal value
 * event :          show modal event
 * modalValue :     Object of Objects following this structure 'dataname : {value: 'value', type: 'html-attribute', selector: 'css-selector'}
 * example          HTML : <button data-myid='{"value":"1","type":"value","selector":".my-id"}'>My Button</button><input type='hidden' class='my-id' name='id' value=""/>
 *                  JS :  {value: '1', type: 'value', selector: '.my-id'}
 * modal            The modal
 */
function setModalValue(event, field, modal, trigger) {
    let item = modal.find(field.selector)
    if (field.type === 'text') {
        item.text(trigger.find('.text').text());
    } else {
        if (field.type) {
            item.attr(field.type, field.value)
        } else {
            item.text(field.value)
        }
    }
}

function showBootstrapFileInputValue(fileInput) {
    $(fileInput).change(function (e) {
        let value = $(e.target).val().replace('C:\\fakepath\\', '').trim();
        $(e.target).next(".custom-file-label").html(value);
    })
}