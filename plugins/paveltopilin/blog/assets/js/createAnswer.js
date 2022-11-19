// $(document).on('submit', '.createAnswers', function(event){
//     event.preventDefault();
//     let form = $(this);
//     let id = form.attr('id');
//     $.request('onCreateComment', {
//         form: form,
//         update: {'postShow::comment': `@#answers${id}`},
//         loading: $.wn.stripeLoadIndicator,
//         flash: 1,
//         handleFlashMessage: function(message, type) {
//             $.wn.flashMsg({ text: message, class: type })
//         }
//     })
// })
$(document).on('submit', '.createAnswer', function(event){
    event.preventDefault();
    let form = $(this);
    let id = form.attr('id');
    $.request('onCreateComment', {
        form: form,
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            if(response.errors){
                jQuery.each(response.errors, function(key, value){
                    $(form).children('.alert').empty();
                    $(form).children('.alert').show();
                    $(form).children('.alert').append('<p>' + value + '</p>');
                })
            } else {
                $(`#answers${id}`).append(response.partial);
                $.wn.flashMsg({ text: 'You did it!'})
            }
        }
    })
})
