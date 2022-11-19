// $(document).on('submit', '#createComment', function(event){
//     event.preventDefault();
//     let form = $(this);
//     $.request('onCreateComment', {
//         form: form,
//         update: {'postShow::comment': '@#comments'},
//         loading: $.wn.stripeLoadIndicator,
//         flash: 1,
//         handleFlashMessage: function(message, type) {
//             $.wn.flashMsg({ text: message, class: type })
//         }
//     })
// })
$(document).on('submit', '#createComment', function(event){
    event.preventDefault();
    let form = $(this);
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
                $('#comments').append(response.partial);
                $.wn.flashMsg({ text: 'You did it!'})
            }
        }
    })
})
