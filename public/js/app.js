$(document).ready(function () {

    var chosenOptionsIngredients = {
        en: {
            placeholder_text_multiple: "Ingredients",
            no_results_text: "No results!",
            width: '500',
        },
        pl: {
            placeholder_text_multiple: "Składniki",
            no_results_text: "Brak wyników!",
            width: '500',
        }
    };

    const locale = $('#data-locale').data('locale');

    $('.chosen-select-ingredients').chosen(
        chosenOptionsIngredients[locale]
    );

    $("#recipe-images-gallery").unitegallery({
        theme_enable_text_panel: false,
        gallery_autoplay: true,
    });

    $('.add-collection-widget').click(function (e) {
        var list = jQuery(jQuery(this).attr('data-list-selector'));
        var counter = list.data('widget-counter') | list.children().length;

        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);

        var removeButton = '<button class="remove-collection-widget btn btn-danger"><i class="fa fa-trash"></i></button>';
        newWidget = newWidget + removeButton;

        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);

        newElem.on('click', '.remove-collection-widget', function (e) {
            e.preventDefault();

            $(this).parent().remove();

            return false;
        });
    });

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

    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#dismiss, .overlay').on('click', function () {
        $('#sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('.search-form-filters-trigger').on('click', function () {
        $('.search-form-filters').slideToggle('active');
    });

});

