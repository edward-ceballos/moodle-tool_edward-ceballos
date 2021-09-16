// export const showTheThing = thingToShow => {
//     // Load the module for this thing.
//     import(`local_examples/local/types/type_${thingToShow.modname}`)
//     .then(thingModule => {
//         window.console.log(`The ${thingToShow.modname} is now available under thingModule within this scope`);

//         return thingModule;
//     });
// };

define(['jquery', 'core/modal_factory'], function($, ModalFactory) {


    return {
        init: function() {
            $('a.del').on('click', function(e) {
                e.stopPropagation();
                    e.preventDefault();
                    // return false;
                // var clickedLink = $(e.currentTarget);
                ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: 'Delete item',
                    body: 'Do you really want to delete?',
                })
                .then(function(modal) {
                    modal.setSaveButtonText('Delete');
                    // var root = modal.getRoot();
                    // root.on(ModalEvents.save, function() {
                    //     var elementid = clickedLink.data('id');
                    //     // Do something to delete item
                    // });
                    modal.show();
                    // alert(e);
                    e.stopPropagation();
                    e.preventDefault();
                });
            });
        }
    };
});

// require(['jquery', 'core/modal_factory'], function($, ModalFactory) {
//   var trigger = $('#create-modal');
//   ModalFactory.create({
//     title: 'test title',
//     body: '<p>test body content</p>',
//     footer: 'test footer content',
//   }, trigger)
//   .done(function(modal) {
//     // Do what you want with your new modal.

//     // Maybe... add a class to the modal dialog, to be able to style it.
//     modal.getRoot().addClass('mydialog');
//   });
// });