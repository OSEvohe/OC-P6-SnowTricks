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

    // Display delete trick modal
    $('#deleteTrickModal').on('show.bs.modal', function (event) {
        setModalFormValue(event,
            {
                trickid: {type: 'val', selector: '.trickId'},
                trickname: {type: 'text', selector: '.trickName'}
            },
            $(this))
    })

    // Display delete media modal
    $('#deleteMediaModal').on('show.bs.modal', function (event) {
        setModalFormValue(event,
            {
                mediaid: {type: 'val', selector: '.mediaId'},
                medianame: {type: 'text', selector: '.mediaName'}
            },
            $(this))
    })
});


// collapse or reduce the media list
function showMediaList() {
    let cssValue = $('#show-media-button').css('display');
    if (cssValue === 'none') {
        $('#media-list').collapse('show');
    } else {
        $('#media-list').collapse('hide');
    }
}

/**
 * Set form value, allow to use the same modal for multiple item
 * event :          show modal event
 * modalValue :     Object of Objects following this structure 'dataname : {type: 'type', selector: 'css-selector'}
 * example          HTML : <button data-myid="1">My Button</button><input type='hidden' class='my-id' name='id' />
 *                  JS : modalValues = {myid : {type: 'val, selector: '.my-id}}
 * modal            The modal
 */
function setModalFormValue(event, modalValue, modal) {
    for (let key in modalValue) {
        let item = modal.find(modalValue[key].selector)
        let value = $(event.relatedTarget).data(key)
        if (modalValue[key].type === 'val') {
            item.val(value)
        } else {
            item.text(value)
        }
    }
}