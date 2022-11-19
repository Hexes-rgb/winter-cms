$(document).on('submit', '.create-comment', function(event){
    event.preventDefault();
    console.log(12);
    let form = $(this);
    let type = $(this).attr('data-type');
    $.request('onCreateComment', {
        form: form,
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            $('.alert').empty();
            $('.alert').hide();
            responseProcessing(response, type, form);
            $(form).trigger('reset');
        }
    })
})

function responseProcessing(response, type, form){
    if(response.errors){
        jQuery.each(response.errors, function(key, value){
            $(form).children('.alert').show();
            $(form).children('.alert').append('<p>' + value + '</p>');
        })
    } else {
        if(type === 'comment'){
            $('#comments').append(response.partial);
        } else {
            let id = form.attr('id');
            $(`#answers${id}`).append(response.partial);
        }
        $.wn.flashMsg({ text: 'You did it!'})
    }
}

$(document).on('click', '.delete-сomment', function(){
    let comment_id = $(this).attr('comment-id');
    $.request('onDeleteComment', {
        data: {comment_id: comment_id},
        update: {'postShow::delete-comment': `#comment-body${comment_id}`},
        loading: $.wn.stripeLoadIndicator,
        flash: 1,
        handleFlashMessage: function(message, type) {
            $.wn.flashMsg({ text: message, class: type })
        }
    });

});

$(document).on('click', '.restore-сomment', function(){
    let comment_id = $(this).attr('comment-id');
    $.request('onRestoreComment', {
        data: {comment_id: comment_id},
        update: {'postShow::alive-comment': `#comment-body${comment_id}`},
        loading: $.wn.stripeLoadIndicator,
        flash: 1,
        handleFlashMessage: function(message, type) {
            $.wn.flashMsg({ text: message, class: type })
        }
    });

});
