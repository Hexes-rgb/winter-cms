$(document).on('submit', '.createAnswers', function(event){
    event.preventDefault();
    let form = $(this);
    let id = form.attr('id');
    $.request('onCreateComment', {
        form: form,
        update: {'postShow::comment': `@#answers${id}`},
        loading: $.wn.stripeLoadIndicator,
        flash: 1,
        handleFlashMessage: function(message, type) {
            $.wn.flashMsg({ text: message, class: type })
        }
    })
})
