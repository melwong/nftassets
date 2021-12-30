(function ($) {
    "use strict";
        //End Subscribe Functionality
    $(document).on('click', 'span.enable_vpe_disctiption_tab', function () {
            event.preventDefault();
            var data = $(this);
            $(this).next('p.description').toggle();
        });
})(jQuery);
