(function ($) {

    $.fn.comment = function (options) {

        var settings = $.extend({
            commentFieldSelector: 'data-field="comment"',
            addressSelectSelector: 'data-field="address"'
        }, options);

        return $(this).each(function () {
            var $element = $(this);

            var $commentField = $element.find('[' + settings.commentFieldSelector + ']');
            var $addressField = $element.find('[' + settings.addressSelectSelector + ']');

            // process changing address
            var ajaxRequest;

            $addressField.on('change', function () {
                ajaxRequest = $.ajax({
                    type: "POST",
                    data: $element.serialize(),
                    url: $element.attr('data-ajax-url'),
                    complete: $.proxy(function (jqXHR, textStatus) {
                        ajaxRequest = null;
                        if (!textStatus) {
                            textStatus = 'error';
                        }

                        if (textStatus === 'success' || textStatus === 'notmodified') {
                            //var $content = $(jqXHR.responseText);
                            //console.log(jqXHR.responseText);
                            var json = $.parseJSON(jqXHR.responseText);
                            $commentField.val('');
                            $commentField.val(json.firstName + '-' + json.lastName);
                        } else if (textStatus !== 'abort') {
                            // TODO: Error handling
                        }
                    }, this)
                });
            });

        });

    }

    // Init address function
    $(function () {
        $('[data-form="comment"]').comment();
    });

})(jQuery);