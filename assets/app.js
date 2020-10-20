import './styles/global.scss';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();

    // Show or hide "See Medias" button
    let resizeTimer;
    showMediaList();
    $(window).resize(function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(showMediaList, 100);
    });

    // Display delete modal
    $('#deleteModal').on('show.bs.modal', function (event) {
        let data = $(event.relatedTarget).data()
        for (let key in data) {
            if (data.hasOwnProperty(key) && data[key].selector !== undefined) {
                setModalFormValue(event, data[key], $(this))
            }
        }
    })

    // Display additional media form
    let addMediaImagePrototype = $('div#trick_trickMediaPicture')
    let addMediaVideoPrototype = $('div#trick_trickMediaVideo')
    let addMediaContainer = $('div#additional_medias')
    let index = {nb: 0}

    $('#add_trickMediaPicture').click(function (e) {
        addTrickMedia(addMediaImagePrototype, addMediaContainer, index);
        e.preventDefault();
        return false;
    });

    $('#add_trickMediaVideo').click(function (e) {
        addTrickMedia(addMediaVideoPrototype, addMediaContainer, index);
        e.preventDefault();
        return false;
    });

    addMediaContainer.find('.trick-media-form').each(function() {
        addDeleteLink($(this));
    });

    function addTrickMedia(prototype, container, index) {
        index.nb++
        console.log(index)
        let template = prototype.attr('data-prototype')
            .replace(/__name__label__/g, 'Media')
            .replace(/__name__/g, index.nb);

        let form = $(template);
        addDeleteLink(form);
        container.append(form);
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
 * modalValue :     Object of Objects following this structure 'dataname : {value: 'value', type: 'type', selector: 'css-selector'}
 * example          HTML : <button data-myid='{"value":"1","type":"val","selector":".my-id"}'>My Button</button><input type='hidden' class='my-id' name='id' />
 *                  JS :  {value: '1', type: 'val', selector: '.my-id'}
 * modal            The modal
 */
function setModalFormValue(event, field, modal) {
    let item = modal.find(field.selector)
    if (field.type === 'val') {
        item.val(field.value)
    } else {
        item.text(field.value)
    }
}