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

    $('.reloadVideoAlt').click(function (e) {
        let button = $(e.target);
        let alt = button.parent().find("[id$=_alt]");
        let loading = $('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Chargement...</span></div>').prependTo(button);
        $.getJSON(button.data('json'), function (data) {
            alt.val(data.title);
            loading.remove();
        })

    })


    // New media : show only the selected media type
    $('.form-media-type-select').each(function () {
        bindShowSelectedMediaType($(this))
    });


    /** Display additional media form **/

        // set containers
    let addMediaPrototype = $('div#trick_trickMedia')
    let addMediaContainer = $('div#additional_medias')

    let index = {nb: 1}

    // bind addLink event for each Media type
    bindAddLink(addMediaPrototype, addMediaContainer, $('#add_trickMedia'), index)

    // add delete link to already existing media type
    addMediaContainer.find('.new-trick-media-form').each(function () {
        addDeleteLink($(this));
    });

    function addTrickMedia(prototype, container, index) {
        // increment index until we find a not used one
        while ($('#trick_trickMedia_' + index.nb + '_type').length) {
            index.nb++
        }

        let template = prototype.attr('data-prototype')
            .replace(/__name__label__/g, 'Media')
            .replace(/__name__/g, index.nb);

        let form = $(template);
        addDeleteLink(form);
        showBootstrapFileInputValue($(form).find('.custom-file-input'));
        bindShowSelectedMediaType($(form).find('.form-media-type-select'));
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

function bindShowSelectedMediaType(container) {
    let type_0 = container.find('[id$=_type_0]');
    let type_1 = container.find('[id$=_type_1]');

    if (type_0.is(':checked')) {
        toggleMediaType(container, 0)
    }
    type_0.click(function () {
        toggleMediaType(container, 0)
    })

    if (type_1.is(':checked')) {
        toggleMediaType(container, 1)
    }
    type_1.click(function () {
        toggleMediaType(container, 1)
    })
}

function toggleMediaType(container, type) {
    if (type === 0) {
        container.parent().find('.form-add-image-fields').css('display', 'block')
        container.parent().find('.form-add-video-fields').css('display', 'none')
    }

    if (type === 1) {
        container.parent().find('.form-add-image-fields').css('display', 'none')
        container.parent().find('.form-add-video-fields').css('display', 'block')
    }
}