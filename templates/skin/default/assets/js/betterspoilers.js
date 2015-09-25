;(function ($, document) {
    $(document).ready(function () {
        $('.betterspoiler > input ~ a:last-of-type').on('click', function () {
            var self = $(this);
            self.prevAll('input[type=checkbox]').trigger('click');

            $.scrollTo(self.parent(), 500, {interrupt: true});

            return false;
        });
    });
}(jQuery, document));
