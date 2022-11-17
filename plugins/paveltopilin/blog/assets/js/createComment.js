$(document).on('submit', '#createComment', function(event){
    event.preventDefault();
    let form = $(this);
    $.request('onCreateComment', {
        form: form,
        update: {'postShow::comment': '@#comments'},
        flash: 1,
        handleFlashMessage: function(message, type) {
            $.wn.flashMsg({ text: message, class: type })
        }
    })
})
