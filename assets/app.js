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
function setModalFormValue(event,field, modal) {
    let item = modal.find(field.selector)
    if (field.type === 'val') {
        item.val(field.value)
    } else {
        item.text(field.value)
    }
}