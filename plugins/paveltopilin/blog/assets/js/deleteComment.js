$(document).on('click', '.deleteComment', function(){
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
