define(['jquery', 'core/modal_factory', 'core/modal_events', 'core/str'],
        function($, ModalFactory, ModalEvents, Str) {


    return {
        init: function() {
            $('a.del').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                var clickedLink = $(e.currentTarget);
                var del = Str.get_string('delete', 'tool_edward');
                var qt = Str.get_string('confirm_del', 'tool_edward');

                ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: del,
                    body: qt,
                })
                .then(function(modal) {
                    modal.setSaveButtonText(del);
                    var root = modal.getRoot();
                    root.on(ModalEvents.save, function() {
                         window.location = clickedLink.prop('href');
                    });
                    modal.show();
                });
            });
        }
    };
});