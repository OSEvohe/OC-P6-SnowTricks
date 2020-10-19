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

    // FORMULAIRE AVEC NOMBRE VARIABLE DE TRICKMEDIA
    var $container = $('div#trick_trickMediaPicture');
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('fieldset').length;
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_trickMedia').click(function (e) {
        addTrickMedia($container);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'un nouveau trick).
    if (index === 0) {
        addTrickMedia($container);
    } else {
        // S'il existe déjà des medias, on ajoute un lien de suppression pour chacun d'entre eux.
        $container.children('fieldset').each(function (index) {
            $(this).children('legend').text('Media ' + (index + 1))
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire TrickMediaType
    function addTrickMedia($container) {
        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $container.find('fieldset').length;
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Media ' + (index + 1))
            .replace(/__name__/g, index)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
    }

// La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function (e) {
            $prototype.remove();
            $container.children('fieldset').each(function (index) {
                $(this).children('legend').text('Tarif n°' + (index + 1))
            });
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
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