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
 * Set form values, allow to use the same modal for multiple item
 * event :          show modal event
 * modalValue :     Object of Objects following this structure 'dataname : {type: 'type', selector: 'css-selector'}
 * example          HTML : <button data-myid="1">My Button</button><input type='hidden' class='my-id' name='id' />
 *                  JS : modalValues = {myid : {type: 'val, selector: '.my-id}}
 * modal            The modal
 */
function setModalFormValue(event,field, modal) {
    console.log(field)
    let item = modal.find(field.selector)
    if (field.type === 'val') {
        item.val(field.value)
    } else {
        item.text(field.value)
    }
}