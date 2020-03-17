$(document).ready(function () {

    //init Bootstrap
    $('[data-toggle="tooltip"]').tooltip();
    $('.alert').alert()

    //init Chosen plugin
    const locale = $('#data-locale').data('locale');

    var chosenOptionsIngredients = {
        en: {
            placeholder_text_multiple: "Ingredients",
            no_results_text: "No results!",
            width: '325',
        },
        pl: {
            placeholder_text_multiple: "Składniki",
            no_results_text: "Brak wyników!",
            width: '325',
        }
    };

    $('.chosen-select-ingredients').chosen(
        chosenOptionsIngredients[locale]
    );

    //init Unite Gallery plugin
    $("#recipe-images-gallery").unitegallery({
        theme_enable_text_panel: false,
        gallery_autoplay: true,
    });

    //init CollectionType handling
    $('.form-collection').children().each(function (i) {
        addCollectionDeleteLink($(this));
    });

    $('.add-collection-widget').click(function (e) {
        var $collection = $($(this).attr('data-list-selector'));
        var counter = $collection.data('widget-counter') | $collection.children().length;

        // grab the prototype template
        var newWidget = $collection.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);

        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        $collection.data('widget-counter', counter);

        // create a new list element...
        var $newElem = $($collection.attr('data-widget-tags')).html(newWidget);

        // add remove button to it...
        addCollectionDeleteLink($newElem)

        // and add it to the list
        $newElem.appendTo($collection);
    });

    //recipes likes
    $('.like-recipe').on('click', function (e) {
        e.preventDefault();

        var $link = $(e.currentTarget);
        $link.toggleClass('far').toggleClass('fas');

        $.ajax({
            method: 'POST',
            url: $link.attr('href')
        }).done(function (response) {
            $('.like-recipe-count').html(response.hearts);
        })

    })

    //sidebar
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#dismiss, .overlay').on('click', function () {
        $('#sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });

    $('#sidebar-collapse').on('click', function () {
        $('#sidebar').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('.search-form-filters-trigger').on('click', function () {
        $('.search-form-filters').slideToggle('active');
    });

});

function addCollectionDeleteLink($element) {
    var $removeFormButton = $('<button class="remove-collection-widget btn btn-danger"><i class="fa fa-trash"></i></button>');
    $element.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        $element.remove();
    });
}

