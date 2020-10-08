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
        var button = $(event.relatedTarget)
        var trickId = button.data('trickid')
        var trickName = button.data('trickname')
        var modal = $(this)
        modal.find('.modal-title-trickName').text(trickName)
        modal.find('.modal-body #trickId').val(trickId)
    })

    // Display delete media modal
    $('#deleteMediaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var mediaId = button.data('mediaid')
        var mediaName = button.data('medianame')
        var modal = $(this)
        modal.find('.modal-title-mediaName').text(mediaName)
        modal.find('.modal-body #mediaId').val(mediaId)
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